<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <img src="{{ asset('images/categories.jpeg') }}" alt="Categories organizer" class="w-20 h-20 mr-4">
                <h1 class="text-3xl font-bold text-gray-800">Categories</h1>
            </div>
            <a href="{{ route('categories.create') }}" class="bg-teal-500 hover:bg-teal-600 text-white font-bold py-2 px-4 rounded transition duration-300">
                <i class="fas fa-plus-circle mr-2"></i>Add New Category
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if ($categories->isEmpty())
            <p class="text-gray-600">No categories found. Create your first category!</p>
        @else
            <ul class="bg-white shadow-md rounded-lg divide-y divide-gray-200">
                @foreach ($categories as $category)
                    <li class="flex items-center justify-between p-4 hover:bg-gray-50">
                        <a href="{{ route('categories.show', $category->id) }}" class="text-lg text-blue-600 hover:underline">{{ $category->name }}</a>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('categories.edit', $category->id) }}" class="text-yellow-500 hover:text-yellow-600">
                                <i class="fas fa-edit"></i><span class="sr-only">Edit</span>
                            </a>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-600">
                                    <i class="fas fa-trash"></i><span class="sr-only">Delete</span>
                                </button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</x-app-layout>