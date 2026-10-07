
<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>
    <script src="https://requirejs.org/docs/release/2.3.5/minified/require.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
        }

        .header {
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: transform 0.3s ease-in-out;
        }

        .hero {
            padding-top: 5rem; /* Adjusted to account for the header height */
        }

        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        .hero-content p {
            font-size: 1.25rem;
            font-weight: 300;
            color: #34495e;
            margin-bottom: 2rem;
        }

        .cta-button {
            font-size: 1.125rem;
            font-weight: 600;
            padding: 0.75rem 2rem;
            background-color: #e74c3c;
            color: #ffffff;
            border-radius: 50px;
            transition: background-color 0.3s ease;
        }
        .nav-link:hover span {
        transform: scaleX(1);
    }
   
        
    </style>
</head>
<header class="header fixed w-full z-20 transition-all duration-300" id="header">
    <div class="container mx-auto flex justify-between items-center p-4 bg-gradient-to-r from-gray-800 to-gray-700 text-white">
        <a href="{{url('/')}}" class="logo flex items-center text-white text-xl font-bold">
            <img src="{{asset('images/logo.png')}}" alt="TrackSave Logo" class="h-8 mr-2">
            TrackSave
        </a>
        <nav class="flex gap-4">
            @auth
                <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded hover:bg-orange-500 transition duration-300">Dashboard</a>
            @else
                <a href="{{ url('/') }}" class="px-4 py-2 rounded hover:bg-orange-500 transition duration-300">Home</a>
            @endauth
            <a href="{{ route('resources.index') }}" class="px-4 py-2 rounded hover:bg-orange-500 transition duration-300">Resources</a>
            <a href="{{ route('contact') }}" class="px-4 py-2 rounded hover:bg-orange-500 transition duration-300">Contact Us</a>
            <a href="{{ route('terms') }}" class="px-4 py-2 rounded hover:bg-orange-500 transition duration-300">Terms of Service</a>
        </nav>
        @auth
           <div class="user-profile flex items-center gap-2 cursor-pointer">
    <div class="user-avatar w-8 h-8 rounded-full bg-gray-500 overflow-hidden">
        <img src="{{ Auth::check() ? (Auth::user()->usertype === 'admin' ? asset('images/a.jpeg') : (Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('images/default-avatar.png'))) : asset('images/default-avatar.png') }}" alt="{{ Auth::check() ? Auth::user()->name : 'Guest' }}" class="w-full h-full object-cover">
    </div>
    <span>{{ Auth::user()->name }}</span>
</div>
@else
<div class="flex gap-4">
    <a href="{{ route('login') }}" class="px-4 py-2 rounded hover:bg-orange-500 transition duration-300">Login</a>
    <a href="{{ route('register') }}" class="px-4 py-2 rounded hover:bg-orange-500 transition duration-300">Sign Up</a>
</div>
@endauth
    </div>
