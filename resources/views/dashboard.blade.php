<x-app-layout>
    
  <!-- Personal Resources -->
  <html><head>
      <title>TrackSave - Document Resources</title>
      <style>
      * {
          margin: 0;
          padding: 0;
          box-sizing: border-box;
          font-family: Arial, sans-serif;}
body {
          background-color: #f0f2f5;
          color: #333;}
.user-info {
          color: white;}
.create-resource {
          background-color: #3498db;
          color: white;
          border: none;
          padding: 0.5rem 1rem;
          border-radius: 5px;
          cursor: pointer;}
.main-content {
          padding: 2rem;}
h1 {
          font-size: 2rem;
          margin-bottom: 1.5rem;
          color: #2c3e50;}
.filter-section {
          display: flex;
          gap: 1rem;
          margin-bottom: 2rem;
          align-items: center;
          flex-wrap: wrap;}
.document-icon.custom-image {
      overflow: hidden;}
.document-icon.custom-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;}
.filter-section select {
          padding: 0.5rem;
          border-radius: 5px;
          border: 1px solid #ddd;}
.search-container {
          position: relative;
          flex-grow: 1;}
.search-container input {
          width: 100%;
          padding: 0.5rem;
          padding-right: 2.5rem;
          border-radius: 5px;
          border: 1px solid #ddd;}
.search-icon {
          position: absolute;
          right: 10px;
          top: 50%;
          transform: translateY(-50%);
          color: #7f8c8d;
          cursor: pointer;}
.filter-button {
          background-color: #3498db;
          color: white;
          border: none;
          padding: 0.5rem 1rem;
          border-radius: 5px;
          cursor: pointer;}
.resources-grid {
          display: grid;
          grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
          gap: 2rem;
          align-items: start; /* Align items to the start of the grid cell */}
.resource-card {
          background-color: white;
          border-radius: 8px;
          overflow: hidden;
          box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
          transition: all 0.3s cubic-bezier(.25,.8,.25,1);
          display: flex;
          flex-direction: column;
          min-height: 300px; /* Set a minimum height for the cards */}
.resource-card:hover {
          box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);}
.document-icon {
          height: 100px;
          display: flex;
          justify-content: center;
          align-items: center;
          font-size: 3rem;
          color: white;}
.pdf { background-color: #e74c3c;}
.doc { background-color: #3498db;}
.xls { background-color: #2ecc71;}
.ppt { background-color: #e67e22;}
.txt { background-color: #95a5a6;}

.resource-content {
          padding: 1rem;
          display: flex;
          flex-direction: column;
          flex-grow: 1;}
.resource-title {
          font-size: 1.2rem;
          font-weight: bold;
          margin-bottom: 0.5rem;
          height: 1.5em; /* Adjust this value as needed */
          line-height: 1.5em;
          overflow: hidden;
          whitespace-nowrap: nowrap;
          text-overflow: ellipsis;
          width: 100%;
          max-width: 100%;
          display: inline-block;}
.resource-description {
          font-size: 0.9rem;
          color: #7f8c8d;
          margin-bottom: 0.5rem;
          flex-grow: 1;
          height: 1.5em; /* Adjust this value as needed */
          line-height: 1.5em;
          overflow: hidden;
          whitespace-nowrap: nowrap;
          text-overflow: ellipsis;
          width: 100%;
          max-width: 100%;
          display: inline-block;}
.resource-tags {
          display: flex;
          flex-wrap: wrap;
          gap: 0.5rem;
          margin-top: auto; /* Push tags to the bottom of the content area */}
.tag {
          background-color: #ecf0f1;
          padding: 0.2rem 0.5rem;
          border-radius: 3px;
          font-size: 0.8rem;
          color: #7f8c8d;}
.upload-card {
          border: 2px dashed #3498db;
          border-radius: 8px;
          padding: 2rem;
          text-align: center;
          cursor: pointer;
          display: flex;
          flex-direction: column;
          justify-content: center;
          align-items: center;
          align-self: stretch; /* This allows the card to stretch to fill its grid cell */}
.view-tabs {
      display: flex;
      gap: 1rem;
      margin-bottom: 1.5rem;}
.view-tab {
      background-color: #ecf0f1;
      border: none;
      padding: 0.5rem 1rem;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;}
.view-tab.active {
      background-color: #3498db;
      color: white;}
.upload-icon {
          font-size: 3rem;
          color: #3498db;
          margin-bottom: 1rem;}
.upload-text {
          font-size: 1.2rem;
          color: #7f8c8d;}
resources-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 2rem;
            align-items: start; /* Align items to the start of the grid cell */}
.resource-card {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
            transition: all 0.3s cubic-bezier(.25,.8,.25,1);
            display: flex;
            flex-direction: column;
            min-height: 300px; /* Set a minimum height for the cards */}
.resource-card:hover {
            box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);}
.resource-icon {
            height: 150px;
            flex-shrink: 0; /* Prevent the icon from shrinking */
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 3rem;
            color: white;
            width: 100%;}
.resource-icon.custom-image {
            height: 150px;
            overflow: hidden;}
.resource-icon.custom-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;}
.image { background-color: #9b59b6;}
.video { background-color: #34495e;}
.audio { background-color: #f1c40f;}
.resource-content {
            padding: 1rem;
            display: flex;
            flex-direction: column;
            flex-grow: 1;}
.resource-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            height: 1.5em; /* Adjust this value as needed */
            line-height: 1.5em;
            overflow: hidden;
            whitespace-nowrap: nowrap;
            text-overflow: ellipsis;
            width: 100%;
            max-width: 100%;
            display: inline-block;}
.resource-description {
            font-size: 0.9rem;
            color: #7f8c8d;
            margin-bottom: 0.5rem;
            flex-grow: 1;
            height: 1.5em; /* Adjust this value as needed */
            line-height: 1.5em;
            overflow: hidden;
            whitespace-nowrap: nowrap;
            text-overflow: ellipsis;
            width: 100%;
            max-width: 100%;
            display: inline-block;}
.resource-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: auto; /* Push tags to the bottom of the content area */}
.tag {
            background-color: #ecf0f1;
            padding: 0.2rem 0.5rem;
            border-radius: 3px;
            font-size: 0.8rem;
            color: #7f8c8d;}
            #loading-overlay {
    background-color: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
}
audio {
    width: 100%;
    margin-top: 0.5rem;
}

audio::-webkit-media-controls-panel {
    background-color: #f0f0f0;
}

audio::-webkit-media-controls-play-button {
    background-color: #3498db;
    border-radius: 50%;
}

audio::-webkit-media-controls-play-button:hover {
    background-color: #2980b9;
}
      </style>
    </head>
    <body>
       
      <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Dashboard</h2>
            @if(Auth::user()->usertype == 'admin')
                <a href="{{ route('admin.dashboard') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition duration-300">
                    View Admin Panel
                </a>
            @endif
        </div>
        <div id="loading-overlay" class="fixed inset-0 bg-white bg-opacity-75 z-50 flex justify-center items-center hidden backdrop-blur-sm">
            <div class="text-center">
                <div class="inline-block animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-blue-500"></div>
                <p class="mt-4 text-lg font-semibold text-gray-700">Loading...</p>
            </div>
        </div>
      
        <!-- Overview Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Resources Card -->
            <div class="card p-6 rounded-lg shadow bg-blue-100 text-blue-800 h-40 transition duration-300 hover:shadow-lg">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Resources</h3>
                        <p class="text-3xl font-bold">{{ $resourceCount }}</p>
                    </div>
                    <i class="fas fa-book-open text-4xl text-blue-500"></i>
                </div>
                <div class="mt-4 flex items-center">
                    @if($resourceChange >= 0)
                        <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                        <span class="text-sm text-green-500">{{ $resourceChange }}% from last week</span>
                    @else
                        <i class="fas fa-arrow-down text-red-500 mr-1"></i>
                        <span class="text-sm text-red-500">{{ abs($resourceChange) }}% from last week</span>
                    @endif
                </div>
            </div>
            
            <!-- Categories Card -->
            <div class="card p-6 rounded-lg shadow bg-green-100 text-green-800 h-40 transition duration-300 hover:shadow-lg">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Categories</h3>
                        <p class="text-3xl font-bold">{{ $categoryCount }}</p>
                    </div>
                    <i class="fas fa-folder text-4xl text-green-500"></i>
                </div>
                <div class="mt-4 flex items-center">
                    @if($categoryChange >= 0)
                        <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                        <span class="text-sm text-green-500">{{ $categoryChange }}% from last week</span>
                    @else
                        <i class="fas fa-arrow-down text-red-500 mr-1"></i>
                        <span class="text-sm text-red-500">{{ abs($categoryChange) }}% from last week</span>
                    @endif
                </div>
            </div>
            
            <!-- Tags Card -->
            <div class="card p-6 rounded-lg shadow bg-yellow-100 text-yellow-800 h-40 transition duration-300 hover:shadow-lg">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Tags</h3>
                        <p class="text-3xl font-bold">{{ $tagCount }}</p>
                    </div>
                    <i class="fas fa-tags text-4xl text-yellow-500"></i>
                </div>
                <div class="mt-4 flex items-center">
                    @if($tagChange >= 0)
                        <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                        <span class="text-sm text-green-500">{{ $tagChange }}% from last week</span>
                    @else
                        <i class="fas fa-arrow-down text-red-500 mr-1"></i>
                        <span class="text-sm text-red-500">{{ abs($tagChange) }}% from last week</span>
                    @endif
                </div>
            </div>
            <!-- Recent Activities Card -->
            <div class="card p-6 rounded-lg shadow bg-purple-100 text-purple-800 h-40 overflow-y-auto transition duration-300 hover:shadow-lg">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-lg font-semibold">Recent Activities</h3>
                    <a href="{{ route('resources.recent-activity') }}" class="text-sm text-purple-600 hover:underline">View All</a>
                </div>
                <ul>
                    @foreach ($recent_activities as $activity)
                    <li class="mb-2 text-sm"> 
                        <i class="fas {{ $activity['icon'] }} mr-2"></i>
                        {{ ucfirst($activity['action']) }} {{ $activity['type'] }}: {{ Str::limit($activity['name'], 20) }}
                        <span class="text-xs text-purple-600 block">{{ $activity['created_at']->diffForHumans() }}</span>
                    </li>
                @endforeach
                </ul>
            </div>
        </div>
    
        <!-- Sections -->
        <!-- Personal Resources -->
        <div class="bg-white shadow rounded-lg p-6">
          <h3 class="text-xl font-semibold mb-4">Resources</h3>
          <div class="section view-tabs mb-4" id="vtabs">
              <a href="{{ route('dashboard', ['type' => 'all'] + request()->except('type')) }}" class="view-tab {{ $resourceType === 'all' ? 'active' : '' }}">All</a>
              <a href="{{ route('dashboard', ['type' => 'document'] + request()->except('type')) }}" class="view-tab {{ $resourceType === 'document' ? 'active' : '' }}">Documents</a>
              <a href="{{ route('dashboard', ['type' => 'image'] + request()->except('type')) }}" class="view-tab {{ $resourceType === 'image' ? 'active' : '' }}">Images</a>
              <a href="{{ route('dashboard', ['type' => 'video'] + request()->except('type')) }}" class="view-tab {{ $resourceType === 'video' ? 'active' : '' }}">Videos</a>
              <a href="{{ route('dashboard', ['type' => 'audio'] + request()->except('type')) }}" class="view-tab {{ $resourceType === 'audio' ? 'active' : '' }}">Audios</a>
          </div>
            <!-- Filter and Search Section -->
            <form action="{{ route('dashboard') }}" method="GET" class="filter-section" id="filter-form">
                <input type="hidden" name="type" value="{{ $resourceType }}">
                <select name="category" id="category-filter" class="border rounded px-3 py-2">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $selectedCategory == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @if($resourceType === 'document')
                    <select name="document_type" id="document-type-filter" class="border rounded px-3 py-2">
                        <option value="">All Document Types</option>
                        @foreach($documentTypes as $type)
                            <option value="{{ $type }}" {{ $selectedDocumentType == $type ? 'selected' : '' }}>
                                {{ strtoupper($type) }}
                            </option>
                        @endforeach
                    </select>
                @endif
                <div class="search-container flex-grow">
                    <input type="text" name="search" placeholder="Search resources..." class="w-full border rounded px-3 py-2" value="{{ $searchTerm }}">
                    <button type="submit" class="search-icon">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <button type="submit" class="filter-button bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Apply Filters
                </button>
            </form>
          
            <!-- Document Grid -->
            <div class="resources-grid">
                <!-- Upload Card -->
                <div id="upload-area" class="upload-card">
                    <i class="fas fa-cloud-upload-alt upload-icon"></i>
                    <p class="upload-text">Click or drag to upload documents</p>
                    <input type="file" id="document-upload" class="hidden" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt">
                </div>
                
                <!-- Resource Cards -->
                @foreach($resources as $resource)
                <div class="resource-card bg-white shadow-lg rounded-lg overflow-hidden flex flex-col" data-resource-id="{{ $resource->id }}">
                    <div class="resource-icon {{ $resource->resource_image_path ? 'custom-image' : '' }} relative h-40">
                        @if($resource->resource_image_path)
                            <img src="{{ asset('storage/resource_images/' . $resource->resource_image_path) }}" alt="{{ $resource->title }}" class="w-full h-full object-cover">
                            @if($resource->type === 'video')
                                <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50">
                                    <i class="fas fa-play-circle text-white text-4xl"></i>
                                </div>
                            @endif
                        @else
                            @php
                                $iconClass = match ($resource->type) {
                                    'document' => match (strtolower(pathinfo($resource->file_path, PATHINFO_EXTENSION))) {
                                        'pdf' => 'bg-red-600',
                                        'doc', 'docx' => 'bg-blue-600',
                                        'xls', 'xlsx' => 'bg-green-600',
                                        'ppt', 'pptx' => 'bg-orange-600',
                                        'txt' => 'bg-gray-600',
                                        default => 'bg-indigo-700',
                                    },
                                    'image' => 'bg-purple-600',
                                    'video' => 'bg-pink-600',
                                    'audio' => 'bg-yellow-600',
                                    default => 'bg-gray-600',
                                };
                            @endphp
                           <div class="flex items-center justify-center h-full w-full {{ $iconClass }}">
                                @if($resource->default_icon)
                                    @php
                                        $iconParts = explode(' ', $resource->default_icon);
                                        $iconClass = implode(' ', array_filter($iconParts, function($part) {
                                            return !str_contains($part, 'text-');
                                        }));
                                    @endphp
                                    <i class="{{ $iconClass }} text-white text-4xl"></i>
                                @else
                                    <i class="fas fa-file text-white text-4xl"></i>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="resource-content p-4 flex-grow flex flex-col">
                        <h4 class="resource-title text-lg font-semibold mb-2 overflow-hidden whitespace-nowrap text-ellipsis" title="{{ $resource->title }}">
                            {{ Str::limit($resource->title, 20) }}
                        </h4>
                        @if($resource->type === 'audio')
                            <p class="resource-description text-sm text-gray-600 mb-4 flex-grow overflow-hidden whitespace-nowrap text-ellipsis">
                                {{ Str::limit(strip_tags($resource->description), 35) }}
                            </p>
                        @else
                            <p class="resource-description text-sm text-gray-600 mb-4 flex-grow">
                                {{ Str::limit(strip_tags($resource->description), 100) }}
                            </p>
                        @endif
                        @if($resource->type === 'audio')
                            <div class="audio-player mb-4">
                                <button class="play-audio-btn bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" data-audio-src="{{ asset('storage/resources/' . $resource->file_path) }}">
                                    <i class="fas fa-play mr-2"></i>Play Audio
                                </button>
                                <div class="audio-container hidden"></div>
                            </div>
                        @endif
                        <div class="resource-tags flex flex-wrap gap-2">
                            <span class="tag bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">{{ $resource->category->name }}</span>
                            @foreach($resource->tags as $tag)
                                <span class="tag bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $resources->appends(request()->except('page'))->links() }}
            </div>
        </div>
    </div>
    
      <script>

         const uploadArea = document.getElementById('upload-area');
    const documentUpload = document.getElementById('document-upload');
    const loadingOverlay = document.getElementById('loading-overlay');
    const viewTabs = document.getElementById('vtabs');

    // Function to show loading overlay
    function showLoading() {
        loadingOverlay.classList.remove('hidden');
    }

    // Function to hide loading overlay
    function hideLoading() {
        loadingOverlay.classList.add('hidden');
    }

    // Add click event listeners to view tabs
    viewTabs.querySelectorAll('.view-tab').forEach(tab => {
        tab.addEventListener('click', (e) => {
            e.preventDefault();
            showLoading();
            window.location.href = e.target.href;
        });
    });

    uploadArea.addEventListener('click', () => {
        documentUpload.click();
    });

    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('bg-gray-100');
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('bg-gray-100');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('bg-gray-100');
        handleFiles(e.dataTransfer.files);
    });

    documentUpload.addEventListener('change', (e) => {
        handleFiles(e.target.files);
    });

    function handleFiles(files) {
        if (files.length > 0) {
            const file = files[0];
            const formData = new FormData();
            formData.append('file', file);

            showLoading();

            // Send the file to the server
            fetch('/upload-file', {
    method: 'POST',
    body: formData,
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    }
})
.then(response => response.json())
.then(data => {
    console.log(data);
    hideLoading();
    if (data.success) {
        window.location.href = '/resources/create?file=' + encodeURIComponent(data.filename);
    } else {
        alert('Error uploading file: ' + data.message);
    }
})
.catch(error => {
    console.error('Error:', error);
    hideLoading();
    alert('Error uploading file');
});
        }
    }

    // Add event listener for filter form submission
    document.getElementById('filter-form').addEventListener('submit', (e) => {
        showLoading();
    });
        document.querySelectorAll('.view-tab:not(.add-tab)').forEach(tab => {
          tab.addEventListener('click', function() {
            document.querySelector('.view-tab.active').classList.remove('active');
            this.classList.add('active');
            console.log(`Switched to ${this.textContent} view`);
          });
        });
    
        // document.querySelector('.view-tab.add-tab').addEventListener('click', function() {
        //   console.log('Add new tab button clicked');
        // });
    
        // document.querySelector('.filter-button').addEventListener('click', function() {
        //   const category = document.getElementById('category-filter').value;
        //   const documentType = document.getElementById('document-type-filter').value;
        //   const searchTerm = document.querySelector('.search-container input').value;
        //   console.log(`Filtering documents - Category: ${category}, Document Type: ${documentType}, Search term: ${searchTerm}`);
        // });
    
        // document.querySelector('.search-icon').addEventListener('click', function() {
        //   const searchTerm = document.querySelector('.search-container input').value;
        //   console.log(`Searching for: ${searchTerm}`);
        // });
    
        // document.querySelector('.create-resource').addEventListener('click', function() {
        //   console.log('Create Resource button clicked');
        // });
    
        document.querySelector('.upload-card').addEventListener('click', function() {
          document.getElementById('document-upload').click();
        });
    
        document.getElementById('document-upload').addEventListener('change', function(event) {
          const file = event.target.files[0];
          if (file) {
            console.log(`File selected: ${file.name}`);
            // Here you would typically handle the file upload
          }
        });
    
        document.querySelectorAll('.resource-card').forEach(card => {
          card.addEventListener('click', function(event) {
            // Prevent navigation if the click was on a button or the audio player
            if (event.target.closest('.play-audio-btn') || event.target.closest('button')) {
              return;
            }
            const resourceId = this.dataset.resourceId;
            window.location.href = `/resources/${resourceId}`;
          });
        });
        document.addEventListener('DOMContentLoaded', function() {
      const tabs = document.querySelectorAll('.view-tab');
      const loadingOverlay = document.getElementById('loading-overlay');
    const filterForm = document.getElementById('filter-form');
    const viewTabs = document.querySelectorAll('.view-tab');
        let loadingTimeout;
        document.querySelectorAll('.play-audio-btn').forEach(button => {
        button.addEventListener('click', function() {
            const audioContainer = this.nextElementSibling;
            const audioSrc = this.getAttribute('data-audio-src');

            if (audioContainer.innerHTML === '') {
                // Create and load the audio element
                const audio = document.createElement('audio');
                audio.controls = true;
                audio.src = audioSrc;
                audio.className = 'w-full';
                audioContainer.appendChild(audio);

                // Show the audio player
                audioContainer.classList.remove('hidden');

                // Hide the play button
                this.classList.add('hidden');

                // Play the audio
                audio.play();
            } else {
                // Toggle visibility if audio is already loaded
                audioContainer.classList.toggle('hidden');
                this.classList.toggle('hidden');
            }
        });
    });
        function showLoading() {
        clearTimeout(loadingTimeout);
        loadingOverlay.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function hideLoading() {
        loadingOverlay.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function scrollToResources() {
        const resourcesSection = document.getElementById('vtabs');
        if (resourcesSection) {
            window.scrollTo({
                top: 230,
                behavior: 'smooth'
            });
            // Hide loading after scroll animation completes
            setTimeout(hideLoading, 100); // Adjust this value if needed
        } else {
            hideLoading();
        }
    }


            // Check if there's a search term or any filter applied
            const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('search') || urlParams.has('category') || urlParams.has('document_type') || urlParams.has('type')) {
        showLoading();
        sessionStorage.setItem('shouldScroll', 'true');
    }

            // Add event listeners for form submission and tab changes
            filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        showLoading();
        sessionStorage.setItem('shouldScroll', 'true');
        setTimeout(() => {
            this.submit();
        }, 100);
    });

    viewTabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            if (!this.classList.contains('active')) {
                e.preventDefault();
                showLoading();
                sessionStorage.setItem('shouldScroll', 'true');
                setTimeout(() => {
                    window.location = this.href;
                }, 100);
            }
        });
    });

            // Check if we should scroll (after page load)
            window.addEventListener('load', function() {
    window.addEventListener('load', function() {
        const loadingScreen = document.getElementById('loading-screen');
        if (loadingScreen) {
            loadingScreen.classList.add('hidden');
        }
    });
        if (sessionStorage.getItem('shouldScroll') === 'true') {
            scrollToResources();
            sessionStorage.removeItem('shouldScroll');
        } else {
            hideLoading();
        }
    });

    // Show loading immediately if it's a page load from form submission or tab click
    if (sessionStorage.getItem('shouldScroll') === 'true') {
        showLoading();
    }
      tabs.forEach(tab => {
        tab.addEventListener('click', function() {
          // Remove 'active' class from all tabs
          tabs.forEach(t => t.classList.remove('active'));
          
          // Add 'active' class to clicked tab
          this.classList.add('active');
          
          // Here you would typically add logic to show/hide content based on the selected tab
          console.log(`Switched to ${this.textContent} tab`);
        });
      });
    });

    function adjustUploadCardHeight() {
        const uploadCard = document.getElementById('upload-area');
        const resourceCards = document.querySelectorAll('.resource-card:not(#upload-area)');
        
        if (resourceCards.length === 0) return;

        // Reset the upload card height
        uploadCard.style.height = 'auto';

        // Find the smallest card height in the current row
        const smallestHeight = Math.min(...Array.from(resourceCards).map(card => card.offsetHeight));

        // Set the upload card height to match the smallest card
        uploadCard.style.height = `${smallestHeight}px`;
    }

    // Call the function on page load and window resize
    window.addEventListener('load', adjustUploadCardHeight);
    window.addEventListener('resize', adjustUploadCardHeight);

    // If you're dynamically loading content, call this function after the content is loaded
    // For example, if you're using AJAX to load more cards:
    // function loadMoreCards() {
    //     // ... load more cards ...
    //     adjustUploadCardHeight();
    // }
      </script>
    </body></html>
  </x-app-layout>