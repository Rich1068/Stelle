import React, { useState } from 'react';
import { Button, Dialog, Classes } from '@blueprintjs/core';

// Modal for entering template name
const TemplateNameModal = ({ isOpen, onClose, onSave }) => {
  const [templateName, setTemplateName] = useState('');

  const handleSaveClick = () => {
    if (templateName.trim() === '') {
      alert('Please enter a template name.');
      return;
    }
    onSave(templateName); // Pass the template name to the save function
    onClose(); // Close the modal after saving
  };

  return (
    <Dialog
      icon="info-sign"
      onClose={onClose}
      title="Save as Template"
      isOpen={isOpen}
      style={{ width: '400px' }}
    >
      <div className={Classes.DIALOG_BODY}>
        <h5>Enter a Template Name:</h5>
        <input
          type="text"
          className="bp3-input"
          placeholder="Template Name"
          value={templateName}
          onChange={(e) => setTemplateName(e.target.value)}
          style={{ width: '100%', marginBottom: '15px' }}
        />
      </div>
      <div className={Classes.DIALOG_FOOTER}>
        <div className={Classes.DIALOG_FOOTER_ACTIONS}>
          <Button onClick={onClose}>Cancel</Button>
          <Button intent="primary" onClick={handleSaveClick}>
            Save Template
          </Button>
        </div>
      </div>
    </Dialog>
  );
};

export default TemplateNameModal;
