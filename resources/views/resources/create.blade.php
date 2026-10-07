<x-app-layout>
  <style>
    
    .main-content {
        max-width: 850px;
        margin: 2rem auto;
        padding: 1rem;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
      }
      
      
  </style>
  <main class="main-content">
  <div class="">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          <h1 class="text-2xl font-bold mb-4">Create New Resource</h1>
          <div id="file-upload-section" class="mb-4">
            
          </div>
          <div id="upload-progress-container" class="mt-4 hidden">
            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
              <div id="upload-progress-bar" class="bg-blue-600 h-2.5 rounded-full" style="width: 0%"></div>
            </div>
            <p id="upload-progress-text" class="text-sm font-medium text-gray-900 dark:text-white mt-1">0%</p>
          </div>
          <form id="create-resource-form" class="space-y-4" enctype="multipart/form-data" action="{{ route('resources.store') }}" method="POST">
            @csrf
            <div id="file-upload-section" class="mb-4">
              @if (request()->has('file'))
                <div id="file-card" class="flex justify-between items-center p-4 bg-gray-100 rounded-lg">
                  <div class="flex items-center">
                    <span class="text-2xl mr-2">📄</span>
                    <span>{{ request('file') }}</span>
                  </div>
                  <span class="cursor-pointer" onclick="removeFile()">❌</span>
                </div>
              @else
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:bg-gray-50 transition-colors" id="upload-area">
                  <i class="fas fa-cloud-upload-alt text-4xl text-blue-500 mb-4"></i>
                  <p class="text-gray-600">Click or drag to upload documents <span class="text-red-500">*</span></p>
                  <input type="file" id="file-upload" name="file-upload" class="hidden" onchange="handleFileUpload(this.files)">
                </div>
              @endif
            </div>
            <div class="space-y-2">
              <label for="resource-title" class="block font-medium text-gray-700">
                Title <span class="text-red-500">*</span>
              </label>
              <input type="text" id="resource-title" name="title" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
          
            <div class="space-y-2">
              <label for="resource-description" class="block font-medium text-gray-700">
                Description <span class="text-red-500">*</span>
              </label>
              <input id="resource-description" type="hidden" name="description" required>
              <trix-editor input="resource-description" class="trix-content"></trix-editor>
            </div>
            
            <div class="space-y-2">
              <label for="resource-type" class="block font-medium text-gray-700">
                Resource Type <span class="text-red-500">*</span>
              </label>
              <select id="resource-type" name="type" required disabled class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option value="">Detecting Type..</option>
                <option value="document">Document</option>
                <option value="image">Image</option>
                <option value="video">Video</option>
                <option value="audio">Audio</option>
              </select>
            </div>
            <div class="space-y-2">
              <label for="category-id" class="block font-medium text-gray-700">
                Category <span class="text-red-500">*</span>
              </label>
              <select id="category-id" name="category_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                  <option value="">Select a category</option>
                  @foreach ($categories as $category)
                      <option value="{{ $category->id }}">{{ $category->name }}</option>
                  @endforeach
              </select>
            </div>
            <div class="space-y-2">
              <label for="new-tag" class="block font-medium text-gray-700">Tags (separate multiple tags with commas) <span class="text-red-500">*</span></label>
              <div class="tag-input flex flex-wrap gap-2 p-2 border rounded-md">
                <input type="text" id="new-tag" placeholder="Add tags, e.g. work, important, project" class="flex-grow min-w-[100px] focus:outline-none">
              </div>
            </div>
        
            <div class="space-y-2">
              <div class="flex items-center space-x-4">
                <input type="radio" id="default-image" name="image-source" value="default" checked class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                <label for="default-image" class="font-medium text-gray-700">Default image</label>
                <button type="button" id="browse-icons" class="hidden ml-2 bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-2 rounded text-sm">
                  Browse Icons
                </button>
              </div>
              <div id="default-icon-container" class="hidden mt-4">
                <i id="default-icon" class="text-6xl"></i>
                <input type="hidden" id="default-icon-class" name="default_icon" value="">
              </div>
              <div id="icon-selection-modal" class="fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex items-center justify-center hidden">
                <div class="bg-white rounded-lg shadow-xl w-11/12 md:max-w-md mx-auto">
                  <div class="border-b px-4 py-2 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Select an Icon</h3>
                    <button id="close-icon-modal" class="text-gray-400 hover:text-gray-500">
                      <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                      </svg>
                    </button>
                  </div>
                  <div class="p-4">
                    <div id="icon-grid" class="grid grid-cols-4 sm:grid-cols-6 gap-4"></div>
                  </div>
                </div>
              </div>
              <div class="flex items-center space-x-4">
                <input type="radio" id="ai-generated" name="image-source" value="ai" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                <label for="ai-generated" class="font-medium text-gray-700">AI-generated</label>
              </div>
              <div class="flex items-center space-x-4">
                <input type="radio" id="user-upload" name="image-source" value="upload" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                <label for="user-upload" class="font-medium text-gray-700">Upload image</label>
              </div>
           
              <div class="relative">
                <textarea id="ai-prompt" placeholder="Describe the image you want to generate..." class="hidden w-full p-2 border rounded mt-2 pr-10"></textarea>
                <button type="button" id="generate-prompt" class="hidden absolute right-2 top-2 bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-2 rounded text-sm">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                  </svg>
                </button>
                <button type="button" id="generate-ai-image" class="hidden mt-2 bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded flex items-center justify-center">
                  <span id="generate-button-text">Generate AI Image</span>
                  <svg id="loading-spinner" class="hidden animate-spin ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                </button>
              </div>
              <div id="ai-image-container" class="hidden mt-4">
                <img id="ai-generated-image" src="" alt="AI Generated Image" class="max-w-full h-auto rounded shadow-lg">
              </div>
              <label class="block font-medium text-gray-700">Resource Image</label>
              <div id="resource-image-container" class="hidden">
                <img id="resource-image-preview" src="" alt="Resource Image" class="max-w-[200px]">
                <button type="button" id="remove-resource-image" class="mt-2 bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-2 rounded text-sm">Remove Image</button>
              </div>
              <div id="resource-image-upload">
                <input type="file" id="resource-image" name="resource_image" accept="image/*" class="hidden" onchange="handleResourceImage(this.files)">
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:bg-gray-50 transition-colors" id="resource-image-drop-area">
                  <div class="text-4xl mb-2">📤</div>
                  <p>Drag and drop your image here, or <label for="resource-image" class="text-blue-500 underline cursor-pointer">browse</label></p>
                </div>
              </div>
            </div>
            <div class="flex items-center space-x-2">
              <label for="visibility-toggle" class="font-medium text-gray-700">Visibility:</label>
              <label class="inline-flex items-center cursor-pointer">
                <input type="checkbox" id="visibility-toggle" name="visibility" value="1" class="sr-only peer">
                <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                <span id="visibility-status" class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">Private</span>
              </label>
            </div>
        
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
              Create Resource
            </button>      
          </form>
        </div>
      </div>
    </div>
  </div>
