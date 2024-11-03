import React, { useEffect, useState } from 'react';
import { Button, Classes, Dialog, Checkbox } from '@blueprintjs/core';
import axios from 'axios';

// Export Modal for Certificate Selection and Preview
const ExportModal = ({ isOpen, store, onClose, eventId, showLoadingModal }) => {
  const [loading, setLoading] = useState(false);
  const [names, setNames] = useState([]); // To store names fetched from the database
  const [selectedNames, setSelectedNames] = useState([]); // To store selected names for image generation
  const [selectAll, setSelectAll] = useState(false);
  const [images, setImages] = useState([]);

  // Fetch event participants
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
  }, [eventId]);

  const handleCheckboxChange = (name) => {
    setSelectedNames((prevSelected) => {
      const updatedSelected = prevSelected.includes(name)
        ? prevSelected.filter((n) => n !== name)
        : [...prevSelected, name];

      setSelectAll(updatedSelected.length === names.length); // Update "Select All" state
      return updatedSelected;
    });
  };
  const handleSelectAllChange = () => {
    setSelectAll(!selectAll);
    setSelectedNames(!selectAll ? names.map(n => n.full_name) : []);
  };


  const replaceNameInStore = (json, name) => {
    const clonedJson = JSON.parse(JSON.stringify(json)); // Clone the JSON to make it mutable

    clonedJson.pages.forEach((page) => {
      page.children.forEach((element) => {
        if (element.type === 'text' && element.text.includes('{{name}}')) {
          element.text = element.text.replace('{{name}}', name); // Replace {{name}} placeholder
        }
      });
    });
    return clonedJson;
  };

  // Generate previews
  const handleGenerate = async () => {
    if (selectedNames.length === 0) {
      alert('Please select at least one user before generating the preview.');
      return;
    }

    setLoading(true); // Start loading state
    const json = store.toJSON(); // Get the original design as JSON

    const newImages = [];

    try {
      for (let name of selectedNames) {
        const modifiedJson = replaceNameInStore(json, name);
        await store.loadJSON(modifiedJson);
        await store.waitLoading();
        const dataURL = await store.toDataURL();
        newImages.push(dataURL);
      }
      setImages(newImages);
    } catch (e) {
      alert('Something went wrong');
      console.error(e);
    } finally {
      setLoading(false);
      await store.loadJSON(json);
      await store.waitLoading();
    }
  };

  // Generate and send certificates
  const handleGenerateAndSave = async () => {
    if (selectedNames.length === 0) {
      alert('Please select at least one user before sending the certificate.');
      return;
    }

    setLoading(true);
    showLoadingModal(true); // Show the loading modal when "Send" is clicked

    const json = store.toJSON();
    const certificateData = [];

    try {
      for (let name of selectedNames) {
        const modifiedJson = replaceNameInStore(json, name);
        await store.loadJSON(modifiedJson);
        await store.waitLoading();
        const dataURL = await store.toDataURL();
        const userId = getUserIdByName(name);
        certificateData.push({
          userId,
          imageData: dataURL,
        });
      }

      const response = await axios.post(`/event/${eventId}/participants/send-certificates`, {
        data: certificateData,
      });
      setLoading(false);
      showLoadingModal(false); // Hide the loading modal when done
      if (response.data && response.data.message === 'Certificates sent successfully!') {
        setTimeout(() => {
          alert('Certificates saved successfully!');
      }, 300);
      } 
    } catch (error) {
      console.error('Error generating and saving certificates:', error);
    } finally {
      await store.loadJSON(json);
      await store.waitLoading();
    }
  };

  const getUserIdByName = (name) => {
    const user = names.find(n => n.full_name === name);
    return user ? user.user_id : null;
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
        <Checkbox
          label="Select All"
          checked={selectAll}
          onChange={handleSelectAllChange}
          style={{ marginBottom: '10px' }}
        />
        <div style={{ marginBottom: '20px' }}>
          {names.map((name, index) => (
            <Checkbox
              key={index}
              label={name.full_name}
              checked={selectedNames.includes(name.full_name)}
              onChange={() => handleCheckboxChange(name.full_name)}
            />
          ))}
        </div>

        <h5>Generated Previews:</h5>
        <div style={{ display: 'flex', overflowX: 'auto', marginBottom: '20px' }}>
          {images.map((url, index) => (
            <img
              src={url}
              key={index}
              alt={`Preview for ${selectedNames[index]}`}
              style={{
                display: 'inline-block',
                margin: '10px',
                width: window.innerWidth > 768 ? '600px' : '80%', // Adjust width for mobile
                height: 'auto',
                boxShadow: '0px 4px 8px rgba(0, 0, 0, 0.2)',
              }}
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

// Loading Modal Component
const LoadingModal = ({ isOpen }) => {
  return (
    <Dialog
      icon="cloud-upload"
      title="Processing"
      isOpen={isOpen}
      style={{ width: '400px', zIndex: 1500}}
    >
      <div className={Classes.DIALOG_BODY}>
        <p>Your certificates are being generated and sent. Please wait...</p>
      </div>
    </Dialog>
  );
};

// SendButton Component with Loading Modal
export const SendButton = ({ store, eventId }) => {
  const [modalVisible, setModalVisible] = useState(false);
  const [loadingModalVisible, setLoadingModalVisible] = useState(false);

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
        eventId={eventId}
        showLoadingModal={setLoadingModalVisible} // Pass loading modal controller
      />
      <LoadingModal isOpen={loadingModalVisible} />
    </>
  );
};

export default SendButton;
