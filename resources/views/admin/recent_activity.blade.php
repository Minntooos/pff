<x-admin-layout>
    <style>
    .flatpickr-calendar {
        background: white;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
        border-radius: 0.375rem;
    }
    .flatpickr-day.selected {
        background: #3b82f6;
        border-color: #3b82f6;
    }
    .flatpickr-day.today {
        border-color: #3b82f6;
    }
</style>
    <x-slot name='header'>
        Activity History
    </x-slot>

    <div class="container mx-auto px-4 py-8">

        <div class="mb-6">
            <input type="text" id="activity-search" placeholder="Search activities..." class="w-full p-3 border border-gray-300 rounded-lg text-lg">
        </div>

        <div class="flex flex-wrap justify-between items-center mb-6">
            <div class="flex items-center mb-4 mr-4">
                <label for="action-filter" class="mr-2">Action:</label>
                <select id="action-filter" class="p-2 border border-gray-300 rounded">
                    <option value="">All Actions</option>
                    @foreach($actionTypes as $action)
                        <option value="{{ $action }}">{{ ucfirst($action) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center mb-4 mr-4">
                <label for="resource-filter" class="mr-2">Resource Type:</label>
                <select id="resource-filter" class="p-2 border border-gray-300 rounded">
                    <option value="">All Types</option>
                    @foreach($resourceTypes as $type)
                        <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center mb-4 mr-4">
                <label for="start_date" class="mr-2">Start Date:</label>
                <input type="date" id="start_date" name="start_date" class="p-2 border border-gray-300 rounded">
            </div>
            <div class="flex items-center mb-4 mr-4">
                <label for="end_date" class="mr-2">End Date:</label>
                <input type="date" id="end_date" name="end_date" class="p-2 border border-gray-300 rounded">
            </div>
            <button id="apply-filters" class="bg-blue-800 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300">Apply Filters</button>
        </div>

        <div id="activity-list" class="bg-white rounded-lg shadow-md overflow-hidden">
            @include('admin.partials.activity_items', ['activities' => $activities])
        </div>

        <div id="pagination-container" class="mt-4">
            {{ $activities->links() }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
   const searchInput = document.getElementById('activity-search');
    const actionFilter = document.getElementById('action-filter');
    const resourceFilter = document.getElementById('resource-filter');
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const applyFiltersBtn = document.getElementById('apply-filters');
    const activityList = document.getElementById('activity-list');
    const paginationContainer = document.getElementById('pagination-container');

    function fetchActivities(url = '{{ route("admin.recent-activity") }}') {
        const params = new URLSearchParams({
            search: searchInput.value,
            action: actionFilter.value,
            resource_type: resourceFilter.value,
            start_date: startDate.value,
            end_date: endDate.value,
        });

        fetch(`${url}${url.includes('?') ? '&' : '?'}${params}`)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                activityList.innerHTML = doc.getElementById('activity-list').innerHTML;
                paginationContainer.innerHTML = doc.getElementById('pagination-container').innerHTML;
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    applyFiltersBtn.addEventListener('click', fetchActivities);

    // Handle pagination clicks
    paginationContainer.addEventListener('click', function(e) {
        if (e.target.tagName === 'A') {
            e.preventDefault();
            fetchActivities(e.target.href);
        }
    });
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('pagination-link')) {
                e.preventDefault();
                fetchActivities(e.target.href);
            }
        });
    </script>
</x-admin-layout>