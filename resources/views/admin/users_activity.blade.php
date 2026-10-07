<x-admin-layout>
    <x-slot name="header">
        User Activity Report
    </x-slot>
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">User Activity Report</h1>
            <p class="text-gray-600">Generated on {{ now()->format('F d, Y H:i:s') }}</p>
        </div>
        
        <div class="mb-8">
            <div class="flex space-x-4">
                <button class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded" onclick="printReport()">Print Report</button>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="downloadReport()">Download PDF</button>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2">Total Active Users</h3>
                <p class="text-3xl font-bold">{{ $totalActiveUsers }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2">Average Daily Sessions</h3>
                <p class="text-3xl font-bold">{{ number_format($averageDailySessions, 1) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2">Resource Interactions</h3>
                <p class="text-3xl font-bold">{{ $resourceInteractions }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2">New User Registrations</h3>
                <p class="text-3xl font-bold">{{ $newUserRegistrations }}</p>
            </div>
        </div>
        
        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-4">User Activity Over Time</h2>
            <div class="bg-white rounded-lg shadow p-6">
                
                <canvas id="activityChart"></canvas>
            </div>
        </div>
        
        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-4">Most Active Users</h2>
            <div class="bg-white rounded-lg shadow p-6">
                <canvas id="userChart"></canvas>
            </div>
        </div>
        
        <div>
            <h2 class="text-2xl font-bold mb-4">Detailed User Activity</h2>
            <div class="bg-white rounded-lg shadow overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Active</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Sessions</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resources Accessed</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($detailedUserActivity as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
<td class="px-6 py-4 whitespace-nowrap">{{ $user->last_active ? \Carbon\Carbon::parse($user->last_active)->format('Y-m-d H:i') : 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->activities_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->activities_count }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Activity Over Time Chart
        const activityCtx = document.getElementById('activityChart').getContext('2d');
        new Chart(activityCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($activityOverTime->keys()) !!},
                datasets: [{
                    label: 'Active Users',
                    data: {!! json_encode($activityOverTime->values()) !!},
                    borderColor: '#3b82f6',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Active Users'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Hour of Day'
                        }
                    }
                }
            }
        });

        // Most Active Users Chart
        const userCtx = document.getElementById('userChart').getContext('2d');
        new Chart(userCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($mostActiveUsers->pluck('name')) !!},
                datasets: [{
                    label: 'Total Sessions',
                    data: {!! json_encode($mostActiveUsers->pluck('activities_count')) !!},
                    backgroundColor: '#3b82f6'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Sessions'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Users'
                        }
                    }
                }
            }
        });

        function printReport() {
            window.print();
        }

        function downloadReport() {
    window.location.href = '{{ route("admin.users.activity.download") }}';
}
    </script>
</x-admin-layout>