</main>
  



    <script>
      const form = document.getElementById('create-resource-form');
      const aiPrompt = document.getElementById('ai-prompt');
      const fileUpload = document.getElementById('file-upload');
      const imageSourceRadios = document.getElementsByName('image-source');
      const tagInput = document.querySelector('.tag-input');
      const newTagInput = document.getElementById('new-tag');
      const previewImage = document.getElementById('preview-image');
      const uploadCard = document.getElementById('resource-image-drop-area');
      const visibilityToggle = document.getElementById('visibility-toggle');
      const visibilityStatus = document.getElementById('visibility-status');
      
      const uploadedFileContainer = document.getElementById('uploaded-file-container');
      const uploadedFileName = document.getElementById('uploaded-file-name');
      const removeUploadedFile = document.getElementById('remove-uploaded-file');
      const fileUploadContainer = document.getElementById('file-upload-container');
      const uploadBtn = document.getElementById('upload-btn');
const fileUploadSection = document.getElementById('file-upload-section');
const aiGeneratedRadio = document.getElementById('ai-generated');
const resourceImage = document.getElementById('resource-image');
  const resourceImageContainer = document.getElementById('resource-image-container');
  const resourceImagePreview = document.getElementById('resource-image-preview');
  const removeResourceImage = document.getElementById('remove-resource-image');
  const resourceImageUpload = document.getElementById('resource-image-upload');
  const resourceImageDropArea = document.getElementById('resource-image-drop-area');

