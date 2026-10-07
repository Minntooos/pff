<x-resource-layout>
    @php
    $canEdit = $canEdit ?? false;
    $isPrivateAccess = $isPrivateAccess ?? false;
    $tags = $tags ?? [];

    $category = $category ?? null;
    $categories = $categories ?? [];
@endphp
    @if($resource->approval_status === 'approved' || $isPrivateAccess || (Auth::check() && (Auth::id() === $resource->user_id || Auth::user()->usertype === 'admin')))
    
    <style>
        /* ... existing styles ... */
        .file-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .file-info {
            display: flex;
            align-items: center;
        }
        .file-icon {
            font-size: 2rem;
            margin-right: 1rem;
        }
        .remove-file {
            color: #e74c3c;
            cursor: pointer;
        }
        .upload-card {
            border: 2px dashed #3498db;
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }
        .upload-card:hover {
            border-color: #2980b9;
            background-color: #f7f9fa;
        }
        .upload-icon {
            font-size: 3rem;
            color: #3498db;
            margin-bottom: 1rem;
        }
        .file-label {
            cursor: pointer;
            color: #3498db;
            text-decoration: underline;
        }
    </style>
<div class="max-w-4xl mx-auto">
            <div id="file-upload-section" class="mb-4">
            {{-- @if($resource->file_path)
                <div id="file-card" class="file-card">
                    <div class="file-info">
                        <span class="file-icon">📄</span>
                        <span>{{ basename($resource->file_path) }}</span>
                    </div>
                    @if(Auth::id() === $resource->user_id)
                        <span class="remove-file" onclick="removeFile()">❌</span>
                    @endif
                </div>
            @endif --}}
        </div>

        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600">
                <div class="flex items-center justify-between p-4">
                    <div class="flex items-center space-x-4">
                        <img src="{{ $resource->user->profile_picture ? asset('storage/' . $resource->user->profile_picture) : 'https://static-00.iconduck.com/assets.00/avatar-default-symbolic-icon-479x512-n8sg74wg.png' }}" alt="{{ $resource->user->name }} avatar" class="w-12 h-12 rounded-full border-2 border-white">
                        <div class="text-white">
                            <div class="font-bold text-lg">{{ $resource->user->name }}</div>
                            <div class="text-sm opacity-75">Published: {{ $resource->created_at->format('M d, Y') }}</div>
                        </div>                    
                    </div>
                    <div class="text-white text-sm font-medium bg-blue-700 rounded-full px-3 py-1 mr-4">
                        {{ $resource->category->name }}
                    </div>
                </div>
                <div class="relative">
                    <h1 class="text-4xl font-bold text-white mt-6 pr-32 mb-4 ml-4 ">{{ $resource->title }}</h1>
                    @if(Auth::id() === $resource->user_id)
                        <button id="editResourceBtn" class="absolute top-0 right-0 px-4 py-2 mr-4 bg-white text-blue-600 rounded-full hover:bg-blue-100 transition duration-300">
                            <i class="fas fa-edit mr-2"></i>Edit Resource
                        </button>
                    @endif
                    <div class="mt-2 flex flex-wrap gap-2 ml-2">
                        
                        

                        @foreach($resource->tags as $tag)
                            <span class="bg-blue-200 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                {{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                </div>

            {{-- @if($resource->resource_image_path && $resource->type !== 'video' && $resource->type !== 'audio')
                <img src="{{ asset('storage/resource_images/' . $resource->resource_image_path) }}" alt="{{ $resource->title }}" class="w-full h-64 object-cover">

            @endif --}}


            <div class="bg-white w-full ">
                <div class="max-w-4xl mx-auto px-8">
    @if($resource->type === 'image')
        <img src="{{ asset('storage/resources/' . $resource->file_path) }}" alt="{{ $resource->title }}" class="w-full  object-cover rounded-lg shadow-md mb-8 ">
    @elseif($resource->type === 'video')
        <div class="w-full aspect-w-16 aspect-h-9 mb-8">
            <video controls class="w-full h-full rounded-lg shadow-md">
                <source src="{{ asset('storage/resources/' . $resource->file_path) }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    @elseif($resource->type === 'audio')
        <div class="w-full mb-8">
            <audio controls class="w-full">
                <source src="{{ asset('storage/resources/' . $resource->file_path) }}" type="audio/mpeg">
                Your browser does not support the audio element.
            </audio>
        </div>
    @elseif($resource->type === 'document')
    <div class="mb-8">
        <h3 class="text-xl font-semibold mb-2">View Document</h3>
        @if($resource->file_extension === 'pdf')
            <button id="viewPdf" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                Open PDF
            </button>
        @else
            <a href="{{ route('resources.download', $resource) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Download Document
            </a>
        @endif
    </div>
    @endif

    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Description</h2>
    <div class="text-gray-600 mb-8 text-lg">{!! $resource->description !!}</div>
    
    @if($resource->type === 'document' && $resource->file_extension === 'pdf' && $canEdit)
        <div class="mt-4">
            <p class="text-gray-600">As the creator of this resource, you can edit the PDF. However, PDF editing is not implemented in this example.</p>
        </div>
    @endif
