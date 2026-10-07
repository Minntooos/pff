<x-app-layout>



    <h1 class="text-2xl font-semibold mb-4">Trash</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-4 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($trashedResources->count() > 0)
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Title</th>
                    <th class="py-2 px-4 border-b">Category</th>
                    <th class="py-2 px-4 border-b">Type</th>
                    <th class="py-2 px-4 border-b">Deleted At</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($trashedResources as $resource)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $resource->title }}</td>
                        <td class="py-2 px-4 border-b">{{ $resource->category->name }}</td>
                        <td class="py-2 px-4 border-b">{{ ucfirst($resource->type) }}</td>
                        <td class="py-2 px-4 border-b">{{ $resource->deleted_at->format('Y-m-d H:i') }}</td>
                        <td class="py-2 px-4 border-b">
                            <form action="{{ route('trash.restore', $resource->id) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-800 mr-2">Restore</button>
                            </form>
                            <form action="{{ route('trash.forceDelete', $resource->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to permanently delete this resource?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Delete Permanently</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $trashedResources->links() }}
        </div>
    @else
        <p class="text-gray-700">No items in trash.</p>
    @endif

    
</x-app-layout>