const generateAiImageBtn = document.getElementById('generate-ai-image');
const aiImageContainer = document.getElementById('ai-image-container');
const aiGeneratedImage = document.getElementById('ai-generated-image');
const loadingSpinner = document.getElementById('loading-spinner');
const generateButtonText = document.getElementById('generate-button-text');
const generatePromptBtn = document.getElementById('generate-prompt');
const titleInput = document.getElementById('resource-title');
const descriptionInput = document.querySelector('trix-editor');
const uploadArea = document.getElementById('upload-area');
const defaultImageRadio = document.getElementById('default-image');
const defaultIconContainer = document.getElementById('default-icon-container');
const defaultIcon = document.getElementById('default-icon');

const browseIconsBtn = document.getElementById('browse-icons');
const iconSelectionModal = document.getElementById('icon-selection-modal');
const iconGrid = document.getElementById('icon-grid');
const closeIconModalBtn = document.getElementById('close-icon-modal');
const iconOptions = [
    // Document icons
    { class: 'far fa-file-alt', color: 'text-gray-600', name: 'Generic Document' },
    { class: 'far fa-file-word', color: 'text-blue-500', name: 'Word Document' },
    { class: 'far fa-file-excel', color: 'text-green-500', name: 'Excel Spreadsheet' },
    { class: 'far fa-file-powerpoint', color: 'text-red-500', name: 'PowerPoint' },
    { class: 'far fa-file-pdf', color: 'text-red-600', name: 'PDF' },
    { class: 'fas fa-file-csv', color: 'text-green-600', name: 'CSV' },
    { class: 'far fa-file-code', color: 'text-yellow-500', name: 'Code File' },
    { class: 'fas fa-file-contract', color: 'text-indigo-600', name: 'Contract' },
    { class: 'fas fa-file-signature', color: 'text-purple-600', name: 'Signed Document' },
    { class: 'fas fa-file-invoice', color: 'text-yellow-600', name: 'Invoice' },
    { class: 'fas fa-file-medical', color: 'text-green-500', name: 'Medical Record' },
    { class: 'fas fa-file-prescription', color: 'text-pink-500', name: 'Prescription' },

    // Image icons
    { class: 'far fa-file-image', color: 'text-green-400', name: 'Image File' },
    { class: 'fas fa-camera', color: 'text-blue-400', name: 'Camera' },
    { class: 'fas fa-image', color: 'text-indigo-400', name: 'Picture' },
    { class: 'fas fa-portrait', color: 'text-purple-400', name: 'Portrait' },
    { class: 'fas fa-photo-video', color: 'text-pink-400', name: 'Photo Album' },
    { class: 'fas fa-camera-retro', color: 'text-yellow-500', name: 'Retro Camera' },
    { class: 'fas fa-images', color: 'text-green-500', name: 'Image Gallery' },

    // Video icons
    { class: 'far fa-file-video', color: 'text-blue-400', name: 'Video File' },
    { class: 'fas fa-film', color: 'text-gray-700', name: 'Film' },
    { class: 'fas fa-video', color: 'text-red-500', name: 'Video Camera' },
    { class: 'fas fa-tv', color: 'text-gray-600', name: 'TV' },
    { class: 'fas fa-play-circle', color: 'text-green-500', name: 'Play Video' },
    { class: 'fas fa-movie', color: 'text-indigo-500', name: 'Movie' },
    { class: 'fas fa-video-slash', color: 'text-red-600', name: 'No Video' },

    // Audio icons
    { class: 'fas fa-music', color: 'text-purple-400', name: 'Music' },
    { class: 'fas fa-microphone', color: 'text-red-400', name: 'Microphone' },
    { class: 'fas fa-podcast', color: 'text-purple-500', name: 'Podcast' },
    { class: 'fas fa-headphones', color: 'text-gray-700', name: 'Headphones' },
    { class: 'fas fa-volume-up', color: 'text-blue-500', name: 'Speaker' },
    { class: 'fas fa-record-vinyl', color: 'text-gray-800', name: 'Vinyl Record' },
    { class: 'fas fa-broadcast-tower', color: 'text-red-500', name: 'Radio' },
    { class: 'fas fa-guitar', color: 'text-yellow-600', name: 'Guitar' },
    { class: 'fas fa-drum', color: 'text-purple-600', name: 'Drum' },

    // Additional icons for various purposes
    { class: 'fas fa-book', color: 'text-blue-600', name: 'Book' },
    { class: 'fas fa-newspaper', color: 'text-gray-700', name: 'Newspaper' },
    { class: 'fas fa-chart-bar', color: 'text-indigo-500', name: 'Chart' },
    { class: 'fas fa-map', color: 'text-green-600', name: 'Map' },
    { class: 'fas fa-calendar', color: 'text-red-400', name: 'Calendar' },
    { class: 'fas fa-envelope', color: 'text-blue-400', name: 'Email' },
    { class: 'fas fa-link', color: 'text-gray-500', name: 'Link' },
    { class: 'fas fa-cloud', color: 'text-blue-300', name: 'Cloud' },
];
const uploadProgressContainer = document.getElementById('upload-progress-container');
const uploadProgressBar = document.getElementById('upload-progress-bar');
const uploadProgressText = document.getElementById('upload-progress-text');

