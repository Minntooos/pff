<h2 class="text-2xl font-semibold mb-4">Summary</h2>
<ul class="list-disc list-inside mb-4">
    <li>Total Resources: {{ $data['total_resources'] }}</li>
</ul>

<h2 class="text-2xl font-semibold mb-4">Most Viewed Resources</h2>
<table class="w-full mb-4">
    <thead>
        <tr>
            <th class="border px-4 py-2">Resource</th>
            <th class="border px-4 py-2">View Count</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['most_viewed_resources'] as $resource)
        <tr>
            <td class="border px-4 py-2">{{ $resource->title }}</td>
            <td class="border px-4 py-2">{{ $resource->activities_count }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<h2 class="text-2xl font-semibold mb-4">Resource Types Distribution</h2>
<table class="w-full mb-4">
    <thead>
        <tr>
            <th class="border px-4 py-2">Type</th>
            <th class="border px-4 py-2">Count</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['resource_types_distribution'] as $type => $count)
        <tr>
            <td class="border px-4 py-2">{{ $type }}</td>
            <td class="border px-4 py-2">{{ $count }}</td>
        </tr>
        @endforeach
    </tbody>
</table>