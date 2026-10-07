<x-admin-layout>
    <x-slot name="header">
        Pending Resources
    </x-slot>
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold"> </h1>
            <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-sm">{{ $pendingResources->total() }} Pending</span>
        </div>

        <div class="mb-6">
            <input type="text" id="search" placeholder="Search resources..." class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
     
        
        <div class="mb-6 flex space-x-4">
            <div class="w-1/2">
                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" id="start_date" name="start_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div class="w-1/2">
                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                <input type="date" id="end_date" name="end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
        </div>

        @foreach ($pendingResources as $resource)
        <div class="bg-white shadow-md rounded-lg p-6 mb-4 resource-item" data-submitted-at="{{ $resource->created_at->toDateString() }}" data-id="{{ $resource->id }}">
                    <div class="flex justify-between items-start mb-4">
                    <div>
                        <h2 class="text-xl font-semibold mb-2">{{ $resource->title }}</h2>
                        <p class="text-gray-600 mb-2">{!! Str::limit($resource->description, 100) !!}</p>
                        <p class="text-sm text-gray-500">Type: {{ $resource->type }} | Category: {{ $resource->category->name }}</p>
                    </div>
                    <span class="text-sm text-gray-400">Submitted {{ $resource->created_at->diffForHumans() }}</span>
                </div>
                <p class="text-sm text-gray-500 mb-4">Submitted by: {{ $resource->user->name }}</p>
                <div class="flex justify-end items-center">
                    <button onclick="viewDetails({{ $resource->id }})" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mr-2">View Details</button>
                    <button onclick="confirmAction('approve', {{ $resource->id }})" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 mr-2">Approve</button>
                    <button onclick="confirmAction('reject', {{ $resource->id }})" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Reject</button>
                </div>
            </div>
        @endforeach

        {{ $pendingResources->links() }}
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-xl">
            <h2 class="text-2xl font-bold mb-4">Confirm Action</h2>
            <p id="confirmationMessage" class="mb-6"></p>
            <div class="flex justify-end">
                <button onclick="closeModal()" class="bg-gray-300 text-gray-800 px-4 py-2 rounded mr-2">Cancel</button>
                <button id="confirmButton" class="bg-blue-500 text-white px-4 py-2 rounded">Confirm</button>
            </div>
        </div>
    </div>
<!-- Resource Details Modal -->
<!-- Resource Details Modal -->
<div id="resourceDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-xl max-w-2xl w-full">
        <h2 class="text-2xl font-bold mb-4">Resource Details</h2>
        <div id="resourceDetailsContent"></div>
        <div class="flex justify-end mt-6">
            <button id="viewResourceBtn" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mr-2">View Resource</button>
            <button onclick="closeResourceModal()" class="bg-gray-300 text-gray-800 px-4 py-2 rounded">Close</button>
        </div>
    </div>
</div>

    <script>
   function viewDetails(resourceId) {
    const resource = document.querySelector(`.resource-item[data-id="${resourceId}"]`);
    const modal = document.getElementById('resourceDetailsModal');
    const content = document.getElementById('resourceDetailsContent');
    const viewResourceBtn = document.getElementById('viewResourceBtn');

    if (resource && modal && content) {
        const title = resource.querySelector('h2')?.textContent || 'N/A';
        const description = resource.querySelector('p:nth-of-type(1)')?.textContent || 'N/A';
        const typeAndCategory = resource.querySelector('p:nth-of-type(2)')?.textContent || 'N/A';
        const submittedBy = resource.querySelector('p:nth-of-type(3)')?.textContent || 'N/A';
        const submittedAt = resource.querySelector('span.text-sm.text-gray-400')?.textContent || 'N/A';

        content.innerHTML = `
            <h3 class="text-xl font-semibold mb-2">${title}</h3>
            <p class="text-gray-600 mb-4">${description}</p>
            <p class="text-sm text-gray-500 mb-2">${typeAndCategory}</p>
            <p class="text-sm text-gray-500 mb-2">${submittedBy}</p>
            <p class="text-sm text-gray-400">${submittedAt}</p>
        `;

        viewResourceBtn.onclick = () => {
            window.location.href = `/resources/${resourceId}`;
        };

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    } else {
        console.error('Resource or modal elements not found');
    }
}
function closeResourceModal() {
    const modal = document.getElementById('resourceDetailsModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function confirmAction(action, resourceId) {
    const modal = document.getElementById('confirmationModal');
    const message = document.getElementById('confirmationMessage');
    const confirmButton = document.getElementById('confirmButton');

    message.textContent = `Are you sure you want to ${action} this resource?`;
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    confirmButton.onclick = () => {
        performAction(action, resourceId);
    };
}
function performAction(action, resourceId) {
    const routeName = action === 'approve' ? 'admin.approve-resource' : 'admin.reject-resource';
    const url = `{{ route('admin.approve-resource', ':id') }}`.replace(':id', resourceId);

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            document.querySelector(`.resource-item[data-id="${resourceId}"]`).remove();
            const pendingCount = document.querySelector('.bg-yellow-500');
            if (pendingCount) {
                const currentCount = parseInt(pendingCount.textContent);
                pendingCount.textContent = `${currentCount - 1} Pending`;
            }
            showNotification(`Resource ${action}d successfully`, 'success');
        } else {
            throw new Error(data.message || 'An error occurred while processing the resource.');
        }
        closeModal();
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification(`Error: ${error.message}`, 'error');
        closeModal();
    });
}
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.textContent = message;
    notification.className = `fixed bottom-4 right-4 p-4 rounded-md text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
    document.body.appendChild(notification);
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

        function closeModal() {
            const modal = document.getElementById('confirmationModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Implement search functionality
        document.getElementById('search').addEventListener('input', function(e) {
            // Add search logic here
        });
        const searchInput = document.getElementById('search');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const resources = document.querySelectorAll('.resource-item');

    function filterResources() {
        const searchTerm = searchInput.value.toLowerCase();
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);

        resources.forEach(resource => {
            const title = resource.querySelector('h2').textContent.toLowerCase();
            const description = resource.querySelector('p').textContent.toLowerCase();
            const dateSubmitted = new Date(resource.dataset.submittedAt);

            const matchesSearch = title.includes(searchTerm) || description.includes(searchTerm);
            const matchesDateRange = (!startDate || dateSubmitted >= startDate) && (!endDate || dateSubmitted <= endDate);

            if (matchesSearch && matchesDateRange) {
                resource.style.display = 'block';
            } else {
                resource.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterResources);
    startDateInput.addEventListener('change', filterResources);
    endDateInput.addEventListener('change', filterResources);
    </script>
</x-admin-layout>