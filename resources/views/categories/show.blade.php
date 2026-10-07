<x-app-layout>

    <h1>{{ $category->name }}</h1>
    <a href="{{ route('categories.edit', $category->id) }}">Edit</a>
    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit">Delete</button>
    </form>
</x-app-layout>
