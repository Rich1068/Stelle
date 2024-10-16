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
import { PagesTimeline } from 'polotno/pages-timeline';
import { ZoomButtons } from 'polotno/toolbar/zoom-buttons';
import { createStore } from 'polotno/model/store';
import { getImageSize } from 'polotno/utils/image';
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
            getPreview={(template) => {
              console.log(`Template Preview Path: /${template.preview_image}`);
              return `/${template.preview_image}`;
            }} // Display the preview image
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


const sections = [TextSection, CustomTemplates, ElementsSection, CustomUploadSection, BackgroundSection, LayersSection, SizeSection];

const saveDesign = async (setCertificateId, certificateId) => {
    const canvasData = store.toJSON();
    const certName = document.getElementById('certName').value; // Get cert name from the input field
  
    if (!certName) {
      alert('Please enter a certificate name.');
      return;
    }
  
    try {
      const dataURLPromise = store.toDataURL();
      const dataURL = await dataURLPromise;
  
      console.log('Saving design...', canvasData); // Debug log
      console.log('Image Data URL:', dataURL); // Debug log
  
      if (typeof dataURL !== 'string') {
        console.error('dataURL is not a string:', dataURL);
        return;
      }
  
      // Always use the same save route for both creating and updating
      const response = await axios.post(`/certificates/save`, {
        canvas: canvasData,
        image: dataURL,
        cert_name: certName, // Include the certificate name in the request
        certificate_id: certificateId, // Send the certificate ID if it's an update
      });
  
      alert('Design saved successfully!');
      console.log(response.data.message);
  
      // For new certificate, set certificateId after creation
      if (!certificateId && response.data.certificateId) {
        setCertificateId(response.data.certificateId); // Update the certificate ID state for further operations
      }
    } catch (error) {
      console.error('Error saving design:', error);
    }
  };

const loadDesign = async (certificateId) => {
    try {
      const response = await axios.get(`/certificates/${certificateId}/load`);
      console.log('Load response:', response.data);  // Log the response to check the data
      if (response.data) {
        store.loadJSON(response.data);  // Ensure that the response is a valid JSON Polotno can use
      }
    } catch (error) {
      console.error('Error loading design:', error);
    }
  };

export const App = () => {
  const certificateId = document.getElementById('container').getAttribute('data-certificate-id');
  const [certId, setCertificateId] = useState(certificateId);

  useEffect(() => {
    if (certId) {
      loadDesign(certId);
    }
  }, [certId]);

  return (
    <PolotnoContainer className="polotno-app-container">
      <SidePanelWrap>
        <SidePanel store={store} sections={sections} defaultSection="photos" />
      </SidePanelWrap>
      <WorkspaceWrap style={{ width: '100%', height: '100%' }}>
        <Toolbar store={store} />
        <Workspace store={store} style={{ width: '100%', height: '100%' }} />
        <ZoomButtons store={store} />
        <PagesTimeline store={store} />
        <Button onClick={() => saveDesign(setCertificateId, certId)} style={{ top: 10, right: -100 }}>
          Save Design
        </Button>
      </WorkspaceWrap>
    </PolotnoContainer>
  );
};

const root = ReactDOM.createRoot(document.getElementById('container'));
root.render(<App />);
