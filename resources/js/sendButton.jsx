import React, { useEffect, useState } from 'react';
import { Button, Classes, Dialog, Checkbox } from '@blueprintjs/core';
import axios from 'axios';

const ExportModal = ({ isOpen, store, onClose, eventId }) => {
  const [loading, setLoading] = useState(false);
  const [names, setNames] = useState([]); // To store names fetched from the database
  const [selectedNames, setSelectedNames] = useState([]); // To store selected names for image generation
  const [images, setImages] = useState([]);

  // Function to fetch names (event participants) from the database using eventId
  const fetchNames = async () => {
    try {
      const response = await axios.get(`/event/${eventId}/get-participants`);
      setNames(response.data); // Assume the data is an array of names
    } catch (error) {
      console.error('Error fetching names:', error);
    }
  };

  useEffect(() => {
    if (eventId) {
      fetchNames(); // Fetch names when the modal opens and eventId is available
    }
  }, [eventId]); // Re-fetch if eventId changes

  // Handle checkbox change
  const handleCheckboxChange = (name) => {
    setSelectedNames((prevSelected) => {
      if (prevSelected.includes(name)) {
        return prevSelected.filter((n) => n !== name); // If already selected, remove from the list
      } else {
        return [...prevSelected, name]; // Add the name to the selected list
      }
    });
  };

  // Function to replace {{name}} in the JSON structure
  const replaceNameInStore = (json, name) => {
    const clonedJson = JSON.parse(JSON.stringify(json)); // Clone the JSON to make it mutable

    // Traverse through pages and elements in the cloned JSON
    clonedJson.pages.forEach((page) => {
      page.children.forEach((element) => {
        if (element.type === 'text' && element.text.includes('{{name}}')) {
          element.text = element.text.replace('{{name}}', name); // Replace {{name}} placeholder
        }
      });
    });
    return clonedJson;
  };

  const handleGenerate = async () => {
    if (selectedNames.length === 0) {
      alert('Please select at least one user before generating the preview.');
      return;
    }
  
    setLoading(true); // Start loading state
    const json = store.toJSON(); // Get the original design as JSON
  
    const newImages = []; // Array to store generated images
  
    try {
      for (let name of selectedNames) {
        // Clone and modify the store's JSON
        const modifiedJson = replaceNameInStore(json, name);
  
        // Load the modified JSON into the store
        await store.loadJSON(modifiedJson);  // Ensure it's properly awaited
  
        // Wait for the store to finish loading changes (if necessary)
        await store.waitLoading();
  
        // Render the current canvas to a data URL (an image)
        const dataURL = await store.toDataURL();
  
        // Add the generated image to the list
        newImages.push(dataURL);
      }
  
      // Set the generated images in the state for display
      setImages(newImages);
    } catch (e) {
      alert('Something went wrong');
      console.error(e);
    } finally {
      setLoading(false); // Stop loading state
      // Revert back to the original JSON
      if (json) {
        await store.loadJSON(json); // Ensure it's properly awaited
        await store.waitLoading();
      }
    }
  };
  
  const handleGenerateAndSave = async () => {
    if (selectedNames.length === 0) {
      alert('Please select at least one user before sending the certificate.');
      return;
    }
  
    setLoading(true); // Start loading state
    const json = store.toJSON(); // Get the original design as JSON
  
    const certificateData = []; // Array to store certificate data
  
    try {
      for (let name of selectedNames) {
        // Clone and modify the store's JSON
        const modifiedJson = replaceNameInStore(json, name);
  
        // Load the modified JSON into the store
        await store.loadJSON(modifiedJson);  // Ensure it's properly awaited
  
        // Wait for the store to finish loading changes (if necessary)
        await store.waitLoading();
  
        // Render the current canvas to a data URL (an image)
        const dataURL = await store.toDataURL();
  
        // Map the user ID with their generated image
        const userId = getUserIdByName(name); // You need to implement getUserIdByName function
        certificateData.push({
          userId,
          imageData: dataURL, // The base64 image data
        });
      }
  
      // Send the generated certificate data to your backend to save the image paths
      const response = await axios.post(`/event/${eventId}/participants/send-certificates`, {
        data: certificateData,
      });
  
      if (response.data.message === 'Certificates saved successfully!') {
        alert('Certificates saved successfully!');
      }
    } catch (error) {
      console.error('Error generating and saving certificates:', error);
    } finally {
      setLoading(false); // Stop loading state
  
      // Revert back to the original JSON
      if (json) {
        await store.loadJSON(json); // Ensure it's properly awaited
        await store.waitLoading();
      }
    }
  };

  // This function should map the user's name to their user ID
  const getUserIdByName = (name) => {
    // You would need to fetch this or maintain a map of names to user IDs when fetching participants
    const user = names.find(n => n.full_name === name);
    return user ? user.user_id : null; // Assuming you have user_id in your response data
  };

  return (
    <Dialog
      icon="info-sign"
      onClose={onClose}
      title="Send Certificate"
      isOpen={isOpen}
      style={{ width: '800px'}}
    >
      <div className={Classes.DIALOG_BODY}>
        <h5>Select Names:</h5>
        <div style={{ marginBottom: '20px' }}>
          {names.map((name, index) => (
            <Checkbox
              key={index}
              label={name.full_name}  // Access the full_name property here
              checked={selectedNames.includes(name.full_name)}
              onChange={() => handleCheckboxChange(name.full_name)}
            />
          ))}
        </div>

        <h5>Generated Previews:</h5>
        <div style={{ display: 'flex', overflow: 'auto' }}>
          {images.map((url, index) => (
            <img
              src={url}
              key={index}
              alt={`Preview for ${selectedNames[index]}`}
              style={{ display: 'inline-block', margin: '10px' }}
            />
          ))}
        </div>
      </div>
      <div className={Classes.DIALOG_FOOTER}>
        <div className={Classes.DIALOG_FOOTER_ACTIONS}>
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

// The sendButton component passes eventId to ExportModal
export const SendButton = ({ store, eventId }) => {
  const [modalVisible, setModalVisible] = useState(false);

  return (
    <>
      <Button
        intent="primary"
        onClick={() => setModalVisible(true)}
      >
        Send
      </Button>
      <ExportModal
        store={store}
        isOpen={modalVisible}
        onClose={() => setModalVisible(false)}
        eventId={eventId} // Pass the eventId to ExportModal
      />
    </>
  );
};

// Ensure SendButton is exported like this
export default SendButton;
