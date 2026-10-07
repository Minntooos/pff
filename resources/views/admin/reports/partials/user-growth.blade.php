<h2 class="text-2xl font-semibold mb-4">User Growth Summary</h2>
<ul class="list-disc list-inside mb-4">
    <li>New Users: {{ $data['new_users'] }}</li>
    <li>User Growth Rate: {{ number_format($data['user_growth_rate'], 2) }}%</li>
</ul>

<h2 class="text-2xl font-semibold mb-4">Active Users Trend</h2>
<table class="w-full mb-4">
    <thead>
        <tr>
            <th class="border px-4 py-2">Date</th>
            <th class="border px-4 py-2">Active Users</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['active_users_trend'] as $date => $count)
        <tr>
            <td class="border px-4 py-2">{{ $date }}</td>
            <td class="border px-4 py-2">{{ $count }}</td>
        </tr>
        @endforeach
    </tbody>
</table>