</header>
<body class="bg-gray-100 text-gray-800">
   

    <div class="container mx-auto py-20 px-4">
        <h1 class="text-3xl font-bold mb-8">Public Resources</h1>

        <form id="search-filter-form" class="mb-6">
          <div class="flex items-center mb-4">
              <input type="text" name="search" placeholder="Search resources..." class="flex-grow p-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              <button type="submit" class="bg-orange-500 text-white p-2 rounded-r-md hover:bg-red-600 transition duration-300">Search</button>
          </div>
      
          <div class="flex flex-wrap gap-4">
              <select name="category" class="p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                  <option value="">All Categories</option>
                  @foreach($categories as $category)
                      <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                  @endforeach
              </select>
              <select name="type" class="p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                  <option value="">All Types</option>
                  <option value="audio" {{ request('type') == 'audio' ? 'selected' : '' }}>Audio</option>
                  <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Video</option>
                  <option value="document" {{ request('type') == 'document' ? 'selected' : '' }}>Document</option>
                  <option value="image" {{ request('type') == 'image' ? 'selected' : '' }}>Image</option>
              </select>
              <select name="sort" class="p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Sort By</option>
                <option value="date" {{ request('sort') == 'date' ? 'selected' : '' }}>Date Added</option>
                <option value="popularity" {{ request('sort') == 'popularity' ? 'selected' : '' }}>Most Liked</option>
            </select>
          </div>
      </form>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @foreach($resources as $resource)
            @if($resource->is_public && $resource->approval_status === 'approved')
            <div class="bg-white rounded-lg shadow-md overflow-hidden transition duration-300 hover:shadow-lg hover:-translate-y-1">
                <div class="relative h-40">
                    @if($resource->resource_image_path)
                        <img src="{{ asset('storage/resource_images/' . $resource->resource_image_path) }}" alt="{{ $resource->title }}" class="w-full h-full object-cover">
                    @else
                        @php
                        $iconClass = match ($resource->type) {
                            'document' => match (strtolower(pathinfo($resource->file_path, PATHINFO_EXTENSION))) {
                                'pdf' => 'bg-red-600 fas fa-file-pdf',
                                'doc', 'docx' => 'bg-blue-600 fas fa-file-word',
                                'xls', 'xlsx' => 'bg-green-600 fas fa-file-excel',
                                'ppt', 'pptx' => 'bg-orange-600 fas fa-file-powerpoint',
                                'txt' => 'bg-gray-600 fas fa-file-alt',
                                default => 'bg-indigo-700 fas fa-file',
                            },
                            'image' => 'bg-purple-600 fas fa-image',
                            'video' => 'bg-pink-600 fas fa-video',
                            'audio' => 'bg-yellow-600 fas fa-music',
                            default => 'bg-gray-600 fas fa-file',
                        };
                        $bgClass = explode(' ', $iconClass)[0];
                        $faClass = explode(' ', $iconClass)[1] . ' ' . explode(' ', $iconClass)[2];
                        @endphp
                        <div class="flex items-center justify-center h-full w-full {{ $bgClass }}">
                            <i class="{{ $faClass }} text-white text-4xl"></i>
                        </div>
                    @endif
                    @if($resource->type === 'video')
                        <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50">
                            <i class="fas fa-play-circle text-white text-4xl"></i>
                        </div>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-semibold mb-2">{{ $resource->title }}</h3>
                    <p class="text-gray-600 text-sm mb-4">{!! Str::limit($resource->description, 80) !!}</p>
                    <div class="flex justify-between items-center text-xs text-gray-500">
                        <span>{{ $resource->type }}</span>
                        <a href="{{ route('resources.show', $resource) }}" class="text-blue-500 hover:underline">View More</a>
                    </div>
                </div>
            </div>
            @endif
        @endforeach
    </div>
        <div class="mt-6">
            {{ $resources->links() }}
        </div>
    </div>

    <footer class="footer bg-gradient-to-r from-gray-800 to-gray-700 text-gray p-4">
        <div class="container mx-auto flex justify-between items-center">
            <div>© 2024 TrackSave. All rights reserved.</div>
            <div>
                <a href="#" class="hover:text-orange-500 transition duration-300">Contact Us</a>
                <a href="#" class="hover:text-orange-500 transition duration-300 ml-4">Privacy Policy</a>
                <a href="#" class="hover:text-orange-500 transition duration-300 ml-4">Terms of Service</a>
            </div>
            <div class="social-icons flex gap-4">
                <a href="#" class="hover:text-orange-500 transition duration-300">📘</a>
                <a href="#" class="hover:text-orange-500 transition duration-300">🐦</a>
                <a href="#" class="hover:text-orange-500 transition duration-300"></a>
            </div>
        </div>
    </footer>

    <script>
        // Header scroll effect
        const header = document.getElementById('header');
        let lastScrollTop = 0;

        window.addEventListener('scroll', () => {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            if (scrollTop > 50) {
                header.classList.add('scrolled', 'bg-white', 'shadow-md');
                header.classList.remove('bg-gradient-to-r', 'from-gray-800', 'to-gray-700');
            } else {
                header.classList.remove('scrolled', 'bg-white', 'shadow-md');
                header.classList.add('bg-gradient-to-r', 'from-gray-800', 'to-gray-700');
            }

            if (scrollTop > lastScrollTop) {
                // Scrolling down
                header.style.transform = 'translateY(-100%)';
            } else {
                // Scrolling up
                header.style.transform = 'translateY(0)';
            }

            lastScrollTop = scrollTop;
        });
        document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('search-filter-form');
    const resourcesContainer = document.querySelector('.grid');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        updateResources();
    });

    form.querySelectorAll('select').forEach(select => {
        select.addEventListener('change', updateResources);
    });

    function updateResources() {
    const formData = new FormData(form);
    const searchParams = new URLSearchParams(formData);

    fetch(`{{ route('resources.index') }}?${searchParams.toString()}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newResourcesContainer = doc.querySelector('.grid');
        const newPagination = doc.querySelector('.mt-6');

        if (newResourcesContainer) {
            resourcesContainer.innerHTML = newResourcesContainer.innerHTML;
        }

        const paginationContainer = document.querySelector('.mt-6');
        if (newPagination && paginationContainer) {
            paginationContainer.innerHTML = newPagination.innerHTML;
        }

        // Reinitialize FontAwesome icons
        if (window.FontAwesome) {
            window.FontAwesome.dom.i2svg();
        }
    })
    .catch(error => console.error('Error:', error));
}
});
    </script>
</body>
</html>