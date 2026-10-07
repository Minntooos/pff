<!DOCTYPE html>
<html>
<head>
    <title>User Activity Report</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        h1, h2 { color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .summary-box { background-color: #ecf0f1; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
        .chart-container { width: 100%; height: 300px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>User Activity Report</h1>
    <p>Generated on {{ now()->format('F d, Y H:i:s') }}</p>

    <div class="summary-box">
        <h2>Summary</h2>
        <ul>
            <li>Total Active Users: {{ $totalActiveUsers }}</li>
            <li>Average Daily Sessions: {{ number_format($averageDailySessions, 1) }}</li>
            <li>Total Resource Interactions: {{ $resourceInteractions }}</li>
            <li>New User Registrations (Last 30 days): {{ $newUserRegistrations }}</li>
        </ul>
    </div>

    <h2>User Activity Trend (Last 30 Days)</h2>
    <div class="chart-container">
        <!-- Insert chart image here -->
    </div>

    <h2>Most Active Users</h2>
    <table>
        <thead>
            <tr>
                <th>User</th>
                <th>Total Sessions</th>
                <th>Resources Accessed</th>
                <th>Last Active</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mostActiveUsers as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->sessions_count }}</td>
                <td>{{ $user->resources_accessed_count }}</td>
                <td>{{ $user->last_active ? \Carbon\Carbon::parse($user->last_active)->format('Y-m-d H:i') : 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Recent User Activities</h2>
    <table>
        <thead>
            <tr>
                <th>User</th>
                <th>Action</th>
                <th>Resource</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentActivities as $activity)
            <tr>
                <td>{{ $activity->user->name }}</td>
                <td>{{ ucfirst($activity->action) }}</td>
                <td>{{ $activity->resource ? $activity->resource->title : 'N/A' }}</td>
                <td>{{ $activity->created_at->format('Y-m-d H:i:s') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>