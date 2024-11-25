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

  // Pagination states
  const [currentPage, setCurrentPage] = useState(1);
  const itemsPerPage = 10;

  // Fetch event participants
  const fetchNames = async () => {
    try {
      const response = await axios.get(`/event/${eventId}/get-participants`);
      setNames(
        response.data.map((participant) => ({
          full_name: participant.full_name, // Actual name without "(DELETED)"
          display_name: participant.is_deleted
            ? `${participant.full_name} (DELETED)` // Add "(DELETED)" only for display
            : participant.full_name,
          user_id: participant.user_id,
          is_deleted: participant.is_deleted, // Include soft delete status
        }))
      );
    } catch (error) {
      console.error('Error fetching names:', error);
    }
  };

  useEffect(() => {
    if (eventId) {
      fetchNames(); // Fetch names when modal opens and eventId is available
    }
  }, [eventId]);

  const handleCheckboxChange = (name) => {
    setSelectedNames((prevSelected) => {
      const updatedSelected = prevSelected.includes(name)
        ? prevSelected.filter((n) => n !== name)
        : [...prevSelected, name];

      setSelectAll(updatedSelected.length === names.length);
      return updatedSelected;
    });
  };

  const handleSelectAllChange = () => {
    setSelectAll(!selectAll);
    setSelectedNames(!selectAll ? names.map((n) => n.full_name) : []);
  };

  // Pagination logic
  const indexOfLastItem = currentPage * itemsPerPage;
  const indexOfFirstItem = indexOfLastItem - itemsPerPage;
  const currentNames = names.slice(indexOfFirstItem, indexOfLastItem);
  const totalPages = Math.ceil(names.length / itemsPerPage);

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
  
      // Prepare modified JSONs
      const modifiedJsons = selectedNames.map((name, index) => {
        const jsonCopy = JSON.parse(JSON.stringify(originalJson));
  
        jsonCopy.pages.forEach((page) => {
          // Replace {{name}} placeholder only if it exists
          if (namePlaceholderExists) {
            page.children.forEach((element) => {
              if (element.type === "text" && element.text.includes("{{name}}")) {
                element.text = element.text.replace("{{name}}", name);
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
  

  
  const handleGenerateAndSave = async () => {
    if (selectedNames.length === 0) {
      alert("Please select at least one user before sending the certificate.");
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
  
    const originalJson = JSON.parse(JSON.stringify(store.toJSON())); // Clone the JSON once
    let certificateData = [];
  
    // Check if {{name}} exists in the JSON
    const namePlaceholderExists = JSON.stringify(originalJson).includes("{{name}}");
  
    try {
      if (namePlaceholderExists) {
        // Generate personalized certificates in parallel
        const promises = selectedNames.map(async (name, index) => {
          const modifiedJson = JSON.parse(JSON.stringify(originalJson)); // Clone the JSON for modification
          const uniqueId = `CERT-${uuidv4()}`; // Generate a unique ID for the certificate
  
          modifiedJson.pages.forEach((page) => {
            // Replace {{name}} placeholder
            page.children.forEach((element) => {
              if (element.type === "text" && element.text.includes("{{name}}")) {
                element.text = element.text.replace("{{name}}", name);
              }
            });
  
            // Add a background box for the unique ID
            const idBackground = {
              id: `uniqueIdBg-${index}`, // Unique ID for the background box
              type: "figure",
              name: "rectangle",
              x: 0, // Adjust X position
              y: modifiedJson.height - (modifiedJson.height * 0.03), // Adjust Y position for the background
              width: modifiedJson.width * 0.4, 
              height: modifiedJson.height * 0.03, // 3% of the canvas height
              fill: "rgba(0, 0, 0, 0.7)", // Semi-transparent black background
              draggable: false,
              selectable: false,
            };
    
            // Add the unique ID text
            const idText = {
              id: `uniqueId-${index}`, // Unique ID for the text
              type: "text",
              x: modifiedJson.width * 0.02, // 2% margin from the left edge of the canvas
              y: modifiedJson.height - (modifiedJson.height * 0.026), // Slightly above the background box center
              width: modifiedJson.width * 0.35, // Slightly smaller than the background box width
              height: modifiedJson.height * 0.02, // Height based on canvas size
              text: `ID: ${uniqueId}`, // Unique ID text
              fontSize: modifiedJson.width * 0.015, // Font size relative to canvas height
              fontFamily: "Roboto",
              fill: "white", // High-contrast text color
              align: "left", // Align text
              verticalAlign: "middle",
              draggable: false,
              selectable: false,
              alwaysOnTop: true,
            };
  
            // Add the background and text to the page
            page.children.push(idBackground);
            page.children.push(idText);
          });
  
          // Load modified JSON into the Polotno store
          await store.loadJSON(modifiedJson);
          await store.waitLoading();
          const dataURL = await store.toDataURL(); // Generate the image
  
          const userId = getUserIdByName(name);
          return { userId, imageData: dataURL, uniqueId }; // Include uniqueId in the data
        });
  
        certificateData = await Promise.all(promises);
      } else {
        // Generate shared certificate
        const uniqueId = `CERT-${uuidv4()}`; // Generate a single unique ID for the shared certificate
  
        await store.loadJSON(originalJson);
        await store.waitLoading();
        const dataURL = await store.toDataURL();
  
        // Use shared certificate for all participants
        certificateData = selectedNames.map((name) => ({
          userId: getUserIdByName(name),
          imageData: dataURL,
          uniqueId, // Same ID for all users
        }));
      }
  
      // Send certificates to backend
      const response = await axios.post(`/event/${eventId}/participants/send-certificates`, {
        data: certificateData,
      });
  
      setLoading(false);
      showLoadingModal(false);
  
      if (response.data && response.data.message === "Certificates sent successfully!") {
        setTimeout(() => {
          alert("Certificates sent successfully!");
        }, 300);
      }
    } catch (error) {
      console.error("Error generating and saving certificates:", error);
    } finally {
      await store.loadJSON(originalJson); // Reset the Polotno store to the original JSON
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
        <Checkbox
          label="Select All"
          checked={selectAll}
          onChange={handleSelectAllChange}
          style={{ marginBottom: '10px' }}
        />
      <div style={{ marginBottom: '20px', overflowY: 'auto', maxHeight: '200px' }}>
        {currentNames.map((name, index) => (
          <Checkbox
            key={index}
            label={name.display_name} // Use display_name for UI (includes "(DELETED)")
            checked={selectedNames.includes(name.full_name)} // Use full_name for logic
            onChange={() => handleCheckboxChange(name.full_name)} // Pass full_name to handle selection
          />
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
