<div id="resource-grid-container">

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
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
                    @can('update', $resource)
                    <a href="{{ route('resources.edit', $resource) }}" 
                       class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-5 rounded text-sm inline-block mr-2 transition duration-300">
                        Edit
                    </a>
                    @endcan
                    @can('delete', $resource)
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
                    @endcan
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="mt-6">
        {{ $resources->links() }}
    </div>
    
    </div>