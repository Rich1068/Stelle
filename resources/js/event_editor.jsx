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
import SendButton from './sendButton';

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

export const PhotosPanel = observer(({ store }) => {
  const [images, setImages] = React.useState([]);

  async function loadImages() {
    setImages([]);
    await new Promise((resolve) => setTimeout(resolve, 3000));

    setImages([
      { url: '/storage/images/certificates/cert_templates/template1.jpg' },
    ]);
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
        style={{
          marginBottom: '20px',
        }}
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

const sections = [
  TextSection,
  CustomPhotos,
  ElementsSection,
  UploadSection,
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
        <PagesTimeline store={store} />
        <Button onClick={() => saveDesign(eventId, setCertificateId)} style={{ top: 10, right: -100 }}>
          Save Design
        </Button>
      </WorkspaceWrap>
    </PolotnoContainer>
  );
};
const root = ReactDOM.createRoot(document.getElementById('container'));
root.render(<App />);