let uploadedFile = null;
    
      // Handle image source selection
      function triggerFileUpload() {
        document.getElementById('file-upload').click();
      }

      function handleFileUpload(files) {
  if (files.length > 0) {
    const file = files[0];
    showFileCard(file.name);
    detectResourceType(file);
    uploadArea.style.display = 'none';
    
    // Add the file to the form data
    const formData = new FormData(form);
    formData.set('file-upload', file);
    
    // Update or create the hidden input for the file name
    let fileNameInput = document.getElementById('uploaded-file');
    if (!fileNameInput) {
      fileNameInput = document.createElement('input');
      fileNameInput.type = 'hidden';
      fileNameInput.id = 'uploaded-file';
      fileNameInput.name = 'uploaded_file';
      form.appendChild(fileNameInput);
    }
    fileNameInput.value = file.name;

    // Clear any previously uploaded resource image
    resourceImagePreview.src = '';
    resourceImageContainer.style.display = 'none';
    uploadCard.style.display = 'block';

    // Handle image/video files
    if (file.type.startsWith('image/') || file.type.startsWith('video/')) {
      document.getElementById('user-upload').checked = true;
      showSelectedImageOption('upload');
      handleResourceImage(file);
    } else {
      // For other file types, reset to default image
      document.getElementById('default-image').checked = true;
      showSelectedImageOption('default');
    }

    // Store the uploaded file for later use
    uploadedFile = file;
  }
}
defaultImageRadio.addEventListener('change', function() {
  if (this.checked) {
    showDefaultIcon();
    browseIconsBtn.style.display = 'inline-block';
  } else {
    defaultIconContainer.style.display = 'none';
    browseIconsBtn.style.display = 'none';
  }
});
browseIconsBtn.addEventListener('click', showIconSelectionModal);

closeIconModalBtn.addEventListener('click', () => {
  iconSelectionModal.classList.add('hidden');
});
function showIconSelectionModal() {
  iconGrid.innerHTML = '';
  iconOptions.forEach(icon => {
    const iconElement = document.createElement('div');
    iconElement.innerHTML = `<i class="${icon.class} ${icon.color} text-4xl cursor-pointer"></i>`;
    iconElement.addEventListener('click', () => selectIcon(icon));
    iconGrid.appendChild(iconElement);
  });
  iconSelectionModal.classList.remove('hidden');
  iconSelectionModal.classList.add('flex');
}
function selectIcon(icon) {
    defaultIcon.className = `${icon.class} ${icon.color} text-6xl`;
    document.getElementById('default-icon-class').value = `${icon.class} ${icon.color}`;
    iconSelectionModal.classList.add('hidden');
  }
