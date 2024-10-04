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

import {
  TextSection,
  ElementsSection,
  UploadSection,
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

export const PhotosPanel = observer(({ store }) => {
  const [images, setImages] = React.useState([]);

  async function loadImages() {
    setImages([]);
    await new Promise((resolve) => setTimeout(resolve, 3000));

    setImages([{ url: '/storage/images/certificates/cert_templates/template1.jpg' }]);
  }

  React.useEffect(() => {
    loadImages();
  }, []);

  return (
    <div style={{ height: '100%', display: 'flex', flexDirection: 'column' }}>
      <InputGroup
        leftIcon="search"
        placeholder="Search..."
        onChange={(e) => {
          loadImages();
        }}
        style={{ marginBottom: '20px' }}
      />
      <p>Demo images: </p>
      <ImagesGrid
        images={images}
        getPreview={(image) => image.url}
        onSelect={async (image, pos) => {
          const { width, height } = await getImageSize(image.url);
          store.activePage.addElement({
            type: 'image',
            src: image.url,
            width,
            height,
            x: pos ? pos.x : store.width / 2 - width / 2,
            y: pos ? pos.y : store.height / 2 - height / 2,
          });
        }}
        rowsNumber={2}
        isLoading={!images.length}
        loadMore={false}
      />
    </div>
  );
});

const CustomPhotos = {
  name: 'photos',
  Tab: (props) => (
    <SectionTab name="Borders" {...props}>
      <MdPhotoLibrary />
    </SectionTab>
  ),
  Panel: PhotosPanel,
};

const sections = [TextSection, CustomPhotos, ElementsSection, UploadSection, BackgroundSection, LayersSection, SizeSection];

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
