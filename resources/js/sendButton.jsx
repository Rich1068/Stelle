import React, { useEffect, useState } from 'react';
import { Button, Classes, Dialog, Checkbox } from '@blueprintjs/core';
import axios from 'axios';

const ExportModal = ({ isOpen, store, onClose, eventId }) => {
  const [loading, setLoading] = useState(false);
  const [names, setNames] = useState([]); // To store names fetched from the database
  const [selectedNames, setSelectedNames] = useState([]); // To store selected names for image generation
  const [images, setImages] = useState([]);
  const [originalJson, setOriginalJson] = useState(null); // State to store the original JSON

  // Function to fetch names (event participants) from the database using eventId
  const fetchNames = async () => {
    try {
      // Make sure eventId is passed into the API request
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
        // If already selected, remove from the list
        return prevSelected.filter((n) => n !== name);
      } else {
        // Add the name to the selected list
        return [...prevSelected, name];
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
    setLoading(true); // Start loading state
    const json = store.toJSON(); // Get the original design as JSON

    if (!originalJson) {
      setOriginalJson(json); // Save the original JSON to revert back to later
    }

    const newImages = []; // Array to store generated images

    try {
      for (let name of selectedNames) {
        // Clone and modify the store's JSON
        const modifiedJson = replaceNameInStore(json, name);

        // Load the modified JSON into the store
        store.loadJSON(modifiedJson);

        // Wait for the store to finish loading changes (if necessary)
        await store.waitLoading();

        // Render the current canvas to a data URL (an image)
        const dataURL = await store.toDataURL();

        // Add the generated image to the list
        newImages.push(dataURL);
      }

      // Set the generated images in the state for display
      setImages(newImages);
      setLoading(false); // Stop loading state

      // Revert back to the original JSON
      if (originalJson) {
        store.loadJSON(originalJson);
        await store.waitLoading();
      }
    } catch (e) {
      alert('Something went wrong');
      console.error(e);
      setLoading(false); // Stop loading in case of error
    }
  };

  return (
    <Dialog
      icon="info-sign"
      onClose={onClose}
      title="Generate Previews"
      isOpen={isOpen}
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
            Generate
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
          Preview
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
