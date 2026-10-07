<x-admin-layout>
    <x-slot name="header">
        Generate Reports
    </x-slot>
    <title>TrackSave - Generate Reports</title>

    <div class="bg-gray-100 min-h-screen">
        <main class="container mx-auto px-4 py-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-8">Generate Reports</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                @foreach(['User Activity', 'Resource Utilization', 'System Performance', 'User Growth'] as $reportType)
                    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                        <div class="flex items-center mb-4">
                            <i class="fas fa-chart-line text-blue-600 text-2xl mr-3"></i>
                            <h3 class="text-xl font-semibold text-gray-900">{{ $reportType }} Report</h3>
                        </div>
                        <p class="text-gray-600 mb-4">{{ $reportDescriptions[$reportType] }}</p>
                        <div class="flex justify-between items-center">
                            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors duration-300" 
                            onclick="generateReport('{{ strtolower(str_replace(' ', '-', $reportType)) }}')"
                             aria-label="Generate {{ $reportType }} Report">Generate</button>
                            <button class="text-blue-600 hover:text-blue-800"
                             onclick="previewReport('{{ strtolower(str_replace(' ', '-', $reportType)) }}')"
                             aria-label="Preview {{ $reportType }} Report">
                                <i class="fas fa-eye"></i> Preview
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Custom Report</h2>
                <div id="custom-report-builder" class="mb-6">
                    <!-- Drag and drop interface will be implemented here -->
                    <p class="text-gray-600">Drag and drop metrics to build your custom report.</p>
                </div>
                <form id="custom-report-form" class="space-y-4">
                    <div>
                        <label for="report-name" class="block font-semibold mb-1">Report Name:</label>
                        <input type="text" id="report-name" class="w-full p-2 border border-gray-300 rounded" required>
                    </div>
                    <div>
                        <label for="start-date" class="block font-semibold mb-1">Start Date:</label>
                        <input type="date" id="start-date" class="w-full p-2 border border-gray-300 rounded" required>
                    </div>
                    <div>
                        <label for="end-date" class="block font-semibold mb-1">End Date:</label>
                        <input type="date" id="end-date" class="w-full p-2 border border-gray-300 rounded" required>
                    </div>
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition-colors duration-300">Generate Custom Report</button>
                </form>
            </div>
            
            <div id="recent-reports" class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Recent Reports</h2>
                <ul class="space-y-2">
                    <!-- This will be populated dynamically -->
                </ul>
            </div>
        </main>
    </div>

    <div id="notification" class="fixed bottom-4 right-4 bg-green-500 text-white p-4 rounded-lg shadow-lg hidden"></div>

    <script>
        function generateReport(reportType) {
    showNotification(`Generating ${reportType.replace('-', ' ')} report...`);
    
    fetch('{{ route("admin.reports.generate") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            report_type: reportType,
            start_date: '{{ now()->subMonth()->format("Y-m-d") }}',
            end_date: '{{ now()->format("Y-m-d") }}'
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showNotification(`${reportType.replace('-', ' ')} report generated successfully!`);
            addToRecentReports(reportType);
        } else {
            throw new Error(data.message || 'Unknown error occurred');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification(`Error generating ${reportType.replace('-', ' ')} report: ${error.message}`);
    });
}
    
        function previewReport(reportType) {
            window.open(`{{ url('admin/reports/preview') }}/${reportType}`, '_blank');
        }
    
        function showNotification(message) {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.classList.remove('hidden');
            setTimeout(() => {
                notification.classList.add('hidden');
            }, 3000);
        }
    
        function addToRecentReports(reportType) {
            const recentReports = document.querySelector('#recent-reports ul');
            const li = document.createElement('li');
            li.innerHTML = `<a href="{{ url('admin/reports/download') }}/${reportType}" class="text-blue-600 hover:underline">${reportType.replace('-', ' ')} Report</a>`;
            recentReports.prepend(li);
        }
    
        document.getElementById('custom-report-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const reportName = document.getElementById('report-name').value;
            const startDate = document.getElementById('start-date').value;
            const endDate = document.getElementById('end-date').value;
    
            if (reportName && startDate && endDate) {
                showNotification(`Generating custom report: ${reportName}...`);
                fetch('{{ route("admin.reports.generate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        report_type: 'custom',
                        report_name: reportName,
                        start_date: startDate,
                        end_date: endDate
                    })
                })
                .then(response => response.json())
                .then(data => {
                    showNotification(`Custom report "${reportName}" generated successfully!`);
                    addToRecentReports(reportName);
                })
                .catch(error => {
                    showNotification('Error generating custom report. Please try again.');
                });
            } else {
                showNotification('Please fill in all fields for the custom report.');
            }
        });
    
        // Set min date for date inputs to 2024-06-16 (as per previous context)
        const today = new Date('2024-06-16');
        const dateInputs = document.querySelectorAll('input[type="date"]');
        dateInputs.forEach(input => {
            input.min = '2024-06-16';
            input.max = today.toISOString().split('T')[0];
        });
    </script>
</x-admin-layout>