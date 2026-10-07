<x-app-layout>

<div class="container mx-auto px-4">
    <h2 class="text-2xl font-semibold mb-6">Notifications</h2>

    @if($notifications->count() > 0)
        <div class="mb-4">
            <button id="markAllAsRead" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Mark All as Read
            </button>
        </div>

        <div class="space-y-4">
            @foreach($notifications as $notification)
                <div class="bg-white shadow rounded-lg p-4 {{ $notification->read_at ? 'opacity-50' : '' }}" id="notification-{{ $notification->id }}">
                    <p class="text-gray-800">{{ $notification->data['message'] ?? '' }}</p>
                    <p class="text-sm text-gray-600 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                    @if(!$notification->read_at)
                        <button class="mark-as-read mt-2 text-blue-500 hover:text-blue-700" data-id="{{ $notification->id }}">
                            Mark as Read
                        </button>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @else
        <p>No notifications.</p>
    @endif
</div>

<script>
document.querySelectorAll('.mark-as-read').forEach(button => {
    button.addEventListener('click', function() {
        const notificationId = this.dataset.id;
        fetch(`/notifications/${notificationId}/mark-as-read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`notification-${notificationId}`).classList.add('opacity-50');
                this.remove();
            }
        });
    });
});

document.getElementById('markAllAsRead').addEventListener('click', function() {
    fetch('/notifications/mark-all-as-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelectorAll('.mark-as-read').forEach(button => button.remove());
            document.querySelectorAll('.bg-white').forEach(div => div.classList.add('opacity-50'));
        }
    });
});
</script>
</x-app-layout>