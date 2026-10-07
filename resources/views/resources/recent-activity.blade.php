<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-blue-800 mb-6">Activity History</h1>

        <div class="mb-6">
            <input type="text" id="activity-search" placeholder="Search activities..." class="w-full p-3 border border-gray-300 rounded-lg text-lg">
        </div>

        <div class="flex flex-wrap justify-between items-center mb-6">
            <div class="flex items-center mb-3 mr-4">
                <label for="action-filter" class="mr-2">Action:</label>
                <select id="action-filter" class="p-2 border border-gray-300 rounded">
                    <option value="">All Actions</option>
                    <option value="create">Create</option>
                    <option value="edit">Edit</option>
                    <option value="delete">Delete</option>
                    <option value="share">Share</option>
                    <option value="comment">Comment</option>
                    <option value="like">Like</option>
                    <option value="createTag">Create Tag</option>
                    <option value="createCategory">Create Category</option>
                    <option value="reply">Reply</option>
                    <option value="approve">Approve</option>
                </select>
            </div>
            <div class="flex items-center mb-3 mr-4">
                <label for="resource-filter" class="mr-2">Resource Type:</label>
                <select id="resource-filter" class="p-2 border border-gray-300 rounded">
                    <option value="">All Types</option>
                    <option value="document">Document</option>
                    <option value="image">Image</option>
                    <option value="audio">Audio</option>
                    <option value="video">Video</option>
                </select>
            </div>
            <div class="flex items-center mb-3 mr-4">
                <label for="date-range" class="mr-2">Date Range:</label>
                <input type="date" id="date-from" class="p-2 border border-gray-300 rounded mr-2">
                <span class="mr-2">to</span>
                <input type="date" id="date-to" class="p-2 border border-gray-300 rounded">
            </div>
            <button id="apply-filters" class="bg-blue-800 text-white px-4 py-2 rounded hover:bg-blue-900 transition duration-300">Apply Filters</button>
        </div>

        <div id="activity-list" class="bg-white rounded-lg shadow-md overflow-hidden">
            @include('partials.activity-list')
        </div>
    </div>

    <script>
        const applyFiltersBtn = document.getElementById('apply-filters');
        const searchInput = document.getElementById('activity-search');

        function loadActivities(url = '{{ route('resources.recent-activity') }}') {
            const actionFilter = document.getElementById('action-filter').value;
            const resourceFilter = document.getElementById('resource-filter').value;
            const dateFrom = document.getElementById('date-from').value;
            const dateTo = document.getElementById('date-to').value;
            const search = searchInput.value;

            axios.get(url, {
                params: {
                    action: actionFilter,
                    resource_type: resourceFilter,
                    date_from: dateFrom,
                    date_to: dateTo,
                    search: search
                }
            })
            .then(response => {
                document.getElementById('activity-list').innerHTML = response.data;
            })
            .catch(error => {
                console.error('Error loading activities:', error);
            });
        }

        applyFiltersBtn.addEventListener('click', () => loadActivities());
        searchInput.addEventListener('input', debounce(() => loadActivities(), 300));

        // Debounce function
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    </script>
</x-app-layout>