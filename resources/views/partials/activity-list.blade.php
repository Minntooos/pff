@foreach ($activities as $activity)
    <div class="activity-item flex items-center p-4 border-b border-gray-200">
        <div class="text-2xl mr-4">
            @switch($activity->action)
                @case('create')
                    📤
                    @break
                @case('edit')
                    ✏️
                    @break
                @case('delete')
                    🗑️
                    @break
                @case('share')
                    🔗
                    @break
                @case('comment')
                    💬
                    @break
                @case('like')
                    👍
                    @break
                @case('createTag')
                    🏷️
                    @break
                @case('createCategory')
                    📁
                    @break
                @case('reply')
                    ↩️
                    @break
                @case('approve')
                    ✅
                    @break
                @default
                    📄
            @endswitch
        </div>
        <div class="flex-grow">
            <div class="text-sm text-gray-600">{{ $activity->created_at->format('Y-m-d H:i') }}</div>
            <div>
                <span class="font-semibold">{{ ucfirst($activity->action) }}</span>

                <span class="text-blue-800">{{ $activity->resource->title ?? 'Resource' }}</span>
            </div>
        </div>
    </div>
@endforeach

<div class="mt-4">
    {{ $activities->links() }}
</div>