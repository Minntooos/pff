<x-app-layout>
    <div class="container mx-auto mt-8">
        <h1 class="text-2xl font-bold mb-6">Edit Resource</h1>

        <form action="{{ route('resources.update', $resource->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="title" class="block text-gray-700 font-bold mb-2">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $resource->title) }}" class="w-full px-3 py-2 border rounded-lg" required>
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-bold mb-2">Description</label>
                <textarea name="description" id="description" rows="4" class="w-full px-3 py-2 border rounded-lg" required>{{ old('description', $resource->description) }}</textarea>
            </div>

            <div class="mb-4">
                <label for="category_id" class="block text-gray-700 font-bold mb-2">Category</label>
                <select name="category_id" id="category_id" class="w-full px-3 py-2 border rounded-lg" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $resource->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="tags" class="block text-gray-700 font-bold mb-2">Tags</label>
                <select name="tags[]" id="tags" class="w-full px-3 py-2 border rounded-lg" multiple>
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', $resource->tags->pluck('id')->toArray())) ? 'selected' : '' }}>
                            {{ $tag->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Image Source</label>
                <div class="flex items-center space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="image_source" value="upload" {{ old('image_source', 'upload') === 'upload' ? 'checked' : '' }} class="form-radio">
                        <span class="ml-2">Upload Image</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="image_source" value="ai" {{ old('image_source') === 'ai' ? 'checked' : '' }} class="form-radio">
                        <span class="ml-2">AI Generated Image</span>
                    </label>
                </div>
            </div>

            <div id="upload-section" class="mb-4 {{ old('image_source', 'upload') === 'upload' ? '' : 'hidden' }}">
                <label for="resource_image" class="block text-gray-700 font-bold mb-2">Upload Image</label>
                <input type="file" name="resource_image" id="resource_image" class="w-full px-3 py-2 border rounded-lg">
                @if($resource->resource_image_path)
                    <img src="{{ asset('storage/resources/' . $resource->resource_image_path) }}" alt="Current Image" class="mt-2 w-32 h-32 object-cover rounded">
                @endif
            </div>

            <div id="ai-image-section" class="mb-4 {{ old('image_source') === 'ai' ? '' : 'hidden' }}">
                <label for="ai_prompt" class="block text-gray-700 font-bold mb-2">AI Image Prompt</label>
                <input type="text" name="ai_prompt" id="ai_prompt" value="{{ old('ai_prompt') }}" class="w-full px-3 py-2 border rounded-lg">
                @if($resource->resource_image_path)
                    <img src="{{ asset('storage/resources/' . $resource->resource_image_path) }}" alt="AI Generated Image" class="mt-2 w-32 h-32 object-cover rounded">
                @endif
            </div>

            <div class="mb-4">
                <label for="visibility" class="inline-flex items-center">
                    <input type="checkbox" name="visibility" id="visibility" class="form-checkbox" {{ old('visibility', $resource->is_public) ? 'checked' : '' }}>
                    <span class="ml-2">Make this resource public</span>
                </label>
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Update Resource</button>
                <a href="{{ route('resources.show', $resource) }}" class="ml-2 text-gray-600 hover:underline">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageSourceRadios = document.querySelectorAll('input[name="image_source"]');
            imageSourceRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'upload') {
                        document.getElementById('upload-section').classList.remove('hidden');
                        document.getElementById('ai-image-section').classList.add('hidden');
                    } else if (this.value === 'ai') {
                        document.getElementById('ai-image-section').classList.remove('hidden');
                        document.getElementById('upload-section').classList.add('hidden');
                    }
                });
            });
        });
    </script>
</x-app-layout>