</div>

                <div class="bg-gray-50 w-full">
                    <div class="max-w-4xl mx-auto px-8 py-6">
                        <div class="flex justify-between items-center mb-4">
                            <div class="text-sm text-gray-600">
                                <p><i class="far fa-clock mr-2"></i>Posted: {{ $resource->created_at->diffForHumans() }}</p>
                                <p><i class="far fa-comment mr-2"></i>Comments: {{ $resource->comments->count() }}</p>
                            </div>
                            <div class="flex items-center space-x-4">
                                <button id="likeBtn" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition duration-300" @guest onclick="window.location.href='{{ route('login') }}'" @endguest>
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                    </svg>
                                    Like
                                </button>
                                <span id="likeCount" class="text-gray-600 font-semibold">{{ $resource->likes()->count() }} likes</span>
                                <button id="shareBtn" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-full hover:bg-green-700 transition duration-300">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                                    </svg>
                                    Share
                                </button>
                                @if($resource->approval_status === 'pending' && Auth::id() === $resource->user_id)
        <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">Pending Approval</span>
    @endif
                            </div>
                        </div>
                        <input type="text" id="shareLink" class="mt-2 w-full border rounded px-2 py-1 hidden" readonly>
                    </div>
                </div>
            </div>
            @if($resource->approval_status === 'approved')

            <div class="bg-gray-100 w-full">
                <div class="max-w-4xl mx-auto px-8 py-8">
                    <h3 class="text-2xl font-semibold mb-8">Comments</h3>
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        @auth
                            <form action="{{ route('comments.store', $resource->id) }}" method="POST" class="p-6 border-b border-gray-200">
                                @csrf
                                <textarea name="content" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" rows="4" placeholder="Share your thoughts on this resource..."></textarea>
                                <button type="submit" class="mt-2 px-4 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-300">
                                    Post Comment
                                </button>
                            </form>
                        @else
                            <div class="p-6 border-b border-gray-200">
                                <p class="text-gray-700">Please <a href="{{ route('login') }}" class="text-blue-600 hover:underline">log in</a> to post a comment.</p>
                            </div>
                        @endauth
                        <div id="commentsList" class="divide-y-0 divide-gray-200">
                            @foreach($resource->comments()->whereNull('parent_id')->orderBy('created_at', 'desc')->get() as $comment)
                            <div class="p-6" id="comment-{{ $comment->id }}">
                                <div class="flex items-start space-x-3">
                                    <img src="{{ $comment->user->profile_picture ? asset('storage/' . $comment->user->profile_picture) : 'https://static-00.iconduck.com/assets.00/avatar-default-symbolic-icon-479x512-n8sg74wg.png' }}" alt="{{ $comment->user->name }}'s avatar" class="w-10 h-10 rounded-full">
                                    <div class="flex-grow">
                                        <div class="flex justify-between items-center">
                                            <div class="font-semibold text-gray-800">{{ $comment->user->name }}</div>
                                            <div class="relative">
                                                <button onclick="toggleCommentMenu({{ $comment->id }})" class="text-gray-500 hover:text-gray-700">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                                    </svg>
                                                </button>
                                                <div id="commentMenu-{{ $comment->id }}" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 hidden">
                                                    <div class="py-1">
                                                        <button onclick="showReplyForm({{ $comment->id }})" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left">Reply</button>
                                                        @if(Auth::id() === $comment->user_id)
                                                            <button onclick="editComment({{ $comment->id }})" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left">Edit</button>
                                                            <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="block" onsubmit="return confirm('Are you sure you want to delete this comment?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 w-full text-left">Delete</button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-gray-600 mt-1 comment-content">{{ $comment->content }}</p>
                                        <div class="text-xs text-gray-500 mt-1">{{ $comment->created_at->diffForHumans() }}</div>
                                        <form action="{{ route('comments.reply', $comment->id) }}" method="POST" class="reply-form hidden mt-2">
                                            @csrf
                                            <textarea name="content" class="w-full p-2 border rounded" rows="2" placeholder="Write a reply..."></textarea>
                                            <button type="submit" class="mt-1 px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Submit Reply</button>
                                        </form>
                                    </div>
                                </div>
    
                                <div class="ml-12 mt-4 space-y-4 replies">
                                    @if($comment->replies && $comment->replies->count() > 0)
                                        @foreach($comment->replies as $reply)
                                            <div class="bg-gray-50 p-4 rounded-lg" id="comment-{{ $reply->id }}">
                                                <div class="flex items-start space-x-3">
                                                    @auth
                                                    <img src="{{ Auth::check() ? (Auth::user()->usertype === 'admin' ? asset('images/a.jpeg') : (Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('images/default-avatar.png'))) : asset('images/default-avatar.png') }}" alt="{{ Auth::check() ? Auth::user()->name : 'Guest' }} alt="{{ $reply->user->name }}'s avatar" class="w-8 h-8 rounded-full">
                                                    @else
                                                    <a href="{{ route('login') }}" class="text-white hover:text-gray-200 transition duration-300">Login</a>
                                                    @endauth
                                                    <div class="flex-grow">
                                                        <div class="flex justify-between items-center">
                                                            <div class="font-semibold text-gray-800">{{ $reply->user->name }}</div>
                                                            <div class="relative">
                                                                <button onclick="toggleCommentMenu({{ $reply->id }})" class="text-gray-500 hover:text-gray-700">
                                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                                                    </svg>
                                                                </button>
                                                                <div id="commentMenu-{{ $reply->id }}" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 hidden">
                                                                    <div class="py-1">
                                                                        @if(Auth::id() === $reply->user_id)
                                                                            <button onclick="editComment({{ $reply->id }})" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left">Edit</button>
                                                                            <form action="{{ route('comments.destroy', $reply->id) }}" method="POST" class="block" onsubmit="return confirm('Are you sure you want to delete this comment?')">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 w-full text-left">Delete</button>
                                                                            </form>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <p class="text-gray-600 mt-1 comment-content">{{ $reply->content }}</p>
                                                        <div class="text-xs text-gray-500 mt-1">{{ $reply->created_at->diffForHumans() }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                </div>
            </div>
            @endif
        </div>
    </div>
    <div id="pdfModal" class="fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white w-11/12 h-5/6 rounded-lg shadow-xl flex flex-col">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-xl font-semibold">PDF Viewer</h3>
                <div>
                    <button id="savePdfBtn" class="px-4 py-2 bg-green-600 text-white rounded mr-2">Save PDF</button>
                    <button id="printPdfBtn" class="px-4 py-2 bg-blue-600 text-white rounded mr-2">Print PDF</button>
                    <button id="closePdfModal" class="text-black text-2xl">&times;</button>
                </div>
            </div>
            <div id="pspdfkit" class="flex-grow"></div>
        </div>
    </div>
    <div id="printLoading" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-4 rounded-lg">
            <p class="text-lg font-semibold">Preparing PDF for printing...</p>
            <div class="mt-2 w-full bg-gray-200 rounded-full h-2.5">
                <div id="printProgress" class="bg-blue-600 h-2.5 rounded-full" style="width: 0%"></div>
            </div>
        </div>
    </div>
    <div id="editResourceModal" class="fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white w-11/12 max-w-4xl rounded-lg shadow-xl p-6 relative">
            <!-- Close Button -->
            <button id="closeEditResourceModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-500">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <h2 class="text-2xl font-bold mb-4">Edit Resource</h2>
            <form id="edit-resource-form" method="POST" action="{{ route('resources.update', $resource) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- File Upload Section -->
                <div id="file-upload-section" class="mb-4">
                    @if($resource->file_path)
                        <div id="file-card" class="file-card flex items-center justify-between p-4 bg-gray-100 rounded">
                            <div class="file-info flex items-center space-x-2">
                                <span class="file-icon">📄</span>
                                <span>{{ basename($resource->file_path) }}</span>
                            </div>
                            <span class="remove-file cursor-pointer text-red-600" onclick="removeFile()">❌</span>
                        </div>
                    @else
                        <div class="upload-card border-2 border-dashed border-gray-300 p-6 text-center rounded cursor-pointer"
                             id="upload-area" onclick="document.getElementById('file-upload').click();">
                            <i class="fas fa-cloud-upload-alt upload-icon text-4xl text-gray-500"></i>
                            <p class="mt-2 text-gray-600">Click or drag to upload documents</p>
                            <input type="file" id="file-upload" name="file_path" style="display: none;" onchange="handleFileUpload(this.files)">
                        </div>
                    @endif
                </div>

                <!-- Title -->
                <div class="form-group mb-4">
                    <label for="title" class="block text-gray-700 font-bold mb-2">Title</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $resource->title) }}" required
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Description -->
                <div class="form-group mb-4">
                    <label for="description" class="block text-gray-700 font-bold mb-2">Description</label>
                    <input id="description" type="hidden" name="description" value="{{ old('description', $resource->description) }}">
                    <trix-editor input="description" class="trix-content"></trix-editor>
                </div>

                <!-- Resource Type -->
                <div class="form-group mb-4">
                    <label for="type" class="block text-gray-700 font-bold mb-2">Resource Type</label>
                    <select id="type" name="type" required
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="document" {{ old('type', $resource->type) === 'document' ? 'selected' : '' }}>Document</option>
                        <option value="image" {{ old('type', $resource->type) === 'image' ? 'selected' : '' }}>Image</option>
                        <option value="video" {{ old('type', $resource->type) === 'video' ? 'selected' : '' }}>Video</option>
                        <option value="audio" {{ old('type', $resource->type) === 'audio' ? 'selected' : '' }}>Audio</option>
                    </select>
                </div>

                <!-- Category -->
                @if(Auth::check() && Auth::id() === $resource->user_id)
                    <div class="form-group mb-4">
                        <label for="category_id" class="block text-gray-700 font-bold mb-2">Category</label>
                        <select id="category_id" name="category_id" required
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select a category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                        {{ old('category_id', $resource->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Tags -->
                <div class="form-group mb-4">
                    <label for="tags" class="block text-gray-700 font-bold mb-2">Tags</label>
                    <select name="tags[]" id="tags" multiple
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}"
                                    {{ in_array($tag->id, old('tags', $resource->tags->pluck('id')->toArray())) ? 'selected' : '' }}>
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center space-x-4">
                      <input type="radio" id="default-image" name="image-source" value="default" checked class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                      <label for="default-image" class="font-medium text-gray-700">Default image</label>
                      <button type="button" id="browse-icons" class="hidden ml-2 bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-2 rounded text-sm">
                        Browse Icons
                      </button>
                    </div>
                    <div id="default-icon-container" class="hidden mt-4">
                      <i id="default-icon" class="text-6xl"></i>
                      <input type="hidden" id="default-icon-class" name="default_icon" value="">
                    </div>
                    <div id="icon-selection-modal" class="fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex items-center justify-center hidden">
                      <div class="bg-white rounded-lg shadow-xl w-11/12 md:max-w-md mx-auto">
                        <div class="border-b px-4 py-2 flex justify-between items-center">
                          <h3 class="text-lg font-semibold text-gray-900">Select an Icon</h3>
                          <button id="close-icon-modal" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                          </button>
                        </div>
                        <div class="p-4">
                          <div id="icon-grid" class="grid grid-cols-4 sm:grid-cols-6 gap-4"></div>
                        </div>
                      </div>
                    </div>
                    <div class="flex items-center space-x-4">
                      <input type="radio" id="ai-generated" name="image-source" value="ai" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                      <label for="ai-generated" class="font-medium text-gray-700">AI-generated</label>
                    </div>
                    <div class="flex items-center space-x-4">
                      <input type="radio" id="user-upload" name="image-source" value="upload" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                      <label for="user-upload" class="font-medium text-gray-700">Upload image</label>
                    </div>
                 
                    <div class="relative">
                      <textarea id="ai-prompt" placeholder="Describe the image you want to generate..." class="hidden w-full p-2 border rounded mt-2 pr-10"></textarea>
                      <button type="button" id="generate-prompt" class="hidden absolute right-2 top-2 bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-2 rounded text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                      </button>
                      <button type="button" id="generate-ai-image" class="hidden mt-2 bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded flex items-center justify-center">
                        <span id="generate-button-text">Generate AI Image</span>
                        <svg id="loading-spinner" class="hidden animate-spin ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                      </button>
                    </div>
                    <div id="ai-image-container" class="{{ old('image-source', $resource->image_source) === 'ai' ? '' : 'hidden' }} mt-4">
                        <input type="hidden" name="ai_generated_image" id="ai_generated_image" value="{{ $resource->resource_image_path }}">
                        <img id="ai-generated-image" src="{{ $resource->resource_image_path ? asset('storage/resource_images/' . $resource->resource_image_path) : '' }}" alt="AI Generated Image" class="max-w-full h-auto rounded shadow-lg">
                    </div>
                    <label class="block font-medium text-gray-700">Resource Image</label>
                    <div id="resource-image-container" class="hidden">
                      <img id="resource-image-preview" src="" alt="Resource Image" class="max-w-[200px]">
                      <button type="button" id="remove-resource-image" class="mt-2 bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-2 rounded text-sm">Remove Image</button>
                    </div>
                    <div id="resource-image-upload">
                        <input type="file" id="resource-image" name="resource_image_path" accept="image/*" class="hidden" onchange="handleResourceImage(this.files)">
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:bg-gray-50 transition-colors" id="resource-image-drop-area">
                          <div class="text-4xl mb-2">📤</div>
                          <p>Drag and drop your image here, or <label for="resource-image" class="text-blue-500 underline cursor-pointer">browse</label></p>
                        </div>
                      </div>
                  </div>
                  <div class="flex items-center space-x-2">
                    <label for="visibility-toggle" class="font-medium text-gray-700">Visibility:</label>
                    <label class="inline-flex items-center cursor-pointer">
                      <input type="checkbox" id="visibility-toggle" name="visibility" value="1" class="sr-only peer">
                      <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                      <span id="visibility-status" class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">Private</span>
                    </label>
                  </div>

                <!-- Submit Button -->
                <div class="mt-6 flex justify-end">
                    <button type="submit"
                            class="px-6 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                        Update Resource
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.min.js"></script>
    <script src="{{ asset('assets/dist/pspdfkit.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>
<script>
const editResourceBtn = document.getElementById('editResourceBtn');
const editResourceModal = document.getElementById('editResourceModal');
const editResourceForm = document.getElementById('editResourceForm');
const cancelEditBtn = document.getElementById('cancelEditBtn');
const editDefaultImageRadio = document.getElementById('edit_default_image');
const editDefaultIconContainer = document.getElementById('edit_default_icon_container');
const editDefaultIcon = document.getElementById('edit_default_icon');
const editBrowseIconsBtn = document.getElementById('edit_browse_icons');
const editUserUploadRadio = document.getElementById('edit_user_upload');
const editResourceImageContainer = document.getElementById('edit_resource_image_container');
const editResourceImagePreview = document.getElementById('edit_resource_image_preview');
const editRemoveResourceImage = document.getElementById('edit_remove_resource_image');
const editResourceImageUpload = document.getElementById('edit_resource_image_upload');
const editResourceImageDropArea = document.getElementById('edit_resource_image_drop_area');
const editResourceImage = document.getElementById('edit_resource_image');
const form = document.getElementById('edit-resource-form');
        const aiPrompt = document.getElementById('ai-prompt');
        const fileUpload = document.getElementById('file-upload');
        const imageSourceRadios = document.getElementsByName('image-source');
        const tagInput = document.querySelector('.tag-input');
        const newTagInput = document.getElementById('new-tag');
        const uploadArea = document.getElementById('upload-area');
        const defaultImageRadio = document.getElementById('default-image');
        const defaultIconContainer = document.getElementById('default-icon-container');
        const defaultIcon = document.getElementById('default-icon');
        const browseIconsBtn = document.getElementById('browse-icons');
        const iconSelectionModal = document.getElementById('icon-selection-modal');
        const iconGrid = document.getElementById('icon-grid');
        const closeIconModalBtn = document.getElementById('close-icon-modal');
        const generateAiImageBtn = document.getElementById('generate-ai-image');
        const aiImageContainer = document.getElementById('ai-image-container');
const aiGeneratedImage = document.getElementById('ai-generated-image');

        const loadingSpinner = document.getElementById('loading-spinner');
        const generateButtonText = document.getElementById('generate-button-text');
        const generatePromptBtn = document.getElementById('generate-prompt');
        const titleInput = document.getElementById('resource-title');
        const descriptionInput = document.querySelector('trix-editor');

        let uploadedFile = null;
const iconOptions = [
    // Document icons
    { class: 'far fa-file-alt', color: 'text-gray-600', name: 'Generic Document' },
    { class: 'far fa-file-word', color: 'text-blue-500', name: 'Word Document' },
    { class: 'far fa-file-excel', color: 'text-green-500', name: 'Excel Spreadsheet' },
    { class: 'far fa-file-powerpoint', color: 'text-red-500', name: 'PowerPoint' },
    { class: 'far fa-file-pdf', color: 'text-red-600', name: 'PDF' },
    { class: 'fas fa-file-csv', color: 'text-green-600', name: 'CSV' },
    { class: 'far fa-file-code', color: 'text-yellow-500', name: 'Code File' },
    { class: 'fas fa-file-contract', color: 'text-indigo-600', name: 'Contract' },
    { class: 'fas fa-file-signature', color: 'text-purple-600', name: 'Signed Document' },
    { class: 'fas fa-file-invoice', color: 'text-yellow-600', name: 'Invoice' },
    { class: 'fas fa-file-medical', color: 'text-green-500', name: 'Medical Record' },
    { class: 'fas fa-file-prescription', color: 'text-pink-500', name: 'Prescription' },

    // Image icons
    { class: 'far fa-file-image', color: 'text-green-400', name: 'Image File' },
    { class: 'fas fa-camera', color: 'text-blue-400', name: 'Camera' },
    { class: 'fas fa-image', color: 'text-indigo-400', name: 'Picture' },
    { class: 'fas fa-portrait', color: 'text-purple-400', name: 'Portrait' },
    { class: 'fas fa-photo-video', color: 'text-pink-400', name: 'Photo Album' },
    { class: 'fas fa-camera-retro', color: 'text-yellow-500', name: 'Retro Camera' },
    { class: 'fas fa-images', color: 'text-green-500', name: 'Image Gallery' },

    // Video icons
    { class: 'far fa-file-video', color: 'text-blue-400', name: 'Video File' },
    { class: 'fas fa-film', color: 'text-gray-700', name: 'Film' },
    { class: 'fas fa-video', color: 'text-red-500', name: 'Video Camera' },
    { class: 'fas fa-tv', color: 'text-gray-600', name: 'TV' },
    { class: 'fas fa-play-circle', color: 'text-green-500', name: 'Play Video' },
    { class: 'fas fa-movie', color: 'text-indigo-500', name: 'Movie' },
    { class: 'fas fa-video-slash', color: 'text-red-600', name: 'No Video' },

    // Audio icons
    { class: 'fas fa-music', color: 'text-purple-400', name: 'Music' },
    { class: 'fas fa-microphone', color: 'text-red-400', name: 'Microphone' },
    { class: 'fas fa-podcast', color: 'text-purple-500', name: 'Podcast' },
    { class: 'fas fa-headphones', color: 'text-gray-700', name: 'Headphones' },
    { class: 'fas fa-volume-up', color: 'text-blue-500', name: 'Speaker' },
    { class: 'fas fa-record-vinyl', color: 'text-gray-800', name: 'Vinyl Record' },
    { class: 'fas fa-broadcast-tower', color: 'text-red-500', name: 'Radio' },
    { class: 'fas fa-guitar', color: 'text-yellow-600', name: 'Guitar' },
    { class: 'fas fa-drum', color: 'text-purple-600', name: 'Drum' },

    // Additional icons for various purposes
    { class: 'fas fa-book', color: 'text-blue-600', name: 'Book' },
    { class: 'fas fa-newspaper', color: 'text-gray-700', name: 'Newspaper' },
    { class: 'fas fa-chart-bar', color: 'text-indigo-500', name: 'Chart' },
    { class: 'fas fa-map', color: 'text-green-600', name: 'Map' },
    { class: 'fas fa-calendar', color: 'text-red-400', name: 'Calendar' },
    { class: 'fas fa-envelope', color: 'text-blue-400', name: 'Email' },
    { class: 'fas fa-link', color: 'text-gray-500', name: 'Link' },
    { class: 'fas fa-cloud', color: 'text-blue-300', name: 'Cloud' },
];
function removeFile() {
  const fileCard = document.getElementById('file-card');
  if (fileCard) fileCard.remove();
  
  const uploadedFileInput = document.getElementById('uploaded-file');
  if (uploadedFileInput) uploadedFileInput.remove();
  
  showUploadCard();
  
  // Reset the file input
  const fileInput = document.getElementById('file-upload');
  fileInput.value = '';
  
  // Reset the resource type
  const resourceTypeInput = document.getElementById('type');
  resourceTypeInput.value = '';
  
  // If default icon is selected, update it
  if (defaultImageRadio.checked) {
    showDefaultIcon();
  }

  // Clear the stored uploaded file
  uploadedFile = null;
}

function showUploadCard() {
  const uploadArea = document.getElementById('upload-area');
  uploadArea.style.display = 'block';
  
  // Create a new upload card
  const uploadCard = document.createElement('div');
  uploadCard.id = 'upload-card';
  uploadCard.className = 'border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:bg-gray-50 transition-colors';
  uploadCard.innerHTML = `
    <input type="file" id="file-upload" name="file_path" class="hidden" onchange="handleFileUpload(this.files)">
    <div class="text-4xl mb-2">📤</div>
    <p>Drag and drop your file here, or <span class="text-blue-500 underline">browse</span></p>
  `;
  
  uploadArea.innerHTML = '';
  uploadArea.appendChild(uploadCard);
  
  // Add event listeners for drag and drop
  uploadCard.addEventListener('dragover', handleDragOver);
  uploadCard.addEventListener('dragleave', handleDragLeave);
  uploadCard.addEventListener('drop', handleDrop);
  uploadCard.addEventListener('click', () => document.getElementById('file-upload').click());
}
function handleFileUpload(files) {
  if (files.length > 0) {
    const file = files[0];
    uploadedFile = file;
    
    // Create file card
    const fileCard = document.createElement('div');
    fileCard.id = 'file-card';
    fileCard.className = 'file-card flex items-center justify-between p-4 bg-gray-100 rounded';
    fileCard.innerHTML = `
      <div class="file-info flex items-center space-x-2">
        <span class="file-icon">📄</span>
        <span>${file.name}</span>
      </div>
      <span class="remove-file cursor-pointer text-red-600" onclick="removeFile()">❌</span>
    `;
    
    const uploadArea = document.getElementById('upload-area');
    uploadArea.innerHTML = '';
    uploadArea.appendChild(fileCard);
    
    // Update resource type
    updateResourceType(file.type);
    
    // Create hidden input for the uploaded file
    const uploadedFileInput = document.createElement('input');
    uploadedFileInput.type = 'hidden';
    uploadedFileInput.id = 'uploaded-file';
    uploadedFileInput.name = 'uploaded_file';
    uploadedFileInput.value = file.name;
    uploadArea.appendChild(uploadedFileInput);
  }
}

function updateResourceType(fileType) {
  const resourceTypeInput = document.getElementById('type');
  if (fileType.startsWith('image/')) {
    resourceTypeInput.value = 'image';
  } else if (fileType.startsWith('video/')) {
    resourceTypeInput.value = 'video';
  } else if (fileType.startsWith('audio/')) {
    resourceTypeInput.value = 'audio';
  } else {
    resourceTypeInput.value = 'document';
  }
}
document.addEventListener('DOMContentLoaded', function() {

    
if (editResourceBtn) {
    editResourceBtn.addEventListener('click', function() {
        editResourceModal.classList.remove('hidden');
    });
}

window.toggleCommentMenu = function(commentId) {
    const menu = document.getElementById(`commentMenu-${commentId}`);
    if (menu) {
        menu.classList.toggle('hidden');
    }
};
    const editBtn = document.getElementById('editResourceBtn');
    const editModal = document.getElementById('editResourceModal');
    const closeModalBtn = document.getElementById('closeEditResourceModal');
    const form = document.getElementById('edit-resource-form');
    const imageSourceRadios = document.querySelectorAll('input[name="image-source"]');
    const defaultIconContainer = document.getElementById('default-icon-container');
    const aiPrompt = document.getElementById('ai-prompt');
    const generateAiImageBtn = document.getElementById('generate-ai-image');
    const aiImageContainer = document.getElementById('ai-image-container');
    const resourceImageUpload = document.getElementById('resource-image-upload');
    const visibilityToggle = document.getElementById('visibility-toggle');
    const visibilityStatus = document.getElementById('visibility-status');

    // Function to show selected image option
    function showSelectedImageOption(value) {
        defaultIconContainer.classList.add('hidden');
        aiPrompt.classList.add('hidden');
        generateAiImageBtn.classList.add('hidden');
        aiImageContainer.classList.add('hidden');
        resourceImageUpload.classList.add('hidden');

        if (value === 'default') {
            defaultIconContainer.classList.remove('hidden');
        } else if (value === 'ai') {
            aiPrompt.classList.remove('hidden');
            generateAiImageBtn.classList.remove('hidden');
            aiImageContainer.classList.remove('hidden');
        }else if (value === 'upload') {
            resourceImageUpload.classList.remove('hidden');
        }
    }
    function handleResourceImage(file) {
  if (file && (file.type.startsWith('image/') || file.type.startsWith('video/'))) {
    if (file.type.startsWith('video/')) {
      const videoElement = document.createElement('video');
      videoElement.controls = true;
      videoElement.id = "videopreview";
      videoElement.style.maxWidth = '600px';
      videoElement.style.borderRadius = '8px';
      videoElement.style.boxShadow = '0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24)';
      videoElement.src = URL.createObjectURL(file);
      resourceImageContainer.innerHTML = '';
      resourceImageContainer.appendChild(videoElement);
      resourceImageContainer.style.display = 'block';
      uploadCard.style.display = 'none';
    } else {
      const reader = new FileReader();
      reader.onload = (e) => {
        resourceImagePreview.src = e.target.result;
        resourceImagePreview.style.display = 'block';
        resourceImageContainer.style.display = 'block';
        uploadCard.style.display = 'none';
      };
      reader.readAsDataURL(file);
    }
    
    // Clear any previously generated AI image
    aiGeneratedImage.src = '';
    aiImageContainer.style.display = 'none';
  } else {
    console.log('File type not supported for preview');
    uploadCard.style.display = 'block';
    resourceImageContainer.style.display = 'none';
  }
}
    // Open the modal
    @if(Auth::check())
    editBtn.addEventListener('click', () => {
        editModal.classList.remove('hidden');
    });
    @endif


    // Close the modal
    closeModalBtn.addEventListener('click', () => {
        editModal.classList.add('hidden');
    });

    // Close modal when clicking outside the content
    window.addEventListener('click', (event) => {
        if (event.target == editModal) {
            editModal.classList.add('hidden');
        }
    });

    // Toggle image source sections
    imageSourceRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            showSelectedImageOption(this.value);
        });
    });

    // Initialize the form
    const defaultSelected = document.querySelector('input[name="image-source"]:checked');
    if (defaultSelected) {
        showSelectedImageOption(defaultSelected.value);
    }

    // Handle visibility toggle
    visibilityToggle.addEventListener('change', function() {
        visibilityStatus.textContent = this.checked ? 'Public' : 'Private';
    });

    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        // Add the selected image source
        const selectedImageSource = document.querySelector('input[name="image-source"]:checked');
        if (selectedImageSource) {
            formData.append('image_source', selectedImageSource.value);
        }

        // If AI-generated image is selected, add the image data
        if (selectedImageSource && selectedImageSource.value === 'ai') {
            const aiGeneratedImage = document.getElementById('ai-generated-image');
            if (aiGeneratedImage.src) {
                fetch(aiGeneratedImage.src)
                    .then(res => res.blob())
                    .then(blob => {
                        formData.append('ai_generated_image', blob, 'ai_generated_image.png');
                        submitFormData(formData);
                    });
            } else {
                submitFormData(formData);
            }
        } else {
            submitFormData(formData);
        }
    });

    function submitFormData(formData) {
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text()) // Change to .text() to log the raw response
    .then(data => {
        console.log('Raw response:', data); // Log the raw response
        try {
            const jsonData = JSON.parse(data); // Try to parse the response as JSON
            if (jsonData.success) {
                alert(jsonData.message);
                window.location.reload(); // Reload the page to show updated resource
            } else {
                alert(jsonData.message || 'An error occurred while updating the resource.');
            }
        } catch (error) {
            console.error('Error parsing JSON:', error);
            alert('An error occurred while updating the resource. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the resource. Please try again.');
    });
}

    // AI Image Generation (placeholder - replace with actual AI integration)
    generateAiImageBtn.addEventListener('click', async function() {
    const prompt = aiPrompt.value;
    if (prompt) {
        generateButtonText.textContent = 'Generating...';
        loadingSpinner.classList.remove('hidden');

        try {
            const response = await fetch("https://api-inference.huggingface.co/models/black-forest-labs/FLUX.1-schnell", {
                headers: { Authorization: "Bearer {{ config('services.hf.key') }}" },
                method: "POST",
                body: JSON.stringify({ "inputs": prompt }),
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const blob = await response.blob();
            const aiImageUrl = URL.createObjectURL(blob);
            aiGeneratedImage.src = aiImageUrl;
            aiImageContainer.classList.remove('hidden');

            // Convert blob to base64
            const reader = new FileReader();
            reader.readAsDataURL(blob);
            reader.onloadend = function() {
                const base64data = reader.result;
                document.getElementById('ai_generated_image').value = base64data;
                console.log('AI image data set:', base64data.substring(0, 50) + '...'); // Log first 50 characters
            }

            // Set the image source to AI
            document.querySelector('input[name="image_source"][value="ai"]').checked = true;

        } catch (error) {
            console.error('Error generating AI image:', error);
            alert('An error occurred while generating the AI image. Please try again.');
        } finally {
            generateButtonText.textContent = 'Generate AI Image';
            loadingSpinner.classList.add('hidden');
        }
    } else {
        alert('Please enter a prompt for the AI image.');
    }
});

// Modify your form submit event listener
document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent the default form submission
    console.log('Form submitted');
    console.log('Image source:', document.querySelector('input[name="image_source"]:checked').value);
    console.log('AI image data:', document.getElementById('ai_generated_image').value.substring(0, 50) + '...');

    // Create FormData object
    const formData = new FormData(this);
// If AI-generated image is selected, add the image data
if (formData.get('image_source') === 'ai') {
        const aiGeneratedImage = document.getElementById('ai_generated_image').value;
        if (aiGeneratedImage) {
            formData.set('ai_generated_image', aiGeneratedImage);
            console.log('AI image data set:', aiGeneratedImage.substring(0, 50) + '...');
        }
    }
    // Send AJAX request
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Resource updated successfully');
            window.location.reload(); // Reload the page to show updated resource
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the resource');
    });
});
// Add this to your form submit event listener
document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent the default form submission
    console.log('Form submitted');
    console.log('Image source:', document.querySelector('input[name="image_source"]:checked').value);
    console.log('AI image data:', document.getElementById('ai_generated_image').value.substring(0, 50) + '...');

    // Create FormData object
    const formData = new FormData(this);

    // Send AJAX request
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Resource updated successfully');
            window.location.reload(); // Reload the page to show updated resource
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the resource');
    });
});

    // Handle file upload
    const resourceImage = document.getElementById('resource-image');
    const resourceImagePreview = document.getElementById('resource-image-preview');
    const resourceImageContainer = document.getElementById('resource-image-container');
    const removeResourceImageBtn = document.getElementById('remove-resource-image');

    resourceImage.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            resourceImagePreview.src = e.target.result;
            resourceImageContainer.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
        
        // Set the image source to upload
        document.querySelector('input[name="image_source"][value="upload"]').checked = true;
    }
});


    removeResourceImageBtn.addEventListener('click', function() {
        resourceImage.value = '';
        resourceImagePreview.src = '';
        resourceImageContainer.classList.add('hidden');
    });

    // Initialize the form on page load
    window.addEventListener('load', () => {
        const defaultSelected = document.querySelector('input[name="image-source"]:checked');
        if (defaultSelected) {
            showSelectedImageOption(defaultSelected.value);
        }
    });


