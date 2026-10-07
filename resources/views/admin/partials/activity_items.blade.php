@forelse($activities as $activity)
<div class="border-b border-gray-200 p-6 hover:bg-gray-50 transition duration-150 ease-in-out">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center space-x-4">
            <img src="{{ $activity->user->is_admin ? asset('images/A.jpeg') : asset('images/U.jpeg') }}" alt="{{ $activity->user->name }}" class="w-12 h-12 rounded-full">
            <div>
                <p class="font-semibold text-gray-800 text-lg">
                    {{ $activity->user->name }}
                    @if($activity->user->is_admin)
                        <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">Admin</span>
                    @endif
                </p>
                <p class="text-sm text-gray-600">{{ $activity->created_at->format('M d, Y H:i:s') }}</p>
            </div>
        </div>
        <div class="flex items-center space-x-4">
            @if($activity->resource)
                <div class="text-sm text-gray-600 flex items-center space-x-2">
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">{{ ucfirst($activity->resource->type) }}</span>
                    @if($activity->resource->category)
                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">{{ $activity->resource->category->name }}</span>
                    @endif
                    @if($activity->resource->tags->isNotEmpty())
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">{{ $activity->resource->tags->pluck('name')->implode(', ') }}</span>
                    @endif
                </div>
            @endif
            <span class="px-3 py-1 rounded-full text-sm font-semibold 
                @if($activity->action == 'create') bg-green-100 text-green-800
                @elseif($activity->action == 'update') bg-blue-100 text-blue-800
                @elseif($activity->action == 'delete') bg-red-100 text-red-800
                @else bg-gray-100 text-gray-800
                @endif">
                {{ ucfirst($activity->action) }}
            </span>
        </div>
    </div>
    <div class="ml-16">
        <p class="text-gray-700 mb-3 text-lg">
            <span class="font-medium">{{ ucfirst($activity->action) }}</span> 
            <span class="font-semibold">{{ ucfirst($activity->resource_type) }}</span>
            @if($activity->resource)
                "<span class="font-medium">{{ $activity->resource->title }}</span>"
                <a href="{{ route('resources.show', $activity->resource->id) }}" class="ml-2 text-blue-600 hover:underline">View Resource</a>
            @endif
        </p>
        @if($activity->details)
            <p class="text-sm text-gray-600 mb-3">{{ $activity->details }}</p>
        @endif
        @if($activity->resource)
            <div class="text-sm text-gray-600">
                @if($activity->resource->updated_at && $activity->resource->updated_at != $activity->resource->created_at)
                    <p><span class="font-semibold">Last Updated:</span> {{ $activity->resource->updated_at->format('M d, Y H:i:s') }}</p>
                @endif
            </div>
        @endif
    </div>
    @if($activity->status)
        <div class="mt-3 ml-16">
            <span class="px-2 py-1 rounded text-xs font-semibold 
                @if($activity->status == 'success') bg-green-100 text-green-800
                @elseif($activity->status == 'pending') bg-yellow-100 text-yellow-800
                @elseif($activity->status == 'failed') bg-red-100 text-red-800
                @else bg-gray-100 text-gray-800
                @endif">
                {{ ucfirst($activity->status) }}
            </span>
        </div>
    @endif
</div>
@empty
    <div class="p-6 text-center text-gray-500">No activities found.</div>
@endforelse