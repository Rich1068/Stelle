import React from 'react';
import ReactDOM from 'react-dom/client';
import { observer } from 'mobx-react-lite';
import { InputGroup } from '@blueprintjs/core';
import '@blueprintjs/core/lib/css/blueprint.css';
import { PolotnoContainer, SidePanelWrap, WorkspaceWrap } from 'polotno';
import { Workspace } from 'polotno/canvas/workspace';
import { SidePanel } from 'polotno/side-panel';
import { Toolbar } from 'polotno/toolbar/toolbar';
import { PagesTimeline } from 'polotno/pages-timeline';
import { ZoomButtons } from 'polotno/toolbar/zoom-buttons';
import { createStore } from 'polotno/model/store';
import { getImageSize } from 'polotno/utils/image';

// import all default sections
// we will not use all of them in the demo
// just to show what we have
import {
  TextSection,
  ElementsSection,
  UploadSection,
  BackgroundSection,
  SizeSection,
} from 'polotno/side-panel';
//
import { SectionTab } from 'polotno/side-panel';
import { ImagesGrid } from 'polotno/side-panel/images-grid';
// import our own icon
import MdPhotoLibrary from '@meronex/icons/md/MdPhotoLibrary';

const store = createStore({
  key: 'yLE9pwerTxIv_NjfzWbr',
  showCredit: true,
});

store.addPage();
store.activePage.addElement({
  type: 'text',
  text: 'hello',
});
// store.activePage.setSize({
//     width: 1000,
//     height: 1000,
//     useMagic: true,
//   });

export const PhotosPanel = observer(({ store }) => {
  const [images, setImages] = React.useState([]);

  async function loadImages() {
    // here we should implement your own API requests
    setImages([]);
    await new Promise((resolve) => setTimeout(resolve, 3000));

    // for demo images are hard coded
    // in real app here will be something like JSON structure
    setImages([
      { url: '/storage/images/certificates/cert_templates/template1.jpg' },
      //{ url: './guillaume-de-germain-TQWJ4rQnUHQ-unsplash.jpg' },
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
      {/* you can create yur own custom component here */}
      {/* but we will use built-in grid component */}
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
            // if position is available, show image on dropped place
            // or just show it in the center
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

// define the new custom section
const CustomPhotos = {
  name: 'photos',
  Tab: (props) => (
    <SectionTab name="Photos" {...props}>
      <MdPhotoLibrary />
    </SectionTab>
  ),
  // we need observer to update component automatically on any store changes
  Panel: PhotosPanel,
};

// we will have just two sections
const sections = [
  TextSection,
  CustomPhotos,
  ElementsSection,
  UploadSection,
  BackgroundSection,
  SizeSection,
  // we will replace default resize with our own
];

export const App = () => {
  return (
    <PolotnoContainer className="polotno-app-container">
      <SidePanelWrap>
        <SidePanel store={store} sections={sections} defaultSection="photos" />
      </SidePanelWrap>
      <WorkspaceWrap style={{ width: '100%', height: '100%'}}>
        <Toolbar store={store} downloadButtonEnabled />
        <Workspace store={store} style={{ width: '100%', height: '100%' }}/>
        <ZoomButtons store={store} />
        <PagesTimeline store={store} />
      </WorkspaceWrap>
    </PolotnoContainer>
  );
};

const root = ReactDOM.createRoot(document.getElementById('container'));
root.render(<App />);
