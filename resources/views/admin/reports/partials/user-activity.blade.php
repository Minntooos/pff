<h2 class="text-2xl font-semibold mb-4">Summary</h2>
<ul class="list-disc list-inside mb-4">
    <li>Total Active Users: {{ $data['total_active_users'] }}</li>
    <li>Average Daily Sessions: {{ number_format($data['average_daily_sessions'], 1) }}</li>
</ul>

<h2 class="text-2xl font-semibold mb-4">Most Active Users</h2>
<table class="w-full mb-4">
    <thead>
        <tr>
            <th class="border px-4 py-2">User</th>
            <th class="border px-4 py-2">Activity Count</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['most_active_users'] as $user)
        <tr>
            <td class="border px-4 py-2">{{ $user->name }}</td>
            <td class="border px-4 py-2">{{ $user->activities_count }}</td>
        </tr>
        @endforeach
    </tbody>
</table>