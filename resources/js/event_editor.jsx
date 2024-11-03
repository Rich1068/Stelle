import React, { useEffect, useState } from 'react';
import ReactDOM from 'react-dom/client';
import axios from 'axios';
import { observer } from 'mobx-react-lite';
import { InputGroup, Button } from '@blueprintjs/core';
import '@blueprintjs/core/lib/css/blueprint.css';
import { PolotnoContainer, SidePanelWrap, WorkspaceWrap } from 'polotno';
import { Workspace } from 'polotno/canvas/workspace';
import { SidePanel } from 'polotno/side-panel';
import { Toolbar } from 'polotno/toolbar/toolbar';
import { ZoomButtons } from 'polotno/toolbar/zoom-buttons';
import { createStore } from 'polotno/model/store';
import { getImageSize } from 'polotno/utils/image';
import TemplateNameModal from './TemplateNameModal';
import SendButton from './sendButton';
import CustomUploadSection from './UploadSection';


import {
  TextSection,
  ElementsSection,
  BackgroundSection,
  LayersSection,
  SizeSection,
} from 'polotno/side-panel';

import { SectionTab } from 'polotno/side-panel';
import { ImagesGrid } from 'polotno/side-panel/images-grid';
import MdPhotoLibrary from '@meronex/icons/md/MdPhotoLibrary';

// Get the CSRF token from the meta tag
const csrfTokenMetaTag = document.querySelector('meta[name="csrf-token"]');
if (csrfTokenMetaTag) {
  const csrfToken = csrfTokenMetaTag.getAttribute('content');
  // Set the CSRF token in the Axios headers
  axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
} else {
  console.error('CSRF token meta tag not found.');
}

const store = createStore({
  key: 'yLE9pwerTxIv_NjfzWbr',
  showCredit: true,
});

store.addPage();

export const TemplatesPanel = observer(({ store }) => {
  const [templates, setTemplates] = useState([]);

  // Function to load existing templates from backend
  async function loadTemplates() {
    try {
      const response = await axios.get('/certificates/get');
      setTemplates(response.data); // Set the loaded templates

    } catch (error) {
      console.error('Error loading templates:', error);
    }
  }

  useEffect(() => {
    loadTemplates(); // Load templates on mount
  }, []);

  return (
    <div style={{ height: '100%', display: 'flex', flexDirection: 'column' }}>
      <InputGroup
        leftIcon="search"
        placeholder="Search templates..."
        onChange={(e) => {
          loadTemplates(); // Reload templates on search (adjust as needed)
        }}
        style={{
          marginBottom: '20px',
        }}
      />
      <p>Available Templates: </p>
      <ImagesGrid
          images={templates.map((template) => ({
              name: template.name,          
              design: template.design,      
              preview_image: template.path, 
              id: template.id               
          }))}
          getPreview={(template) => `/${template.preview_image}`} // Display the preview image
          onSelect={async (template) => {
              const design = JSON.parse(template.design);
              console.log('Loading template design:', design);  // Log the design JSON
              store.loadJSON(design);  // Load the template's design into Polotno
          }}
          rowsNumber={2}   // Define the number of rows for displaying templates
          isLoading={!templates.length}   // Show loading indicator if templates haven't loaded
          loadMore={false}   // If you have pagination, handle it here, but it's not needed in this case
      />
    </div>
  );
});

// Register this panel as a new custom section in Polotno
const CustomTemplates = {
  name: 'templates',
  Tab: (props) => (
    <SectionTab name="Templates" {...props}>
      <MdPhotoLibrary />
    </SectionTab>
  ),
  Panel: TemplatesPanel,
};

const sections = [
  TextSection,
  CustomTemplates,
  ElementsSection,
  CustomUploadSection,
  BackgroundSection,
  LayersSection,
  SizeSection,
];



const saveDesign = async (eventId, setCertificateId) => {
  const canvasData = store.toJSON();

  try {
    // Convert the current canvas design to a base64 image (returns a Promise)
    const dataURLPromise = store.toDataURL();

    // Await the Promise to get the actual dataURL
    const dataURL = await dataURLPromise;

    console.log('Saving design...', canvasData); // Debug log
    console.log('Image Data URL:', dataURL); // Debug log

    // Check if dataURL is a string
    if (typeof dataURL !== 'string') {
      console.error('dataURL is not a string:', dataURL);
      return;
    }

    const response = await axios.post(`/event/${eventId}/certificates/save`, {
      canvas: canvasData,
      image: dataURL,
    });
    alert('Design saved successfully!');
    console.log(response.data.message);

    if (response.data.certificateId) {
      setCertificateId(response.data.certificateId);
    }
  } catch (error) {
    console.error('Error saving design:', error);
  }
};


const loadDesign = async (eventId, certId) => {
  try {
    const response = await axios.get(`/event/${eventId}/certificates/load/${certId}`);
    console.log('Load response:', response.data);
    store.loadJSON(response.data);
  } catch (error) {
    console.error('Error loading design:', error);
  }
};


export const App = () => {
  // Get the eventId from the DOM
  const [isModalOpen, setIsModalOpen] = useState(false);
  const eventId = document.getElementById('container').getAttribute('data-event-id');
  const [certificateId, setCertificateId] = useState(null);

  useEffect(() => {
    const fetchCertificateId = async () => {
      try {
        const response = await axios.get(`/event/${eventId}/certificates/get-id`);
        const certId = response.data.certificateId;
        console.log('Fetched Certificate ID:', certId);
        if (certId) {
          setCertificateId(certId);
        }
      } catch (error) {
        console.error('Error fetching certificate ID:', error);
      }
    };

    fetchCertificateId();
  }, [eventId]); // eventId dependency

  useEffect(() => {
    if (certificateId) {
      loadDesign(eventId, certificateId);
    }
  }, [certificateId, eventId]);

  const handleSaveAsTemplate = async (templateName) => {
    const canvasData = store.toJSON();
    try {
      const dataURLPromise = store.toDataURL();
      const dataURL = await dataURLPromise;
  
      const response = await axios.post(`/certificate-template/save`, {
        canvas: canvasData,
        image: dataURL,
        template_name: templateName,
        event_id: eventId,           
      });
  
      alert('Template saved successfully!');

    } catch (error) {
      console.error('Error saving template:', error);
    }
  };

  return (
    <PolotnoContainer className="polotno-app-container">
      <SidePanelWrap>
        <SidePanel store={store} sections={sections} defaultSection="photos" />
      </SidePanelWrap>
      <WorkspaceWrap style={{ width: '100%', height: '100%' }}>
        {/* Pass eventId to SendButton via Toolbar */}
        <Toolbar store={store} components={{ ActionControls: (props) => <SendButton {...props} eventId={eventId} /> }} />
        <Workspace store={store} style={{ width: '100%', height: '100%' }} />
        <ZoomButtons store={store} />
        <Button onClick={() => saveDesign(eventId, setCertificateId)} style={{ top: 10, right: -100 }}>
          Save Design
        </Button>
        <Button onClick={() => setIsModalOpen(true)} style={{ top: 10, right: -100 }}>
          Save as Template
        </Button>
      </WorkspaceWrap>
      <TemplateNameModal
        isOpen={isModalOpen}
        onClose={() => setIsModalOpen(false)}
        onSave={(templateName) => handleSaveAsTemplate(templateName)}
      />
    </PolotnoContainer>
  );
};
const root = ReactDOM.createRoot(document.getElementById('container'));
root.render(<App />);
