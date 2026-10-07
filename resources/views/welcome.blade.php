<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrackSave - Learning Resource Management</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    @vite('resources/css/app.css')

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
        .cta-button:hover {
            background-color: #c0392b;
        }
        .overview-cards {
        width: 100%;
        overflow: hidden;
    }

    .cards-container {
        display: flex;
        width: 200%;
        gap: 1rem;
    }

    .card {
        width: calc(12.5% - 0.875rem);
    }

    @keyframes scroll {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(-50%);
        }
    }

    .animate-scroll {
        animation: scroll 20s linear infinite;
    }

    .cards-container:hover {
        animation-play-state: paused;
    }

    @media (max-width: 1024px) {
        .card {
            width: calc(25% - 0.75rem);
        }
    }

    @media (max-width: 640px) {
        .card {
            width: calc(50% - 0.5rem);
        }
    }
    .btn-primary {
    @apply bg-white text-indigo-600 font-semibold py-3 px-6 rounded-full transition duration-300 hover:bg-indigo-100 hover:shadow-lg;
}

.btn-secondary {
    @apply bg-transparent border-2 border-white text-white font-semibold py-3 px-6 rounded-full transition duration-300 hover:bg-white hover:text-indigo-600;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInRight {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate-fadeInUp {
    animation: fadeInUp 0.6s ease-out forwards;
}

.animate-fadeInRight {
    animation: fadeInRight 0.6s ease-out forwards;
}

.delay-200 {
    animation-delay: 0.2s;
}

.delay-300 {
    animation-delay: 0.3s;
}

.delay-400 {
    animation-delay: 0.4s;
}
    </style>
</head>
<body class="bg-gray-100 text-gray-800">
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

    <section class="hero relative overflow-hidden py-20 bg-gradient-to-br from-indigo-600 to-purple-700">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 text-white mb-10 md:mb-0">
                    <h1 class="text-4xl md:text-5xl font-bold mb-6 animate-fadeInUp">
                        Empower Your Learning Journey with TrackSave
                    </h1>
                    <p class="text-xl mb-8 animate-fadeInUp delay-200">
                        Effortlessly manage, organize, and share your learning resources. 
                        Unlock your potential with our intelligent tracking system.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('register') }}" class="btn-primary animate-fadeInUp delay-300">
                            Get Started for Free
                        </a>
                        <a href="#features" class="btn-secondary animate-fadeInUp delay-400">
                            Explore Features
                        </a>
                    </div>
                </div>
                <div class="md:w-1/2 animate-fadeInRight">
                    <img src="{{ asset('images/vector-test.svg') }}" alt="TrackSave Learning Illustration" class="w-full h-auto">
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#ffffff" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </div>
    </section>

    <div class="container mx-auto py-8 px-4">
        <section class="create-resource-section bg-gradient-to-r from-blue-500 to-indigo-600 py-16 mb-8 rounded-lg shadow-lg overflow-hidden relative">
            <div class="container mx-auto px-4 flex flex-col md:flex-row items-center justify-between">
                <div class="md:w-1/2 lg:w-1/2 mb-8 md:mb-0 relative z-10 md:pr-8">
                    <h2 class="text-3xl font-bold mb-4 text-white">Create New Resource</h2>
                    <p class="text-lg text-white mb-6">
                        Easily add and organize your learning materials. Start building your knowledge base today!
                    </p>
                    <ul class="text-white mb-8 list-disc list-inside">
                        <li>Upload documents, videos, or links</li>
                        <li>Categorize and tag your resources</li>
                        <li>Set visibility and share with others</li>
                        <li>Track your learning progress</li>
                    </ul>
                    <a href="{{ route('resources.create') }}" class="inline-block bg-white text-indigo-600 font-bold py-3 px-6 rounded-full transition duration-300 hover:bg-indigo-100">
                        Start Creating
                    </a>
                </div>
                <div class="md:w-1/2 lg:w-1/2 relative">
                    <div class="aspect-w-16 aspect-h-9 md:aspect-w-4 md:aspect-h-3">
                        <img src="{{ asset('images/visualise-resources.jpeg') }}" alt="Create Resource Illustration" class="object-cover rounded-lg shadow-xl">
                        <div class="absolute inset-0 bg-indigo-600 opacity-20 rounded-lg"></div>
                    </div>
                </div>
            </div>
        </section>
        <div class="relative flex items-center mb-8">
            <input type="text" placeholder="Search resources, categories, or tags..." class="flex-grow p-2 border border-gray-300 rounded-l-md">
            <button class="bg-orange-500 text-white p-2 rounded-r-md hover:bg-red-600 transition duration-300">Search</button>
        </div>

        <div class="overview-cards mb-8 overflow-hidden">
            <div class="cards-container flex animate-scroll">
                <!-- Original set of cards -->
                <div class="card p-6 bg-white rounded-lg shadow flex flex-col items-center flex-shrink-0 w-1/4">
                    <div class="card-icon text-4xl mb-2">📚</div>
                    <h3 class="text-xl font-semibold mb-2">Public Resources</h3>
                    <p class="text-2xl font-bold">{{ $resourceCount }}</p>
                </div>
                <div class="card p-6 bg-white rounded-lg shadow flex flex-col items-center flex-shrink-0 w-1/4">
                    <div class="card-icon text-4xl mb-2">🗂️</div>
                    <h3 class="text-xl font-semibold mb-2">Categories</h3>
                    <p class="text-2xl font-bold">{{ $categoryCount }}</p>
                </div>
                <div class="card p-6 bg-white rounded-lg shadow flex flex-col items-center flex-shrink-0 w-1/4">
                    <div class="card-icon text-4xl mb-2">🏷️</div>
                    <h3 class="text-xl font-semibold mb-2">Tags</h3>
                    <p class="text-2xl font-bold">{{ $tagCount }}</p>
                </div>
                <div class="card p-6 bg-white rounded-lg shadow flex flex-col items-center flex-shrink-0 w-1/4">
                    <div class="card-icon text-4xl mb-2">📊</div>
                    <h3 class="text-xl font-semibold mb-2">Most Popular Category</h3>
                    <p class="text-2xl font-bold">{{ $resourcesByCategory->keys()->first() }}</p>
                </div>
                <!-- Duplicated set of cards -->
                <div class="card p-6 bg-white rounded-lg shadow flex flex-col items-center flex-shrink-0 w-1/4">
                    <div class="card-icon text-4xl mb-2">📚</div>
                    <h3 class="text-xl font-semibold mb-2">Public Resources</h3>
                    <p class="text-2xl font-bold">{{ $resourceCount }}</p>
                </div>
                <div class="card p-6 bg-white rounded-lg shadow flex flex-col items-center flex-shrink-0 w-1/4">
                    <div class="card-icon text-4xl mb-2">🗂️</div>
                    <h3 class="text-xl font-semibold mb-2">Categories</h3>
                    <p class="text-2xl font-bold">{{ $categoryCount }}</p>
                </div>
                <div class="card p-6 bg-white rounded-lg shadow flex flex-col items-center flex-shrink-0 w-1/4">
                    <div class="card-icon text-4xl mb-2">🏷️</div>
                    <h3 class="text-xl font-semibold mb-2">Tags</h3>
                    <p class="text-2xl font-bold">{{ $tagCount }}</p>
                </div>
                <div class="card p-6 bg-white rounded-lg shadow flex flex-col items-center flex-shrink-0 w-1/4">
                    <div class="card-icon text-4xl mb-2">📊</div>
                    <h3 class="text-xl font-semibold mb-2">Most Popular Category</h3>
                    <p class="text-2xl font-bold">{{ $resourcesByCategory->keys()->first() }}</p>
                </div>
            </div>
        </div>
        
        <div class="chart-container bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="section-title text-2xl font-semibold mb-4 text-center">Resources by Category</h2>
            <div class="relative" style="height: 400px;">
                <canvas id="resourcesByCategoryChart" class="chart"></canvas>
            </div>
        </div>

        <section class="featured-resources py-12 bg-gray-50">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold mb-8 text-center text-gray-800">Featured Public Resources</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($publicResources as $resource)
                    <div class="resource-card bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl">
                        <div class="relative h-48">
                            @if($resource->resource_image_path)
                                <img src="{{ asset('storage/resource_images/' . $resource->resource_image_path) }}" alt="{{ $resource->title }}" class="w-full h-full object-cover">
                                @if($resource->type === 'video')
                                    <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50">
                                        <i class="fas fa-play-circle text-white text-4xl"></i>
                                    </div>
                                @endif
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
                            @if($resource->is_featured)
                                <span class="absolute top-2 right-2 bg-yellow-400 text-xs font-bold px-2 py-1 rounded-full text-gray-800">Featured</span>
                            @endif
                        </div>
                            <div class="p-6">
                                <h3 class="text-xl font-semibold mb-2 text-gray-800">{{ $resource->title }}</h3>
                                <p class="text-gray-600 mb-4">{!! Str::limit($resource->description, 100) !!}</p>
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach($resource->tags as $tag)
                                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">{{ $resource->category->name }}</span>
                                    <a href="{{ route('resources.show', $resource) }}" class="inline-block bg-orange-500 text-white font-semibold px-4 py-2 rounded-full hover:bg-orange-600 transition duration-300">View More</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        
        <section class="browse-categories py-16 bg-gray-50 relative overflow-hidden">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold mb-8 text-center text-gray-800">Explore Learning Categories</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
                    @foreach($categories->take(8) as $category)
                        <a href="{{ route('resources.index', ['category' => $category->id]) }}" class="category-card bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                            <div class="relative h-40">
                                @if($category->last_resource_image)
                                    <img src="{{ asset('storage/resource_images/' . $category->last_resource_image) }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center">
                                        <span class="text-white text-4xl">{{ $category->icon ?? '📚' }}</span>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 transition-opacity duration-300 hover:opacity-100">
                                    <span class="text-white font-semibold">Explore</span>
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="text-lg font-semibold mb-1 text-gray-800">{{ $category->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $category->resources_count }} {{ Str::plural('resource', $category->resources_count) }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="text-center">
                    <a href="{{ route('categories.index') }}" class="inline-block bg-indigo-600 text-white font-semibold px-6 py-3 rounded-full hover:bg-indigo-700 transition duration-300">View All Categories</a>
                </div>
            </div>
            <div class="absolute inset-0 opacity-5 pointer-events-none">
                <img src="{{ asset('images/category-pattern.svg') }}" alt="Background Pattern" class="w-full h-full object-cover">
            </div>
        </section>
<section class="collaborative-learning bg-gray-100 py-16">
    <div class="container mx-auto px-4 flex flex-col md:flex-row items-center">
        <div class="md:w-1/2 mb-8 md:mb-0">
            <img src="{{ asset('images/learning-people.jpeg') }}" alt="Collaborative Learning" class="rounded-lg shadow-lg">
        </div>
        <div class="md:w-1/2 md:pl-12">
            <h2 class="text-3xl font-bold mb-4 text-gray-800">Collaborative Learning</h2>
            <p class="text-lg text-gray-600 mb-6">
                Enhance your learning experience by collaborating with peers. Share resources, discuss ideas, and grow together in a supportive community of lifelong learners.
            </p>
            <ul class="list-disc list-inside text-gray-600 mb-8">
                <li>Create study groups</li>
                <li>Share and discuss resources</li>
                <li>Collaborate on projects</li>
                <li>Get feedback from peers</li>
            </ul>
            <a href="{{ route('register') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-full transition duration-300">Join the Community</a>
        </div>
    </div>
</section>
    </div>

    <footer class="footer bg-gradient-to-r from-gray-800 to-gray-700 text-gray p-4">
        <div class="container mx-auto flex justify-between items-center">
            <div>© 2024 TrackSave. All rights reserved.</div>
            <div>
                <a href="{{ url('/contact') }}" class="hover:text-orange-500 transition duration-300">Contact Us</a>
                <a href="{{ url('/privacy') }}" class="hover:text-orange-500 transition duration-300 ml-4">Privacy Policy</a>
                <a href="{{ url('/terms') }}" class="hover:text-orange-500 transition duration-300 ml-4">Terms of Service</a>
            </div>
            <div class="social-icons flex gap-4">
                <a href="https://www.facebook.com/tracksave" class="hover:text-orange-500 transition duration-300">📘</a>
                <a href="https://www.twitter.com/tracksave" class="hover:text-orange-500 transition duration-300">🐦</a>
                <a href="https://www.instagram.com/tracksave" class="hover:text-orange-500 transition duration-300"></a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Header scroll effect
        const header = document.getElementById('header');
        const heroContent = document.getElementById('heroContent');
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

        // Resources by Category Chart
        const ctx = document.getElementById('resourcesByCategoryChart').getContext('2d');
                    new Chart(ctx, {
                    type: 'bar',
                    data: {
                    labels: {!! json_encode($resourcesByCategory->keys()) !!},
                    datasets: [{
                    label: 'Number of Resources',
                    data: {!! json_encode($resourcesByCategory->values()) !!},
                    backgroundColor: 'rgba(99, 102, 241, 0.8)',
                    borderColor: '#6366f1',
                    borderWidth: 1
                    }]
                    },
                    options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                    y: {
                    beginAtZero: true,
                    ticks: {
                    precision: 0
                    }
                    }
                    },
                    plugins: {
                    legend: {
                    display: false
                    },
                    tooltip: {
                    mode: 'index',
                    intersect: false,
                    }
                    }
                    }
                    });
    
    </script>
</body>
</html>
