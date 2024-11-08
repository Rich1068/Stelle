import React, { useEffect, useState } from 'react';
import { Button, Classes, Dialog, Checkbox } from '@blueprintjs/core';
import axios from 'axios';

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
      setNames(response.data); // Assume data is an array of names
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
      alert('Please select at least one user before generating the preview.');
      return;
    }
  
    setLoading(true);
    const originalJson = JSON.parse(JSON.stringify(store.toJSON())); // Deep clone the JSON
  
    // Step 1: Prepare all modified JSON copies with name replacements
    const modifiedJsons = selectedNames.map(name => {
      const jsonCopy = JSON.parse(JSON.stringify(originalJson)); // Clone JSON for each name
      jsonCopy.pages.forEach(page => {
        page.children.forEach(element => {
          if (element.type === 'text' && element.text.includes('{{name}}')) {
            element.text = element.text.replace('{{name}}', name); // Replace placeholder
          }
        });
      });
      return jsonCopy;
    });
  
    const newImages = [];
  
    try {
      // Step 2: Load each modified JSON only once, then capture the image
      for (const json of modifiedJsons) {
        await store.loadJSON(json); // Load modified JSON into the canvas
        await store.waitLoading();  // Wait for the canvas to finish loading
        const dataURL = await store.toDataURL(); // Capture canvas as image
        newImages.push(dataURL); // Add the image URL to the array
      }
  
      setImages(newImages); // Set all generated images at once
  
    } catch (error) {
      alert('Something went wrong while generating previews.');
      console.error('Error in preview generation:', error);
    } finally {
      setLoading(false);
      await store.loadJSON(originalJson); // Restore original JSON after previews
      await store.waitLoading();
    }
  };

  const handleGenerateAndSave = async () => {
    if (selectedNames.length === 0) {
        alert('Please select at least one user before sending the certificate.');
        return;
    }

    setLoading(true);
    showLoadingModal(true);

    const originalJson = JSON.parse(JSON.stringify(store.toJSON())); // Clone the JSON once
    const certificateData = []; // Array to store data for batch saving

    try {
        // Generate all modified JSONs and data URLs in one batch
        for (const name of selectedNames) {
            // Create a modified copy of the original JSON for each name
            const modifiedJson = JSON.parse(JSON.stringify(originalJson));
            modifiedJson.pages.forEach((page) => {
                page.children = page.children.map((element) => {
                    if (element.type === 'text' && element.text.includes('{{name}}')) {
                        return { ...element, text: element.text.replace('{{name}}', name) };
                    }
                    return element;
                });
            });

            await store.loadJSON(modifiedJson);
            await store.waitLoading();

            // Generate data URL for the modified JSON
            const dataURL = await store.toDataURL();
            const userId = getUserIdByName(name);

            // Push certificate data for batch saving
            certificateData.push({ userId, imageData: dataURL });
        }

        // Send all certificates in a single request
        const response = await axios.post(`/event/${eventId}/participants/send-certificates`, {
            data: certificateData,
        });

        setLoading(false);
        showLoadingModal(false);

        if (response.data && response.data.message === 'Certificates sent successfully!') {
            setTimeout(() => {
                alert('Certificates saved successfully!');
            }, 300);
        }
    } catch (error) {
        console.error('Error generating and saving certificates:', error);
    } finally {
        await store.loadJSON(originalJson); // Reset to the original JSON after completion
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
              label={name.full_name}
              checked={selectedNames.includes(name.full_name)}
              onChange={() => handleCheckboxChange(name.full_name)}
            />
          ))}
        </div>
        
        {/* Pagination Controls */}
        <div style={{ display: 'flex', justifyContent: 'center', marginBottom: '10px' }}>
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
                width: '100%',
                maxWidth: '600px',
                height: 'auto',
                boxShadow: '0px 4px 8px rgba(0, 0, 0, 0.2)',
              }}
            />
          ))}
        </div>
      </div>
  
      <div className={Classes.DIALOG_FOOTER} style={{ padding: '0.5rem' }}>
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
