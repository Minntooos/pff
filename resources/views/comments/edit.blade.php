<x-app-layout>



<div class="container">
    <h1>Edit Comment</h1>
    <form action="{{ route('comments.update', $comment) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="content">Comment</label>
            <textarea name="content" id="content" class="form-control" rows="3" required>{{ $comment->content }} </textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Comment</button>
    </form>
</div>
</x-app-layout>