window.showReplyForm = function(commentId) {
    const replyForm = document.querySelector(`#comment-${commentId} .reply-form`);
    const menu = document.getElementById(`commentMenu-${commentId}`);
    if (replyForm) {
        replyForm.classList.toggle('hidden');
    }
    if (menu) {
        menu.classList.add('hidden');
    }
};

window.editComment = function(commentId) {
    const commentElement = document.querySelector(`#comment-${commentId} .comment-content`);
    const menu = document.getElementById(`commentMenu-${commentId}`);
    if (!commentElement) return;

    const currentContent = commentElement.textContent.trim();
    
    const textarea = document.createElement('textarea');
    textarea.value = currentContent;
    textarea.classList.add('w-full', 'p-2', 'border', 'rounded', 'mt-2');
    
    const saveButton = document.createElement('button');
    saveButton.textContent = 'Save';
    saveButton.classList.add('mt-2', 'px-3', 'py-1', 'bg-blue-600', 'text-white', 'rounded', 'hover:bg-blue-700');
    
    saveButton.addEventListener('click', function() {
        const newContent = textarea.value.trim();
        if (newContent !== currentContent) {
            fetch(`/comments/${commentId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ content: newContent })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    commentElement.textContent = data.content;
                    if (data.edited) {
                        const editedSpan = document.createElement('span');
                        editedSpan.textContent = ' (edited)';
                        editedSpan.classList.add('text-xs', 'text-gray-500', 'ml-1');
                        commentElement.appendChild(editedSpan);
                    }
                    textarea.remove();
                    saveButton.remove();
                    commentElement.style.display = '';
                }
            });
        } else {
            textarea.remove();
            saveButton.remove();
            commentElement.style.display = '';
        }
    });
    
    commentElement.parentNode.insertBefore(textarea, commentElement.nextSibling);
    textarea.parentNode.insertBefore(saveButton, textarea.nextSibling);
    commentElement.style.display = 'none';
    
    if (menu) {
        menu.classList.add('hidden');
    }
};
// resources/views/resources/show.blade.php

document.getElementById('shareBtn').addEventListener('click', function() {
    fetch('{{ route("resources.generate-private-link", $resource->id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
    })
    .then(response => response.json())
    .then(data => {
        const shareLinkInput = document.getElementById('shareLink');
        // Access the correct key based on controller's response
        shareLinkInput.value = data.private_link; // Changed from data.private_url
        shareLinkInput.classList.remove('hidden');
        shareLinkInput.select();
        navigator.clipboard.writeText(shareLinkInput.value).then(() => {
            alert('Private share link copied to clipboard!');
        }).catch(err => {
            console.error('Failed to copy: ', err);
            alert('Failed to copy link. Please copy it manually.');
        });
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to generate private link. Please try again.');
    });
});

    

// Initialize the form
window.addEventListener('load', () => {
    const defaultSelected = document.querySelector('input[name="image-source"]:checked');
    if (defaultSelected) {
        showSelectedImageOption(defaultSelected.value);
    }

    // If there's an existing file, set it as the uploadedFile
    const existingFileCard = document.getElementById('file-card');
    if (existingFileCard) {
        const fileName = existingFileCard.querySelector('.file-info span:last-child').textContent;
        uploadedFile = new File([""], fileName, { type: "application/octet-stream" });
    }
});
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.worker.min.js';
        const likeBtn = document.getElementById('likeBtn');
        const likeCount = document.getElementById('likeCount');
        const commentForm = document.getElementById('commentForm');
        const commentsList = document.getElementById('commentsList');
    if (imageSourceRadios.length > 0) {
        const selectedImageSource = document.querySelector('input[name="image-source"]:checked');
        if (selectedImageSource) {
            showSelectedImageOption(selectedImageSource.value);
        }
        
        imageSourceRadios.forEach(radio => {
            radio.addEventListener('change', (e) => {
                showSelectedImageOption(e.target.value);
            });
        });
    }


        likeBtn.addEventListener('click', function() {
            @guest
        window.location.href = '{{ route('login') }}';
        return;
    @endguest
    fetch(`/resources/{{ $resource->id }}/like`, {
           method: 'POST',
           headers: {
               'Content-Type': 'application/json',
               'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
               'X-Requested-With': 'XMLHttpRequest'
           },
           body: JSON.stringify({})
       })
       .then(response => response.json())
       .then(data => {
           if (data.success) {
               // Update like button UI
               likeCount.textContent = `${data.likes_count} likes`;
               this.classList.toggle('text-red-600', data.liked);
           } else {
               alert(data.error || 'An error occurred while liking the resource.');
           }
       })
       .catch(error => {
           console.error('Error:', error);
           alert('An error occurred while processing your request.');
       });
   });

        // commentForm.addEventListener('submit', function(e) {
        //     e.preventDefault();
        //     const content = this.querySelector('textarea').value;
        //     fetch('{{ route("comments.store", $resource->id) }}', {
        //         method: 'POST',
        //         headers: {
        //             'Content-Type': 'application/json',
        //             'X-CSRF-TOKEN': '{{ csrf_token() }}'
        //         },
        //         body: JSON.stringify({ content: content })
        //     })
        //     .then(response => response.json())
        //     .then(data => {
        //         if (data.success) {
        //             const newComment = document.createElement('div');
        //             newComment.className = 'bg-white p-4 rounded-lg shadow';
        //             newComment.innerHTML = `
        //                 <div class="flex items-start space-x-3">
        //                     <img src="https://i.pravatar.cc/150?img=${data.user_id}" alt="${data.user_name}'s avatar" class="w-10 h-10 rounded-full">
        //                     <div>
        //                         <div class="font-semibold">${data.user_name}</div>
        //                         <p class="text-gray-600 mt-1">${data.content}</p>
        //                     </div>
        //                 </div>
        //             `;
        //             commentsList.prepend(newComment);
        //             this.querySelector('textarea').value = '';
        //         }
        //     })
        //     .catch(error => console.error('Error:', error));
        // });
        
        @if($resource->type === 'document' && $resource->file_extension === 'pdf')
const viewPdfBtn = document.getElementById('viewPdf');
const pdfModal = document.getElementById('pdfModal');
const closePdfModal = document.getElementById('closePdfModal');
const savePdfBtn = document.getElementById('savePdfBtn');
const printPdfBtn = document.getElementById('printPdfBtn');
let instance = null;

viewPdfBtn.addEventListener('click', function() {
    pdfModal.classList.remove('hidden');
    loadPdf();
});

closePdfModal.addEventListener('click', function() {
    pdfModal.classList.add('hidden');
    if (instance) {
        instance.destroy();
    }
});
function submitForm(formData) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

fetch(form.action, {
    method: 'POST',
    body: formData,
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
        // Don't set Content-Type header here, let the browser set it with the correct boundary for FormData
    },
    credentials: 'same-origin' // This ensures cookies are sent with the request
})
.then(response => {
    if (!response.ok) {
        throw new Error('Network response was not ok');
    }
    return response.json();
})
.then(data => {
    if (data.success) {
        alert(data.message);
        window.location.href = data.redirect;
    } else {
        alert(data.message || 'An error occurred while updating the resource.');
    }
})
.catch(error => {
    console.error('Error:', error);
    alert('An error occurred while updating the resource. Please try again.');
});
}
savePdfBtn.addEventListener('click', function() {
    if (instance) {
        instance.exportPDF().then((arrayBuffer) => {
            const blob = new Blob([arrayBuffer], { type: 'application/pdf' });
            const formData = new FormData();
            formData.append('pdf', blob, 'edited_document.pdf');

            fetch('{{ route("resources.save-pdf", $resource->id) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to save PDF');
            });
        });
    }
});


printPdfBtn.addEventListener('click', function() {
    if (instance) {
        instance.print();
    }
});

function loadPdf() {
    PSPDFKit.load({
        container: "#pspdfkit",
        document: "{{ route('resources.view', $resource->id) }}",
        toolbarItems: [
            { type: "content-editor" },
            { type: "export-pdf" },
            { type: "print" }
        ]
    })
    .then(function(loadedInstance) {
        instance = loadedInstance;
        console.log("PSPDFKit loaded", instance);
    })
    .catch(function(error) {
        console.error(error.message);
    });
}

@endif
});
    </script>
    @else
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-4">Resource Not Available</h1>
            <p class="text-gray-600">This resource is currently pending approval and is not available for viewing.</p>
        </div>
    </div>
@endif
</x-resource-layout>