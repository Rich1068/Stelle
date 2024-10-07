import React, { useState } from 'react';
import { observer } from 'mobx-react-lite';
import { SectionTab } from 'polotno/side-panel';
import { ImagesGrid } from 'polotno/side-panel/images-grid';
import MdPhotoLibrary from '@meronex/icons/md/MdPhotoLibrary';
import { getImageSize } from 'polotno/utils/image';  // Import getImageSize utility

const UploadSection = observer(({ store }) => {
  const [uploadedImages, setUploadedImages] = useState([]);
  const [errorMessage, setErrorMessage] = useState(null);

  const handleFileChange = async (e) => {
    const file = e.target.files[0];

    if (!file) return;

    // Restrict file size to 1MB
    if (file.size > 1048576) { // 1MB in bytes
      setErrorMessage('File size exceeds 1MB. Please upload a smaller file.');
      return;
    }

    setErrorMessage(null);  // Clear previous error

    // Read file as a base64 string
    const reader = new FileReader();
    reader.onload = async function (event) {
      const base64 = event.target.result;

      // Use getImageSize to get the dimensions of the image
      const { width, height } = await getImageSize(base64);

      // Add image to the active page in Polotno with its original size
      store.activePage.addElement({
        type: 'image',
        src: base64,
        width: width,   // Retain original width
        height: height, // Retain original height
      });

      // Add the image to the preview list
      setUploadedImages([...uploadedImages, base64]);
    };

    reader.readAsDataURL(file);  // Read file as base64
  };

  return (
    <div style={{ height: '100%', display: 'flex', flexDirection: 'column' }}>
      <input type="file" accept="image/*" onChange={handleFileChange} />
      {errorMessage && <p style={{ color: 'red' }}>{errorMessage}</p>}

      {uploadedImages.length > 0 && (
        <ImagesGrid
          images={uploadedImages.map((image, index) => ({
            id: index,
            preview_image: image,
          }))}
          getPreview={(image) => image.preview_image}
          onSelect={async (image) => {
            const { width, height } = await getImageSize(image.preview_image);
            store.activePage.addElement({
              type: 'image',
              src: image.preview_image,
              width: width,
              height: height,
            });
          }}
          rowsNumber={2}
        />
      )}
    </div>
  );
});

// Register Upload Section as a custom section
const CustomUploadSection = {
  name: 'uploads',
  Tab: (props) => (
    <SectionTab name="Upload" {...props}>
      <MdPhotoLibrary />
    </SectionTab>
  ),
  Panel: UploadSection,
};

export default CustomUploadSection;