function showAIElements(show) {
  const display = show ? 'block' : 'none';
  aiPrompt.style.display = display;
  generateAiImageBtn.style.display = display;
  generatePromptBtn.style.display = display;
}
function showDefaultIcon() {
  const resourceType = document.getElementById('resource-type').value;
  let iconClass = '';
  let fileExtension = '';

  // Check if a file has been uploaded
  const uploadedFileInput = document.getElementById('uploaded-file');
  if (uploadedFileInput) {
    const fileName = uploadedFileInput.value;
    fileExtension = fileName.split('.').pop().toLowerCase();
  }

  switch (resourceType) {
    case 'document':
      switch (fileExtension) {
        case 'doc':
        case 'docx':
          iconClass = 'far fa-file-word text-blue-500';
          break;
        case 'xls':
        case 'xlsx':
          iconClass = 'far fa-file-excel text-green-500';
          break;
        case 'ppt':
        case 'pptx':
          iconClass = 'far fa-file-powerpoint text-red-500';
          break;
        case 'pdf':
          iconClass = 'far fa-file-pdf text-red-600';
          break;
        case 'txt':
          iconClass = 'far fa-file-alt text-gray-600';
          break;
        case 'csv':
          iconClass = 'fas fa-file-csv text-green-600';
          break;
        case 'json':
          iconClass = 'far fa-file-code text-yellow-500';
          break;
        case 'html':
        case 'htm':
          iconClass = 'fab fa-html5 text-orange-500';
          break;
        case 'js':
          iconClass = 'fab fa-js-square text-yellow-400';
          break;
        case 'php':
          iconClass = 'fab fa-php text-purple-500';
          break;
        default:
          iconClass = 'far fa-file text-gray-500';
      }
      break;
    case 'image':
      iconClass = 'far fa-file-image text-green-400';
      break;
    case 'video':
      iconClass = 'far fa-file-video text-blue-400';
      break;
    case 'audio':
      iconClass = 'fas fa-music text-purple-400';
      break;
    default:
      iconClass = 'far fa-file text-gray-500';
  }

  defaultIcon.className = `${iconClass} text-6xl`;
  document.getElementById('default-icon-class').value = iconClass;
  defaultIconContainer.style.display = 'block';
  uploadCard.style.display = 'none';
  aiImageContainer.style.display = 'none';
  browseIconsBtn.style.display = 'inline-block';
}

uploadArea.addEventListener('click', () => {
  fileUpload.click();
});

uploadArea.addEventListener('dragover', (e) => {
  e.preventDefault();
  uploadArea.style.backgroundColor = '#e0e0e0';
});

uploadArea.addEventListener('dragleave', () => {
  uploadArea.style.backgroundColor = '';
});

uploadArea.addEventListener('drop', (e) => {
  e.preventDefault();
  uploadArea.style.backgroundColor = '';
  handleFileUpload(e.dataTransfer.files);
});


