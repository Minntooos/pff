<x-app-layout>
    <h1>{{ $tag->name }}</h1>
    <a href="{{ route('tags.edit', $tag->id) }}">Edit</a>
    <form action="{{ route('tags.destroy', $tag->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit">Delete</button>
    </form>
</x-app-layout>