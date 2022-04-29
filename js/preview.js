const fileInput = document.querySelector('.adding-post__input-file');
const previewBlock = document.querySelector('.preview__photo');
const FILE_TYPES = ['jpg', 'jpeg', 'png', 'jpg', 'gif'];

const makePhotoPreview = (input, previewBlock) => {
  const inputFile = input.files[0];
  const inputFileName = inputFile.name.toLowerCase();

  const matches = FILE_TYPES.some((it) => inputFileName.endsWith(it));

  if (matches) {
    previewBlock.src = URL.createObjectURL(inputFile);
  } else {
    previewBlock.src = 'img/drag-and-drop.svg';
  }

};

fileInput.addEventListener('change', () => makePhotoPreview(fileInput, previewBlock));
