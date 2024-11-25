import React, { useEffect, useState } from 'react';
import { Button, Classes, Dialog, Checkbox } from '@blueprintjs/core';
import axios from 'axios';
import { createStore } from 'polotno/model/store';
import { v4 as uuidv4 } from 'uuid';


// Loading Modal Component
const LoadingModal = ({ isOpen }) => (
  <Dialog
    icon="cloud-upload"
    title="Processing"
    isOpen={isOpen}
    style={{ width: '400px', zIndex: 1500 }}
  >
    <div className={Classes.DIALOG_BODY}>
      <p>Your certificates are being generated and sent. Please wait...</p>
    </div>
  </Dialog>
);

// Export Modal for Certificate Selection and Preview
const ExportModal = ({ isOpen, store, onClose, eventId, showLoadingModal }) => {
  const [loading, setLoading] = useState(false);
  const [names, setNames] = useState([]); // Names fetched from the database
  const [selectedNames, setSelectedNames] = useState([]); // Selected names for image generation
  const [selectAll, setSelectAll] = useState(false);
  const [images, setImages] = useState([]);
  const [filteredNames, setFilteredNames] = useState([]);
  const [searchQuery, setSearchQuery] = useState(''); 
  const [selectAllExceptSent, setSelectAllExceptSent] = useState(false);
  const [eventDetails, setEventDetails] = useState({ title: "", address: "" });
  // Pagination states
  const [currentPage, setCurrentPage] = useState(1);
  const itemsPerPage = 10;

  // Fetch event participants
  const fetchNames = async () => {
    try {
      const response = await axios.get(`/event/${eventId}/get-participants`);
  
      // Extract event details
      const { participants, event_title, address } = response.data;
  
      const participantData = participants.map((participant) => ({
        full_name: participant.full_name, // Actual name without "(DELETED)"
        display_name: participant.is_deleted
          ? `${participant.full_name} (DELETED)` // Add "(DELETED)" only for display
          : participant.full_name,
        user_id: participant.user_id,
        is_deleted: participant.is_deleted,
        college: participant.college,
        certificate_received: participant.certificate_received,
      }));
  
      // Sort participants: not sent users first, then sent users
      const sortedParticipants = participantData.sort((a, b) => {
        if (!a.certificate_received && b.certificate_received) {
          return -1; // `a` (not sent) comes before `b` (sent)
        }
        if (a.certificate_received && !b.certificate_received) {
          return 1; // `b` (not sent) comes before `a` (sent)
        }
        return 0; // Keep original order for users with the same status
      });
  
      setNames(sortedParticipants); // Set sorted participants
      setFilteredNames(sortedParticipants); // Initialize filtered names with sorted participants
  
      // Set event details (if needed elsewhere)
      setEventDetails({ title: event_title, address });
    } catch (error) {
      console.error("Error fetching names:", error);
    }
  };

  const refreshParticipantList = async () => {
    try {
      const response = await axios.get(`/event/${eventId}/get-participants`);
      const { participants } = response.data;
  
      // Transform and sort data
      const updatedParticipants = participants.map((participant) => ({
        full_name: participant.full_name,
        display_name: participant.display_name,
        user_id: participant.user_id,
        is_deleted: participant.is_deleted,
        college: participant.college,
        certificate_received: participant.certificate_received,
      }));
  
      // Sort participants as needed
      const sortedParticipants = updatedParticipants.sort((a, b) => {
        if (!a.certificate_received && b.certificate_received) return -1;
        if (a.certificate_received && !b.certificate_received) return 1;
        return 0;
      });
  
      setNames(sortedParticipants); // Update state
      setFilteredNames(sortedParticipants); // Update filtered list
      const updatedSelectedNames = selectedNames.filter(
        (name) => !updatedParticipants.find(
          (participant) => participant.full_name === name && participant.certificate_received
        )
      );
  
      setSelectedNames(updatedSelectedNames);
    } catch (error) {
      console.error("Error refreshing participant list:", error);
    }
  };

  useEffect(() => {
    if (eventId) {
      fetchNames(); // Fetch names when modal opens and eventId is available
    }
  }, [eventId]);

  useEffect(() => {
    setFilteredNames(names); // Reset filtered names when modal opens
  }, [names]);

  const handleSearchChange = (e) => {
    const query = e.target.value.toLowerCase();
    setSearchQuery(query);
    setFilteredNames(
      names.filter((name) =>
        name.display_name.toLowerCase().includes(query)
      )
    );
    setSelectAll(false); // Reset select all when search query changes
    setSelectedNames([]); // Reset selected names
  };

  const handleCheckboxChange = (name) => {
    setSelectedNames((prevSelected) => {
      const updatedSelected = prevSelected.includes(name)
        ? prevSelected.filter((n) => n !== name)
        : [...prevSelected, name];

      setSelectAll(updatedSelected.length === names.length);
      setSelectAllExceptSent(
        updatedSelected.length ===
          names.filter((n) => !n.certificate_received).length // Check if all not-sent participants are selected
      );
      return updatedSelected;
    });
  };

  const handleSelectAllChange = () => {
    setSelectAll(!selectAll);
    setSelectedNames(!selectAll ? names.map((n) => n.full_name) : []);
  };

  const handleSelectAllExceptSent = () => {
    if (selectAllExceptSent) {
      // Deselect all not-sent users
      setSelectedNames((prevSelected) =>
        prevSelected.filter((name) =>
          names.some(
            (n) => n.full_name === name && n.certificate_received // Keep only sent users in the selection
          )
        )
      );
      setSelectAllExceptSent(false); // Uncheck the checkbox
    } else {
      // Select all not-sent users
      const notSentNames = names
        .filter((name) => !name.certificate_received) // Filter out users who have received certificates
        .map((name) => name.full_name); // Get their full names
  
      setSelectedNames(notSentNames); // Update selected names with only not-sent users
      setSelectAll(false); // Ensure "Select All" is unchecked
      setSelectAllExceptSent(true); // Check the "Select All Except Sent" checkbox
    }
  };

  // Pagination logic
  const indexOfLastItem = currentPage * itemsPerPage;
  const indexOfFirstItem = indexOfLastItem - itemsPerPage;
  const currentNames = filteredNames.slice(indexOfFirstItem, indexOfLastItem);
  const totalPages = Math.ceil(filteredNames.length / itemsPerPage);

  const handlePageChange = (direction) => {
    if (direction === 'next' && currentPage < totalPages) {
      setCurrentPage(currentPage + 1);
    } else if (direction === 'prev' && currentPage > 1) {
      setCurrentPage(currentPage - 1);
    }
  };

  // PREVIEW
  const handleGenerate = async () => {
    if (selectedNames.length === 0) {
      alert("Please select at least one user before generating the preview.");
      return;
    }
  
    setLoading(true);
    const originalJson = JSON.parse(JSON.stringify(store.toJSON())); // Deep clone the JSON
  
    const newImages = [];
  
    try {
      // Check if {{name}} exists in the JSON
      const namePlaceholderExists = JSON.stringify(originalJson).includes("{{name}}");
      const schoolPlaceholderExists = JSON.stringify(originalJson).includes("{{school}}");
      const eventTitlePlaceholderExists = JSON.stringify(originalJson).includes("{{event_title}}");
      const addressPlaceholderExists = JSON.stringify(originalJson).includes("{{address}}");
  
      // Prepare modified JSONs
      const modifiedJsons = selectedNames.map((name, index) => {
        const jsonCopy = JSON.parse(JSON.stringify(originalJson));
        const user = names.find((n) => n.full_name === name);
        const college = user ? user.college : "";

        jsonCopy.pages.forEach((page) => {
          // Replace {{name}} placeholder only if it exists
          if (namePlaceholderExists) {
            page.children.forEach((element) => {
              if (element.type === "text" && element.text.includes("{{name}}")) {
                element.text = element.text.replace("{{name}}", name);
              }
            });
          }
          if (schoolPlaceholderExists) {
            page.children.forEach((element) => {
              if (element.type === "text" && element.text.includes("{{school}}")) {
                element.text = element.text.replace("{{school}}", college);
              }
            });
          }
          if (eventTitlePlaceholderExists) {
            page.children.forEach((element) => {
              if (element.type === "text" && element.text.includes("{{event_title}}")) {
                element.text = element.text.replace("{{event_title}}", eventDetails.title);
              }
            });
          }
          if (addressPlaceholderExists) {
            page.children.forEach((element) => {
              if (element.type === "text" && element.text.includes("{{address}}")) {
                element.text = element.text.replace("{{address}}", eventDetails.address);
              }
            });
          }
          // Generate a random unique ID using uuid
          const uniqueId = uuidv4();
  
          // Add a background box for the unique ID
          const idBackground = {
            id: `uniqueIdBg-${index}`, // Unique ID for the background box
            type: "figure",
            name: "rectangle",
            x: 0, // Adjust X position
            y: jsonCopy.height - (jsonCopy.height * 0.03), // Adjust Y position for the background
            width: jsonCopy.width * 0.4, 
            height: jsonCopy.height * 0.03, // 3% of the canvas height
            fill: "rgba(0, 0, 0, 0.7)", // Semi-transparent black background
            draggable: false,
            selectable: false,
          };
  
          // Add the unique ID text
          const idText = {
            id: `uniqueId-${index}`, // Unique ID for the text
            type: "text",
            x: jsonCopy.width * 0.02, // 2% margin from the left edge of the canvas
            y: jsonCopy.height - (jsonCopy.height * 0.026), // Slightly above the background box center
            width: jsonCopy.width * 0.35, // Slightly smaller than the background box width
            height: jsonCopy.height * 0.02, // Height based on canvas size
            text: `ID: CERT-${uniqueId}`, // Unique ID text
            fontSize: jsonCopy.width * 0.015, // Font size relative to canvas height
            fontFamily: "Roboto",
            fill: "white", // High-contrast text color
            align: "left", // Align text
            verticalAlign: "middle",
            draggable: false,
            selectable: false,
            alwaysOnTop: true,
          };
  
          // Add the elements to the page
          page.children.push(idBackground);
          page.children.push(idText);
        });
  
        return jsonCopy;
      });
  
      // Render images from modified JSONs
      for (const json of modifiedJsons) {
        await store.loadJSON(json); // Load the modified JSON
        await store.waitLoading();
        const dataURL = await store.toDataURL(); // Generate the image
        newImages.push(dataURL); // Store the generated image
      }
  
      setImages(newImages); // Update the state with all generated images
    } catch (error) {
      alert("Something went wrong while generating previews.");
      console.error("Error in preview generation:", error);
    } finally {
      setLoading(false);
      await store.loadJSON(originalJson); // Restore original JSON after previews
      await store.waitLoading();
    }
  };
  
  const saveDesign = async (eventId) => {
    const canvasData = store.toJSON();
  
    try {
      const dataURL = await store.toDataURL();
  
      const response = await axios.post(`/event/${eventId}/certificates/save`, {
        canvas: canvasData,
        image: dataURL,
      });
  
      alert('Design saved successfully!');
      return response.data.certificateId; // Return the certificateId directly
    } catch (error) {
      console.error('Error saving design:', error);
      return null; // Return null if saving fails
    }
  };

  const handleGenerateAndSave = async () => {
    if (selectedNames.length === 0) {
      alert("Please select at least one user before sending the certificate.");
      return;
    }
    const certificateId = await saveDesign(eventId); // Save design and get the certificateId
    if (!certificateId) {
      alert("Failed to save the certificate design. Cannot proceed with sending.");
      return;
    }
    try {
      // Check if a certificate exists for the event
      const certificateExistsResponse = await axios.get(`/event/${eventId}/certificate-exists`);
      if (!certificateExistsResponse.data.exists) {
        alert("No certificate has been saved for this event. Please save a certificate before sending.");
        return;
      }
    } catch (error) {
      console.error("Error checking certificate existence:", error);
      alert("An error occurred while verifying the certificate.");
      return;
    }
  
    setLoading(true);
    showLoadingModal(true);
  
    const originalJson = JSON.parse(JSON.stringify(store.toJSON())); // Deep clone the JSON
    const certificateData = []; // Array to store data for batch saving
  
    const namePlaceholderExists = JSON.stringify(originalJson).includes("{{name}}");
    const schoolPlaceholderExists = JSON.stringify(originalJson).includes("{{school}}");
    const eventTitlePlaceholderExists = JSON.stringify(originalJson).includes("{{event_title}}");
    const addressPlaceholderExists = JSON.stringify(originalJson).includes("{{address}}");
  
    try {
      const modifiedJsons = selectedNames.map((name, index) => {
        const jsonCopy = JSON.parse(JSON.stringify(originalJson));
        const user = names.find((n) => n.full_name === name);
        const college = user ? user.college : "";
  
        jsonCopy.pages.forEach((page) => {
          if (namePlaceholderExists) {
            page.children.forEach((element) => {
              if (element.type === "text" && element.text.includes("{{name}}")) {
                element.text = element.text.replace("{{name}}", name);
              }
            });
          }
          if (schoolPlaceholderExists) {
            page.children.forEach((element) => {
              if (element.type === "text" && element.text.includes("{{school}}")) {
                element.text = element.text.replace("{{school}}", college);
              }
            });
          }
          if (eventTitlePlaceholderExists) {
            page.children.forEach((element) => {
              if (element.type === "text" && element.text.includes("{{event_title}}")) {
                element.text = element.text.replace("{{event_title}}", eventDetails.title);
              }
            });
          }
          if (addressPlaceholderExists) {
            page.children.forEach((element) => {
              if (element.type === "text" && element.text.includes("{{address}}")) {
                element.text = element.text.replace("{{address}}", eventDetails.address);
              }
            });
          }
  
          const uniqueId = uuidv4();
          const idBackground = {
            id: `uniqueIdBg-${index}`,
            type: "figure",
            name: "rectangle",
            x: 0,
            y: jsonCopy.height - jsonCopy.height * 0.03,
            width: jsonCopy.width * 0.4,
            height: jsonCopy.height * 0.03,
            fill: "rgba(0, 0, 0, 0.7)",
            draggable: false,
            selectable: false,
          };
  
          const idText = {
            id: `uniqueId-${index}`,
            type: "text",
            x: jsonCopy.width * 0.02,
            y: jsonCopy.height - jsonCopy.height * 0.026,
            width: jsonCopy.width * 0.35,
            height: jsonCopy.height * 0.02,
            text: `ID: CERT-${uniqueId}`,
            fontSize: jsonCopy.width * 0.015,
            fontFamily: "Roboto",
            fill: "white",
            align: "left",
            verticalAlign: "middle",
            draggable: false,
            selectable: false,
            alwaysOnTop: true,
          };
  
          page.children.push(idBackground);
          page.children.push(idText);
        });
  
        return jsonCopy;
      });
  
      for (const json of modifiedJsons) {
        await store.loadJSON(json);
        await store.waitLoading();
        const dataURL = await store.toDataURL();
        const userId = getUserIdByName(selectedNames[modifiedJsons.indexOf(json)]);
        certificateData.push({ userId, imageData: dataURL });
      }
  
      // Send all certificates in a single request
      const response = await axios.post(`/event/${eventId}/participants/send-certificates`, {
        data: certificateData,
      });
  
      if (response.data && response.data.message === "Certificates sent successfully!") {
        alert("Certificates sent successfully!");
        await refreshParticipantList();
      }
    } catch (error) {
      console.error("Error generating and saving certificates:", error);
      alert("An error occurred while generating or saving certificates.");
    } finally {
      setLoading(false);
      showLoadingModal(false);
      await store.loadJSON(originalJson); // Restore the original JSON after completion
      await store.waitLoading();
    }
  };
  

  const getUserIdByName = (name) => {
    const user = names.find((n) => n.full_name === name);
    return user ? user.user_id : null;
  };

  return (
    <Dialog
      icon="info-sign"
      onClose={onClose}
      title="Send Certificate"
      isOpen={isOpen}
      style={{
        width: '90vw',
        maxWidth: '600px',
        margin: '0 auto',
        position: 'relative',
        top: '45px',

      }}
    >
      <div className={Classes.DIALOG_BODY} style={{ padding: '1rem' }}>
      <h5>Select Names:</h5>
      <input
          type="text"
          placeholder="Search for a participant..."
          value={searchQuery}
          onChange={handleSearchChange}
          style={{
            marginBottom: '10px',
            padding: '5px',
            width: '100%',
            border: '1px solid #ddd',
            borderRadius: '4px',
          }}
        />
      <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '10px' }}>
      <Checkbox
        label="Select All"
        checked={selectAll}
        onChange={handleSelectAllChange}
        style={{ marginBottom: '10px' }}
      />
      <Checkbox
        label="Select All Except Sent"
        checked={selectAllExceptSent} // This will not have a persisted state
        onChange={handleSelectAllExceptSent}
      />
      </div>
      <div style={{ marginBottom: '20px', overflowY: 'auto', maxHeight: '200px' }}>
        {currentNames.map((name, index) => (
          <div key={index} style={{ display: 'flex', alignItems: 'center', marginBottom: '5px' }}>
            <Checkbox
              label={name.display_name} // Use display_name for UI (includes "(DELETED)")
              checked={selectedNames.includes(name.full_name)} // Use full_name for logic
              onChange={() => handleCheckboxChange(name.full_name)} // Pass full_name to handle selection
              disabled={name.certificate_received} // Disable if certificate is already sent
            />
            {name.certificate_received && (
              <span style={{ marginLeft: '10px', color: 'green', fontSize: '12px' }}>✔ Sent</span>
            )}
          </div>
        ))}
      </div>
        
        {/* Pagination Controls */}
        <div style={{ display: 'flex', justifyContent: 'center'}}>
          <Button onClick={() => handlePageChange('prev')} disabled={currentPage === 1}>
            Previous
          </Button>
          <span style={{ margin: '0 10px' }}>
            Page {currentPage} of {totalPages}
          </span>
          <Button onClick={() => handlePageChange('next')} disabled={currentPage === totalPages}>
            Next
          </Button>
        </div>
  
        <h5>Generated Previews:</h5>
        <div style={{
          display: 'flex',
          overflowX: 'scroll',
          gap: '10px'
        }}>
          {images.map((url, index) => (
            <img
              src={url}
              key={index}
              alt={`Preview for ${selectedNames[index]}`}
              style={{
                display: 'inline-block',
                width: '90%',
                maxWidth: '600px',
                height: 'auto',
                boxShadow: '0px 4px 8px rgba(0, 0, 0, 0.2)',
                justifyContent: 'center'
              }}
            />
          ))}
        </div>
      </div>
  
      <div className={Classes.DIALOG_FOOTER}>
        <div className={Classes.DIALOG_FOOTER_ACTIONS} style={{
          display: 'flex',
          flexDirection: window.innerWidth < 768 ? 'column' : 'row',
          gap: '10px',
        }}>
          <Button onClick={onClose}>Close</Button>
          <Button onClick={handleGenerate} intent="primary" loading={loading}>
            Preview
          </Button>
          <Button onClick={handleGenerateAndSave} intent="success" loading={loading}>
            Send Certificates
          </Button>
        </div>
      </div>
    </Dialog>
  );
};

// SendButton component
export const SendButton = ({ store, eventId }) => {
  const [modalVisible, setModalVisible] = useState(false);
  const [loadingModalVisible, setLoadingModalVisible] = useState(false);

  return (
    <>
      <Button intent="primary" onClick={() => setModalVisible(true)}>
        Send
      </Button>
      <ExportModal
        store={store}
        isOpen={modalVisible}
        onClose={() => setModalVisible(false)}
        eventId={eventId}
        showLoadingModal={setLoadingModalVisible}
      />
      <LoadingModal isOpen={loadingModalVisible} />
    </>
  );
};

export default SendButton;
