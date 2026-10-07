@forelse($resources as $resource)
    <div class="resource-card">
        <div class="document-icon {{ App\Helpers\ResourceHelper::getIconClass($resource) }}">
            @if($resource->resource_image_path)
                <img src="{{ asset('storage/resource_images/' . $resource->resource_image_path) }}" alt="{{ $resource->title }}" class="w-full h-full object-cover">
            @else
                @php
                    $iconClass = match (strtolower(pathinfo($resource->file_path, PATHINFO_EXTENSION))) {
                        'pdf' => 'fas fa-file-pdf',
                        'doc', 'docx' => 'fas fa-file-word',
                        'xls', 'xlsx' => 'fas fa-file-excel',
                        'ppt', 'pptx' => 'fas fa-file-powerpoint',
                        'txt' => 'fas fa-file-alt',
                        default => 'fas fa-file',
                    };
                @endphp
                <i class="{{ $iconClass }} text-white"></i>
            @endif
        </div>
        <div class="resource-content">
            <h4 class="resource-title">{{ $resource->title }}</h4>
            <p class="resource-description">{{ Str::limit(strip_tags($resource->description), 100) }}</p>
            <div class="resource-tags">
                <span class="tag">{{ $resource->category->name }}</span>
                @foreach($resource->tags as $tag)
                    <span class="tag">{{ $tag->name }}</span>
                @endforeach
            </div>
        </div>
    </div>
@empty
    <p>No resources found.</p>
@endforelse