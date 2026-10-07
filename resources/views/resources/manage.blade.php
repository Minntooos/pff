<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Resource Management</h1>
            <button onclick="window.location.href='{{ route('resources.recent-activity') }}'" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded transition duration-300">
                View Activity History
            </button>
        </div>
        
        <div class="flex mb-6 bg-white rounded-lg overflow-hidden">
            <a href="{{ route('resources.manage', ['type' => '']) }}" 
               class="tab {{ !request('type') ? 'active bg-blue-600 text-white' : 'hover:bg-gray-200' }} px-4 py-2 cursor-pointer">
                View All
            </a>
            <a href="{{ route('resources.manage', ['type' => 'image']) }}" 
               class="tab {{ request('type') == 'image' ? 'active bg-blue-600 text-white' : 'hover:bg-gray-200' }} px-4 py-2 cursor-pointer">
                Images
            </a>
            <a href="{{ route('resources.manage', ['type' => 'video']) }}" 
               class="tab {{ request('type') == 'video' ? 'active bg-blue-600 text-white' : 'hover:bg-gray-200' }} px-4 py-2 cursor-pointer">
                Videos
            </a>
            <a href="{{ route('resources.manage', ['type' => 'audio']) }}" 
               class="tab {{ request('type') == 'audio' ? 'active bg-blue-600 text-white' : 'hover:bg-gray-200' }} px-4 py-2 cursor-pointer">
                Audios
            </a>
            <a href="{{ route('resources.manage', ['type' => 'document']) }}" 
               class="tab {{ request('type') == 'document' ? 'active bg-blue-600 text-white' : 'hover:bg-gray-200' }} px-4 py-2 cursor-pointer">
                Documents
            </a>
        </div>

        <form id="search-filter-form" class="flex flex-wrap mb-6">
            <div class="w-full md:w-1/3 mb-4 md:mb-0 pr-0 md:pr-2">
                <input type="text" name="search" placeholder="Search resources..." 
                       class="w-full p-2 border border-gray-300 rounded" 
                       value="{{ request('search') }}">
            </div>
            <div class="w-full md:w-2/3 flex flex-wrap">
                <select name="category" 
                        class="w-full md:w-1/4 p-2 border border-gray-300 rounded mb-2 md:mb-0 md:mr-2">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" 
                                {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <input type="date" name="start_date" 
           class="w-full md:w-1/4 p-2 border border-gray-300 rounded mb-2 md:mb-0 md:mr-2" 
           value="{{ request('start_date') }}" placeholder="Start Date">
    <input type="date" name="end_date" 
           class="w-full md:w-1/4 p-2 border border-gray-300 rounded mb-2 md:mb-0 md:mr-2" 
           value="{{ request('end_date') }}" placeholder="End Date">
                <button type="submit" 
                        class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                    Apply Filters
                </button>
            </div>
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <div id="upload-area" 
                 class="bg-white rounded-lg shadow-md p-6 border-2 border-dashed border-blue-600 flex flex-col items-center justify-center cursor-pointer">
                <i class="fas fa-cloud-upload-alt text-5xl text-blue-600 mb-2"></i>
                <p class="text-gray-600">Click or drag to upload files</p>
                <input type="file" id="document-upload" class="hidden" 
                       accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.mp3,.wav,.mp4,.mov,.jpg,.jpeg,.png,.gif">
            </div>

            @foreach($resources as $resource)
            <div class="bg-white rounded-lg shadow-md overflow-hidden transition duration-300 hover:shadow-lg hover:-translate-y-1">
                <div class="p-4 flex flex-col h-full">
                    <div class="resource-icon {{ $resource->resource_image_path ? 'custom-image' : '' }} relative h-40 mb-4">
                        @if($resource->resource_image_path)
                            <img src="{{ asset('storage/resource_images/' . $resource->resource_image_path) }}" 
                                 alt="{{ $resource->title }}" 
                                 class="w-full h-full object-cover">
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
                                    <i class="{{ $resource->default_icon }} text-white text-4xl"></i>
                                @else
                                    <i class="fas fa-file text-white text-4xl"></i>
                                @endif
                            </div>
                        @endif
                    </div>
                    <h3 class="text-xl font-semibold truncate mb-2">{{ $resource->title }}</h3>
                    <p class="text-gray-600 text-sm mb-4 overflow-hidden" 
                       style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                        {{ Str::limit(strip_tags($resource->description), 100) }}
                    </p>
                    <div class="flex flex-wrap mb-4">
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold mr-2 mb-2 px-2.5 py-0.5 rounded">
                            {{ $resource->category->name }}
                        </span>
                        <span class="bg-green-100 text-green-800 text-xs font-semibold mr-2 mb-2 px-2.5 py-0.5 rounded">
                            {{ ucfirst($resource->type) }}
                        </span>
                    </div>
                    <div class="mt-auto">
                        <a href="{{ route('resources.show', $resource) }}" 
                           class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-5 rounded text-sm inline-block mr-2 transition duration-300">
                            View
                        </a>
                        <a href="{{ route('resources.edit', $resource) }}" 
                           class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-5 rounded text-sm inline-block mr-2 transition duration-300">
                            Edit
                        </a>
                        <form action="{{ route('resources.destroy', $resource) }}" 
                              method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this resource?');" 
                              class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-5 rounded text-sm transition duration-300">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $resources->links() }}
        </div>
        <div id="resource-grid-container">
            
            <partials.resource-grid  /> 
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('search-filter-form');
            const resourceGridContainer = document.getElementById('resource-grid-container');
    
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                updateResources();
            });
    
            form.querySelectorAll('select, input[type="date"]').forEach(element => {
                element.addEventListener('change', updateResources);
            });
    
            function updateResources() {
                const formData = new FormData(form);
                const searchParams = new URLSearchParams();
    
                for (const [key, value] of formData.entries()) {
                    if (value) {
                        if ((key === 'start_date' || key === 'end_date') && 
                            (!formData.get('start_date') || !formData.get('end_date'))) {
                            continue;
                        }
                        searchParams.append(key, value);
                    }
                }
    
                fetch(`{{ route('resources.manage') }}?${searchParams.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    resourceGridContainer.innerHTML = html;
                    //route('resources.manage')


                    history.pushState(null, '', `{{ route('resources.manage') }}?${searchParams.toString()}`);

                 window.location.reload();

                  

                })
                .catch(error => console.error('Error:', error));
            }
        });
    </script>
</x-app-layout>