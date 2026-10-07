<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
    @foreach($resources as $resource)
        @if($resource->is_public && $resource->approval_status === 'approved')
        <div class="bg-white rounded-lg shadow-md overflow-hidden transition duration-300 hover:shadow-lg hover:-translate-y-1">
            <div class="relative h-40">
                @if($resource->resource_image_path)
                    <img src="{{ asset('storage/resource_images/' . $resource->resource_image_path) }}" alt="{{ $resource->title }}" class="w-full h-full object-cover">
                @else
                    @php
                    $iconClass = match ($resource->type) {
                        'document' => match (strtolower(pathinfo($resource->file_path, PATHINFO_EXTENSION))) {
                            'pdf' => 'bg-red-600 fas fa-file-pdf',
                            'doc', 'docx' => 'bg-blue-600 fas fa-file-word',
                            'xls', 'xlsx' => 'bg-green-600 fas fa-file-excel',
                            'ppt', 'pptx' => 'bg-orange-600 fas fa-file-powerpoint',
                            'txt' => 'bg-gray-600 fas fa-file-alt',
                            default => 'bg-indigo-700 fas fa-file',
                        },
                        'image' => 'bg-purple-600 fas fa-image',
                        'video' => 'bg-pink-600 fas fa-video',
                        'audio' => 'bg-yellow-600 fas fa-music',
                        default => 'bg-gray-600 fas fa-file',
                    };
                    $bgClass = explode(' ', $iconClass)[0];
                    $faClass = explode(' ', $iconClass)[1] . ' ' . explode(' ', $iconClass)[2];
                    @endphp
                    <div class="flex items-center justify-center h-full w-full {{ $bgClass }}">
                        <i class="{{ $faClass }} text-white text-4xl"></i>
                    </div>
                @endif
                @if($resource->type === 'video')
                    <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50">
                        <i class="fas fa-play-circle text-white text-4xl"></i>
                    </div>
                @endif
            </div>
            <div class="p-4">
                <h3 class="text-lg font-semibold mb-2">{{ $resource->title }}</h3>
                <p class="text-gray-600 text-sm mb-4">{!! Str::limit($resource->description, 80) !!}</p>
                <div class="flex justify-between items-center text-xs text-gray-500">
                    <span>{{ $resource->type }}</span>
                    <a href="{{ route('resources.show', $resource) }}" class="text-blue-500 hover:underline">View More</a>
                </div>
            </div>
        </div>
        @endif
    @endforeach
</div>
    <div class="mt-6">
        {{ $resources->links() }}
    </div>
</div>