generatePromptBtn.addEventListener('click', async function() {
  const title = titleInput.value.trim();
  const description = descriptionInput.innerHTML.trim();

  if (!title && !description) {
    alert('Please enter a title or description before generating a prompt.');
    return;
  }

  generatePromptBtn.disabled = true;
    generatePromptBtn.innerHTML = `
      <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
    `;
  try {
      const response = await fetch('/generate-prompt', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
          title: title,
          description: description
        })
      });

      if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.error || 'Failed to generate prompt');
      }

      const data = await response.json();

      // Debugging: Log the entire response
      console.log('Prompt Generation Response:', data);

      if (data.prompt) {
        const generatedPrompt = data.prompt.trim();
        aiPrompt.value = generatedPrompt;
      } else {
        throw new Error('Prompt not found in the response.');
      }

    } catch (error) {
      console.error('Error generating prompt:', error);
      alert(error.message || 'Failed to generate prompt. Please try again.');
    } finally {
      // Reset button state
      generatePromptBtn.disabled = false;
      generatePromptBtn.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
        </svg>
      `;
    }
  });

  // Ensure the sanitizeHTML function exists
  function sanitizeHTML(html) {
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = html;
    return tempDiv.textContent || tempDiv.innerText || '';
  }
aiGeneratedRadio.addEventListener('change', function() {
  if (this.checked) {
    aiPrompt.style.display = 'block';
    generateAiImageBtn.style.display = 'block';
    generatePromptBtn.style.display = 'block';
    uploadCard.style.display = 'none';
  } else {
    aiPrompt.style.display = 'none';
    generateAiImageBtn.style.display = 'none';
    aiImageContainer.style.display = 'none';
  }
});

function showSelectedImageOption(selectedValue) {
  // Hide all options first
  aiPrompt.style.display = 'none';
  generateAiImageBtn.style.display = 'none';
  generatePromptBtn.style.display = 'none';
  aiImageContainer.style.display = 'none';
  uploadCard.style.display = 'none';
  resourceImageContainer.style.display = 'none';
  defaultIconContainer.style.display = 'none';
  browseIconsBtn.style.display = 'none';

  // Show the selected option
  switch (selectedValue) {
    case 'ai':
      aiPrompt.style.display = 'block';
      generateAiImageBtn.style.display = 'block';
      generatePromptBtn.style.display = 'block';
      aiImageContainer.style.display = 'none'; // Hide any previously generated AI image
      aiGeneratedImage.src = ''; // Clear any previously generated AI image
      break;
    case 'upload':
      if (resourceImageContainer.querySelector('video') || resourceImagePreview.src) {
        resourceImageContainer.style.display = 'block';
      } else {
        uploadCard.style.display = 'block';
      }
      break;
    case 'default':
      showDefaultIcon();
      browseIconsBtn.style.display = 'inline-block';
      break;
  }
}

generateAiImageBtn.addEventListener('click', async function() {
  const prompt = aiPrompt.value.trim();
  if (!prompt) {
    alert('Please enter a prompt for the AI-generated image.');
    return;
  }

  // Show loading state
  loadingSpinner.style.display = 'inline-block';
  generateButtonText.textContent = 'Generating...';
  generateAiImageBtn.disabled = true;
  try {
    const response = await fetch(
      "https://api-inference.huggingface.co/models/black-forest-labs/FLUX.1-schnell",
      {
        headers: { Authorization: "Bearer {{ config('services.hf.key') }}" },
        method: "POST",
        body: JSON.stringify({"inputs": prompt}),
      }
    );

    if (!response.ok) {
      throw new Error('Failed to generate image');
    }

    const result = await response.blob();
    const objectURL = URL.createObjectURL(result);
    aiGeneratedImage.src = objectURL;
    aiImageContainer.style.display = 'block';
    
    // Hide the resource image upload section when AI image is generated
    resourceImageContainer.style.display = 'none';
    uploadCard.style.display = 'none';
  } catch (error) {
    console.error('Error generating AI image:', error);
    alert('Failed to generate AI image. Please try again.');
  } finally {
    // Reset button state
    loadingSpinner.style.display = 'none';
    generateButtonText.textContent = 'Generate AI Image';
    generateAiImageBtn.disabled = false;
  }
});
function detectResourceType(file) {
  const resourceTypeInput = document.getElementById('resource-type');
  const fileExtension = file.name.split('.').pop().toLowerCase();

  // Document file types
  const documentExtensions = ['doc', 'docx', 'pdf', 'txt', 'odt', 'rtf', 'csv', 'xls', 'xlsx', 'ppt', 'pptx','json','html','js','php'];
  if (documentExtensions.includes(fileExtension)) {
    resourceTypeInput.value = 'document';
  }
  // Image file types
  else if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'svg'].includes(fileExtension)) {
    resourceTypeInput.value = 'image';
  }
  // Audio file types
  else if (['mp3', 'wav', 'ogg', 'flac', 'aac', 'webm'].includes(fileExtension)) {
    resourceTypeInput.value = 'audio';
  }
  // Video file types
  else if (['mp4', 'avi', 'mov', 'mkv', 'webm', 'flv', 'mpg', 'mpeg'].includes(fileExtension)) {
    resourceTypeInput.value = 'video';
  }
  else {
    resourceTypeInput.value = '';
    
  }
  if (defaultImageRadio.checked) {
    showDefaultIcon();
  }
  resourceTypeInput.disabled = false;
  
}

      function showFileCard(filename) {
        const fileUploadSection = document.getElementById('file-upload-section');
  fileUploadSection.innerHTML = `
    <div id="file-card" class="file-card">
      <div class="file-info">
        <span class="file-icon">📄</span>
        <span>${filename}</span>
      </div>
      <span class="remove-file" onclick="removeFile()">❌</span>
    </div>
  `;
  document.getElementById('create-resource-form').insertAdjacentHTML('afterbegin', `
    <input type="hidden" id="uploaded-file" name="uploaded_file" value="${filename}">
  `);
      }

      function removeFile() {
  const fileCard = document.getElementById('file-card');
  if (fileCard) fileCard.remove();
  
  const uploadedFileInput = document.getElementById('uploaded-file');
  if (uploadedFileInput) uploadedFileInput.remove();
  
  document.getElementById('upload-area').style.display = 'block';
  document.getElementById('resource-image-container').style.display = 'none';
  resourceImagePreview.src = '';
  uploadCard.style.display = 'block';
  
  // Reset the file input
  const fileInput = document.getElementById('file-upload');
  fileInput.value = '';
  
  // Reset the resource type
  const resourceTypeInput = document.getElementById('resource-type');
  resourceTypeInput.value = '';
  resourceTypeInput.disabled = true;
  
  // If default icon is selected, update it
  if (defaultImageRadio.checked) {
    showDefaultIcon();
  }

  // Clear the stored uploaded file
  uploadedFile = null;
}
imageSourceRadios.forEach(radio => {
  radio.addEventListener('change', (e) => {
    showSelectedImageOption(e.target.value);
  });
});

    
      // Handle tag input
      newTagInput.addEventListener('keydown', function(e) {
  if (e.key === 'Enter') {
    e.preventDefault();
    const value = e.target.value.trim();
    if (value) {
      addTag(value);
      e.target.value = '';
    }
  }
});
newTagInput.addEventListener('input', function(e) {
  const value = e.target.value;
  if (value.includes(',')) {
    const tags = value.split(',');
    tags.forEach(tag => {
      const trimmedTag = tag.trim();
      if (trimmedTag) {
        addTag(trimmedTag);
      }
    });
    e.target.value = '';
  }
});
function addTag(text) {
  const existingTags = Array.from(tagInput.querySelectorAll('.tag')).map(tag => tag.textContent.trim());
  if (!existingTags.includes(text)) {
    const tag = document.createElement('span');
    tag.classList.add('tag');
    tag.textContent = text;
    const removeBtn = document.createElement('span');
    removeBtn.classList.add('tag-remove');
    removeBtn.textContent = '×';
    removeBtn.addEventListener('click', () => tag.remove());
    tag.appendChild(removeBtn);
    tagInput.insertBefore(tag, newTagInput);
  }
}
      // Handle file upload and preview
      // fileUpload.addEventListener('change', handleFileSelect);
      // uploadCard.addEventListener('dragover', handleDragOver);
      // uploadCard.addEventListener('drop', handleDrop);
    
      function handleFileSelect(event) {
        const file = event.target.files[0];
        if (file) {
          displayPreview(file);
        }
      }
    
      function handleDragOver(event) {
        event.preventDefault();
        event.stopPropagation();
        uploadCard.style.borderColor = '#2980b9';
      }
    
      function handleDrop(event) {
        event.preventDefault();
        event.stopPropagation();
        uploadCard.style.borderColor = '#3498db';
        const file = event.dataTransfer.files[0];
        if (file) {
          displayPreview(file);
        }
      }
      document.getElementById('file-upload').addEventListener('change', (e) => {
  if (e.target.files.length > 0) {
    handleFileUpload(e.target.files);
  }
});
      function displayPreview(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          previewImage.src = e.target.result;
          previewImage.style.display = 'block';
        }
        reader.readAsDataURL(file);
      }
    
      // Handle visibility toggle
      visibilityToggle.addEventListener('change', (e) => {
        visibilityStatus.textContent = e.target.checked ? 'Public' : 'Private';
      });
      function safeGetElement(id) {
  return document.getElementById(id);
}


    
  resourceImage.addEventListener('change', (e) => {
    handleResourceImage(e.target.files[0]);
  });

  removeResourceImage.addEventListener('click', () => {
    resourceImageContainer.style.display = 'none';
    resourceImageUpload.style.display = 'block';
    resourceImagePreview.src = '';
    resourceImage.value = '';
    uploadCard.style.display = 'block';
  });

  resourceImageDropArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    resourceImageDropArea.style.backgroundColor = '#e0e0e0';
  });

  resourceImageDropArea.addEventListener('dragleave', () => {
    resourceImageDropArea.style.backgroundColor = '';
  });

  resourceImageDropArea.addEventListener('drop', (e) => {
    e.preventDefault();
    resourceImageDropArea.style.backgroundColor = '';
    handleResourceImage(e.dataTransfer.files[0]);
  });

  function handleResourceImage(file) {
  if (file && (file.type.startsWith('image/') || file.type.startsWith('video/'))) {
    if (file.type.startsWith('video/')) {
      const videoElement = document.createElement('video');
      videoElement.controls = true;
      videoElement.id = "videopreview";
      videoElement.style.maxWidth = '600px';
      videoElement.style.borderRadius = '8px';
      videoElement.style.boxShadow = '0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24)';
      videoElement.src = URL.createObjectURL(file);
      resourceImageContainer.innerHTML = '';
      resourceImageContainer.appendChild(videoElement);
      resourceImageContainer.style.display = 'block';
      uploadCard.style.display = 'none';
    } else {
      const reader = new FileReader();
      reader.onload = (e) => {
        resourceImagePreview.src = e.target.result;
        resourceImagePreview.style.display = 'block';
        resourceImageContainer.style.display = 'block';
        uploadCard.style.display = 'none';
      };
      reader.readAsDataURL(file);
    }
    
    // Clear any previously generated AI image
    aiGeneratedImage.src = '';
    aiImageContainer.style.display = 'none';
  } else {
    console.log('File type not supported for preview');
    uploadCard.style.display = 'block';
    resourceImageContainer.style.display = 'none';
  }
}
//   document.addEventListener("trix-initialize", function(event) {
//   var trixEditor = event.target;
//   trixEditor.toolbarElement.querySelector(".trix-button-group--file-tools").remove();
// });
document.addEventListener("trix-initialize", function(event) {
  var trixEditor = event.target;
  var toolbarElement = trixEditor.toolbarElement;

  var fontSizeSelect = document.createElement("select");
  fontSizeSelect.classList.add("trix-button", "trix-button--icon", "trix-button--icon-increase-nesting-level");
  fontSizeSelect.innerHTML = `
    <option value="small">Small</option>
    <option value="normal" selected>Normal</option>
    <option value="large">Large</option>
    <option value="huge">Huge</option>
  `;
  fontSizeSelect.addEventListener("change", function(event) {
    trixEditor.insertHTML('<span style="font-size: ' + event.target.value + ';">' + trixEditor.getSelectedRange() + '</span>');
  });

  // Font color
  var fontColorInput = document.createElement("input");
  fontColorInput.setAttribute("type", "color");
  fontColorInput.classList.add("trix-button", "trix-button--icon", "trix-button--icon-color");
  fontColorInput.addEventListener("change", function(event) {
    trixEditor.insertHTML('<span style="color: ' + event.target.value + ';">' + trixEditor.getSelectedRange() + '</span>');
  });

  // Create new button groups
  var fontGroup = document.createElement("div");
  fontGroup.classList.add("trix-button-group");
  fontGroup.appendChild(fontSizeSelect);
  fontGroup.appendChild(fontColorInput);

  // Insert the new button group after the text tools group
  var textToolsGroup = toolbarElement.querySelector(".trix-button-group--text-tools");
  textToolsGroup.parentNode.insertBefore(fontGroup, textToolsGroup.nextSibling);
  console.log("Trix editor initialized");
});

document.addEventListener("trix-change", function(event) {
  console.log("Trix content changed:", event.target.innerHTML);
});
      // Handle form submission



      form.addEventListener('submit', function(e) {
          e.preventDefault();

          const trixEditor = document.querySelector('trix-editor');
          const trixContent = trixEditor.innerHTML;
          console.log('Trix content being sent:', trixContent);
          const tagInput = document.querySelector('.tag-input');
          const tags = Array.from(tagInput.querySelectorAll('.tag')).map(tag => tag.textContent.trim());
          const selectedImageSource = document.querySelector('input[name="image-source"]:checked').value;

          // Create FormData object from the form
          const formData = new FormData(form);
          
          // Set the description in the FormData
          formData.set('description', trixContent);
          formData.append('tags', JSON.stringify(tags));
          if (uploadedFile) {
    formData.set('file-upload', uploadedFile);
  }
          // Only include default_icon if the image source is 'default'
          if (selectedImageSource === 'default') {
            const defaultIconClass = document.getElementById('default-icon-class').value;
            formData.append('default_icon', defaultIconClass);
          } else {
            formData.delete('default_icon');
          }

          if (selectedImageSource === 'ai' && aiGeneratedImage.src) {
            fetch(aiGeneratedImage.src)
              .then(res => res.blob())
              .then(blob => {
                formData.append('resource_image', blob, 'ai_generated_image.png');
                submitForm(formData);
              });
          } else if (selectedImageSource === 'upload') {
            // The uploaded image should already be in the formData
            submitForm(formData);
          } else {
            // Default image or no image selected
            submitForm(formData);
          }
        });
function submitForm(formData) {
  fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            alert(data.message);
            // Redirect to dashboard
            window.location.href = data.redirect;
        } else {
            // Show error message
            alert(data.message);
        }
    })
  .catch(error => {
    console.error('Error:', error);
  });
}
window.addEventListener('load', () => {
  const defaultSelected = document.querySelector('input[name="image-source"]:checked');
  if (defaultSelected) {
    showSelectedImageOption(defaultSelected.value);
  }
});
    </script>
    </body></html>
</x-app-layout>
