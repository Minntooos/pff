<x-admin-layout>
    <x-slot name="header">
        Dashboard
    </x-slot>
        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top bar -->
            

            <!-- Main content area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="container mx-auto px-6 py-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-semibold text-gray-700">Total Users</h2>
                                <span class="text-green-500 text-sm font-semibold ml-2">+5%</span>
                            </div>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($totalUsers) }}</p>
                            <div class="mt-4">
                                <canvas id="usersChart" width="100%" height="50"></canvas>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-semibold text-gray-700">Resources</h2>
                                <span class="text-green-500 text-sm font-semibold ml-2">+12%</span>
                            </div>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($totalResources) }}</p>
                            <div class="mt-4">
                                <canvas id="resourcesChart" width="100%" height="50"></canvas>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-semibold text-gray-700">Active Users</h2>
                                <span class="text-red-500 text-sm font-semibold ml-2">-2%</span>
                            </div>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($activeUsers) }}</p>
                            <div class="mt-4">
                                <canvas id="activeUsersChart" width="100%" height="50"></canvas>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-semibold text-gray-700">Server Load</h2>
                                <span class="text-yellow-500 text-sm font-semibold ml-2">{{ $serverLoad }}%</span>
                            </div>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $serverLoad }}%</p>
                            <div class="mt-4">
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-yellow-500 h-2.5 rounded-full" style="width: {{ $serverLoad }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-lg font-semibold text-gray-700">Recent Activity</h2>
                                <a href="{{ route('admin.recent-activity') }}" class="text-blue-500 hover:underline">View All</a>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            <th class="pb-3">User</th>
                                            <th class="pb-3">Action</th>
                                            <th class="pb-3">Time</th>
                                            <th class="pb-3"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm">
                                        @foreach ($recentActivity as $activity)
                                            <tr>
                                                <td class="py-2">{{ $activity->user->name }}</td>
                                                <td class="py-2">{{ $activity->action }}</td>
                                                <td class="py-2">{{ $activity->created_at->diffForHumans() }}</td>
                                                <td class="py-2">
                                                    <a href="{{ route('admin.recent-activity', ['activity' => $activity->id]) }}" class="text-blue-500 hover:underline">View Details</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h2 class="text-lg font-semibold text-gray-700 mb-4">Resource Distribution</h2>
                            <canvas id="resourceDistributionChart" width="100%" height="200"></canvas>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-lg font-semibold text-gray-700 mb-4">Pending Resources</h2>
                        <p class="text-3xl font-bold">{{ $pendingResourcesCount }}</p>
                        <a href="{{ route('admin.pending-resources') }}" class="text-blue-500 hover:underline">View all</a>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-lg font-semibold text-gray-700 mb-4">Quick Actions</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <a href="{{ route('admin.users.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition duration-200">
                                Add New User
                            </a>
                            <a href="{{ route('admin.pending-resources') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded transition duration-200">
                                Approve Resources
                            </a>
                            <a href="{{ route('admin.reports') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded transition duration-200">
                                Generate Report
                            </a>
                            <button class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded transition duration-200">
                                System Backup
                            </button>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Users Chart
        var usersCtx = document.getElementById('usersChart').getContext('2d');
        new Chart(usersCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Users',
                    data: [1000, 1050, 1100, 1150, 1200, 1250],
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        display: false
                    },
                    y: {
                        display: false
                    }
                }
            }
        });

        // Resources Chart
        var resourcesCtx = document.getElementById('resourcesChart').getContext('2d');
        new Chart(resourcesCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Resources',
                    data: [4000, 4200, 4500, 4800, 5100, 5423],
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        display: false
                    },
                    y: {
                        display: false
                    }
                }
            }
        });

        // Active Users Chart
        var activeUsersCtx = document.getElementById('activeUsersChart').getContext('2d');
        new Chart(activeUsersCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Active Users',
                    data: [800, 820, 810, 800, 790, 780],
                    borderColor: 'rgb(54, 162, 235)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        display: false
                    },
                    y: {
                        display: false
                    }
                }
            }
        });

        // Resource Distribution Chart
        var resourceDistributionCtx = document.getElementById('resourceDistributionChart').getContext('2d');
var resourceDistributionData = @json($resourceDistribution);
new Chart(resourceDistributionCtx, {
    type: 'doughnut',
    data: {
        labels: resourceDistributionData.map(item => item.type),
        datasets: [{
            data: resourceDistributionData.map(item => item.count),
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 206, 86)',
                'rgb(75, 192, 192)',
                'rgb(153, 102, 255)'
            ],
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Resource Distribution'
            }
        }
    }
});
    </script>
</body>
</html>
</x-admin-layout>