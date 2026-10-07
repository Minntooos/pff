<!DOCTYPE html>
<html>
<head>
    <title>{{ ucfirst($type) }} Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>{{ ucfirst($type) }} Report</h1>
    <p>Generated on {{ now()->format('F d, Y H:i:s') }}</p>

    @if($type == 'user-activity')
        <h2>Summary</h2>
        <ul>
            <li>Total Active Users: {{ $data['total_active_users'] }}</li>
            <li>Average Daily Sessions: {{ number_format($data['average_daily_sessions'], 1) }}</li>
        </ul>

        <h2>Most Active Users</h2>
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Activity Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['most_active_users'] as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->activities_count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @elseif($type == 'resource-utilization')
        <h2>Summary</h2>
        <ul>
            <li>Total Resources: {{ $data['total_resources'] }}</li>
        </ul>

        <h2>Most Viewed Resources</h2>
        <table>
            <thead>
                <tr>
                    <th>Resource</th>
                    <th>View Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['most_viewed_resources'] as $resource)
                <tr>
                    <td>{{ $resource->title }}</td>
                    <td>{{ $resource->activities_count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @elseif($type == 'system-performance')
        <h2>System Metrics</h2>
        <ul>
            <li>Average Response Time: {{ $data['average_response_time'] }} ms</li>
            <li>Uptime Percentage: {{ $data['uptime_percentage'] }}%</li>
            <li>Error Rate: {{ $data['error_rate'] }}%</li>
        </ul>
        @elseif($type == 'user-growth')
        <h2>User Growth Summary</h2>
        <ul>
            <li>New Users: {{ $data['new_users'] }}</li>
            <li>User Growth Rate: {{ number_format($data['user_growth_rate'], 2) }}%</li>
        </ul>

        <h2>Active Users Trend</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Active Users</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['active_users_trend'] as $date => $count)
                <tr>
                    <td>{{ $date }}</td>
                    <td>{{ $count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

    @endif
</body>
</html>