<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <img src="{{ asset('images/tags.jpeg') }}" alt="Colorful tags" class="w-16 h-16 mr-4">
                <h1 class="text-3xl font-bold text-gray-800">Tags</h1>
            </div>
            <a href="{{ route('tags.create') }}" class="bg-teal-500 hover:bg-teal-600 text-white font-bold py-2 px-4 rounded transition duration-300">
                <i class="fas fa-plus-circle mr-2"></i>Add New Tag
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if ($tags->isEmpty())
            <p class="text-gray-600 text-center py-8">No tags found. Start by adding a new tag!</p>
        @else
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <ul class="divide-y divide-gray-200">
                    @foreach ($tags as $tag)
                        <li class="p-4 hover:bg-gray-50 transition duration-150">
                            <div class="flex items-center justify-between">
                                <a href="{{ route('tags.show', $tag->id) }}" class="text-lg text-indigo-600 hover:text-indigo-800 font-medium">
                                    {{ $tag->name }}
                                </a>
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('tags.edit', $tag->id) }}" class="text-yellow-500 hover:text-yellow-600" title="Edit">
                                        <i class="fas fa-edit"></i><span class="sr-only">Edit</span>
                                    </a>
                                    <form action="{{ route('tags.destroy', $tag->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this tag?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-600" title="Delete">
                                            <i class="fas fa-trash-alt"></i><span class="sr-only">Delete</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</x-app-layout>