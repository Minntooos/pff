<x-admin-layout>
    <x-slot name="header">
        {{ ucfirst($type) }} Report Preview
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-4">{{ ucfirst($type) }} Report Preview</h1>
        <p class="mb-4">Generated on {{ now()->format('F d, Y H:i:s') }}</p>

        @if($type == 'user-activity')
            @include('admin.reports.partials.user-activity')
        @elseif($type == 'resource-utilization')
            @include('admin.reports.partials.resource-utilization')
        @elseif($type == 'system-performance')
            @include('admin.reports.partials.system-performance')
        @elseif($type == 'user-growth')
            @include('admin.reports.partials.user-growth')
        @endif

        <div class="mt-8">
            <a href="{{ route('admin.reports.download', $type) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Download PDF
            </a>
        </div>
    </div>
</x-admin-layout>