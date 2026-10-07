<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- External CSS Dependencies -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- External JavaScript Dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js" defer></script>
    <script src="https://requirejs.org/docs/release/2.3.5/minified/require.js" defer></script>

    <style>
        #loading-indicator {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: #4f46e5; /* Indigo color, you can change this */
            z-index: 9999;
            transition: width 0.2s ease-out;
        }
    </style>
</head>
<body class="bg-lightGray">
    <div id="loading-indicator" style="width: 0%;"></div>

    <!-- Header -->
    <header class="header flex items-center justify-between px-4 py-3 text-white bg-gray-800">
        <div class="flex items-center space-x-8">
            <!-- Logo -->
            <div class="shrink-0">
                <a href="{{ route('dashboard') }}">
                    <img class="h-10 w-auto" src="{{ asset('images/logo.png') }}" alt="{{ config('app.name', 'Logo') }}">
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="hidden space-x-8 sm:flex">
                <a href="{{ route('dashboard') }}" 
                   class="nav-link text-white hover:text-secondary {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('resources.index') }}" 
                   class="nav-link text-white hover:text-secondary {{ request()->routeIs('resources.index') ? 'active' : '' }}">
                    Resources
                </a>
                <a href="{{ route('profile.edit') }}" 
                   class="nav-link text-white hover:text-secondary {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    My Profile
                </a>
                <a href="{{ route('tags.index') }}" 
                   class="nav-link text-white hover:text-secondary {{ request()->routeIs('tags.index') ? 'active' : '' }}">
                    Tags
                </a>
            </nav>
        </div>

        <!-- User Actions -->
        <div class="hidden sm:flex items-center">
          <!-- Notifications -->
          <div class="relative mr-4">
            <button id="notification-button" class="text-white hover:text-gray-300 focus:outline-none relative">
                <i class="fas fa-bell text-xl"></i>
                @php
                    $unreadCount = Auth::user()->unreadNotifications->count();
                @endphp
                @if($unreadCount > 0)
                    <span class="absolute -top-2 -right-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
                        {{ $unreadCount }}
                    </span>
                @endif
            </button>

    <!-- Notification Dropdown -->
    <!-- Notification Dropdown -->
<div id="notification-dropdown" class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg overflow-hidden z-20 hidden">
    <div class="py-2">
        @php
            $notifications = Auth::user()->notifications()->latest()->take(5)->get();
            $notificationsCount = $notifications->count();
        @endphp
        
        @if($notificationsCount > 0)
            @foreach($notifications as $notification)
                @php
                    $data = $notification->data ?? [];
                    $sender = isset($data['sender_id']) ? App\Models\User::find($data['sender_id']) : null;
                    $resourceId = $data['resource_id'] ?? null;
                    $resourceName = $data['resource_name'] ?? 'Resource';
                @endphp
                <div class="flex items-center px-4 py-3 border-b hover:bg-gray-100 {{ $notification->read_at ? 'opacity-50' : '' }}">
                    <img src="{{ $sender && $sender->profile_picture ? asset('storage/' . $sender->profile_picture) : asset('images/default-avatar.png') }}" 
                         alt="{{ $sender ? $sender->name : 'Unknown User' }}" 
                         class="w-10 h-10 rounded-full mr-3">
                    <div class="text-black">
                        @if($resourceId)
                            <a href="{{ route('resources.show', $resourceId) }}" class="text-blue-600 hover:underline">{{ $resourceName }}</a>:
                        @endif
                        {{ $data['message'] ?? 'You have a new notification.' }}
                    </div>
                </div>
            @endforeach
        @else
            <div class="px-4 py-3 text-center text-gray-500">
                No new notifications.
            </div>
        @endif
    </div>
</div>
</div>

            <!-- Greeting -->
            <span class="mr-4">Hello, {{ Auth::user()->name }}</span>

            <!-- Create Resource Button -->
            <a href="{{ route('resources.create') }}" class="button-accent text-white px-4 py-2 rounded-md">
                Create Resource
            </a>
        </div>
    </header>

    <!-- Main Container -->
    <div class="min-h-screen flex pt-16">
        <!-- Sidebar -->
        <aside class="sidebar flex flex-col justify-between bg-gray-800 h-screen w-64 fixed">
            <div class="overflow-y-auto">
                <!-- Dashboard Label -->
                <div class="p-6">
                    <a href="{{ route('dashboard') }}" class="text-white text-xl font-semibold">
                        Dashboard
                    </a>
                </div>
                
                <!-- Sidebar Navigation -->
                <nav class="mt-6">
                    <div class="px-4 mb-4">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Main</h3>
                        <a href="{{ route('dashboard') }}" 
                           class="sidebar-link flex items-center px-4 py-2 mt-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-gray-700 text-white' : '' }}">
                            <i class="fas fa-home mr-3"></i>
                            <span>Home</span>
                        </a>
                    </div>
        
                    <div class="px-4 mb-4">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Content Management</h3>
                        <a href="{{ route('resources.manage') }}" 
                           class="sidebar-link flex items-center px-4 py-2 mt-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('resources.manage') ? 'bg-gray-700 text-white' : '' }}">
                            <i class="fas fa-folder-open mr-3"></i>
                            <span>My Resources</span>
                        </a>
                        <a href="{{ route('categories.index') }}" 
                           class="sidebar-link flex items-center px-4 py-2 mt-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('categories.index') ? 'bg-gray-700 text-white' : '' }}">
                            <i class="fas fa-th-list mr-3"></i>
                            <span>Categories</span>
                        </a>
                        <a href="{{ route('tags.index') }}" 
                           class="sidebar-link flex items-center px-4 py-2 mt-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('tags.index') ? 'bg-gray-700 text-white' : '' }}">
                            <i class="fas fa-tags mr-3"></i>
                            <span>Tags</span>
                        </a>
                    </div>
        
                    <div class="px-4 mb-4">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Activity</h3>
                        <a href="{{ route('resources.recent-activity') }}" 
                           class="sidebar-link flex items-center px-4 py-2 mt-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('resources.recent-activity') ? 'bg-gray-700 text-white' : '' }}">
                            <i class="fas fa-history mr-3"></i>
                            <span>Recent Activity</span>
                        </a>
                    </div>
        
                    <div class="px-4">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">User</h3>
                        <a href="{{ route('profile.edit') }}" 
                           class="sidebar-link flex items-center px-4 py-2 mt-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('profile.edit') ? 'bg-gray-700 text-white' : '' }}">
                            <i class="fas fa-cog mr-3"></i>
                            <span>Settings</span>
                        </a>
                    </div>
                </nav>
            </div>
        
            <!-- User Profile in Sidebar -->
            <div class="p-4 border-t border-gray-700">
                <div class="flex items-center cursor-pointer text-gray-300" onclick="toggleProfileMenu()">
                    <img class="h-10 w-10 rounded-full" 
                         src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('images/default-avatar.jpeg') }}" 
                         alt="{{ Auth::user()->name }}">           
                    <div class="ml-3">
                        <div class="text-base font-medium text-white">{{ Auth::user()->name }}</div>
                        <div class="text-sm font-medium text-gray-400">{{ Auth::user()->email }}</div>
                    </div>
                    <div class="ml-auto">
                        <span class="inline-block w-2 h-2 rounded-full bg-green-400"></span>
                    </div>
                </div>

                <!-- Profile Dropdown -->
                <div id="profile-menu" class="mt-3 hidden bg-gray-700 rounded-md shadow-lg">
                    <a href="{{ route('profile.edit') }}" 
                       class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-600 hover:text-white">
                        Your Profile
                    </a>
                    <a href="#" 
                       class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-600 hover:text-white" 
                       onclick="toggleDarkMode()">
                        Toggle Dark Mode
                    </a>
                    <a href="{{ route('logout') }}" 
                       class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-600 hover:text-white" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Sign out
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 p-6 bg-lightGray ml-64">
            {{ $slot }}
        </main>
    </div>

    <!-- Scripts -->
    <script>
        // Toggle Profile Menu
        function toggleProfileMenu() {
            const profileMenu = document.getElementById('profile-menu');
            profileMenu.classList.toggle('hidden');
        }

        // Toggle Notification Dropdown
        document.addEventListener('DOMContentLoaded', function() {
            const notificationButton = document.getElementById('notification-button');
            const notificationDropdown = document.getElementById('notification-dropdown');

            if (notificationButton && notificationDropdown) {
                notificationButton.addEventListener('click', function(event) {
                    event.stopPropagation();
                    notificationDropdown.classList.toggle('hidden');
                });

                document.addEventListener('click', function(event) {
                    if (!notificationButton.contains(event.target) && !notificationDropdown.contains(event.target)) {
                        notificationDropdown.classList.add('hidden');
                    }
                });
            }
        });

        // Highlight Active Sidebar Link
        document.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('click', function() {
                document.querySelectorAll('.sidebar-link').forEach(l => {
                    l.classList.remove('bg-gray-700', 'text-white');
                });
                this.classList.add('bg-gray-700', 'text-white');
            });
        });

        // Optional: Toggle Dark Mode (Implement functionality as needed)
        function toggleDarkMode() {
            document.body.classList.toggle('dark');
            // Save preference to localStorage or perform additional actions
        }

        // Loading indicator functionality
        document.addEventListener('DOMContentLoaded', function() {
            const loadingIndicator = document.getElementById('loading-indicator');
            let loadingTimer;

            function startLoading() {
                loadingIndicator.style.width = '0%';
                loadingTimer = setInterval(() => {
                    const currentWidth = parseFloat(loadingIndicator.style.width);
                    if (currentWidth < 90) {
                        loadingIndicator.style.width = `${currentWidth + (90 - currentWidth) / 10}%`;
                    }
                }, 100);
            }

            function stopLoading() {
                clearInterval(loadingTimer);
                loadingIndicator.style.width = '100%';
                setTimeout(() => {
                    loadingIndicator.style.width = '0%';
                }, 200);
            }

            // Intercept all link clicks
            document.addEventListener('click', function(event) {
                const target = event.target.closest('a');
                if (target && target.href && !target.target && !event.ctrlKey && !event.metaKey) {
                    startLoading();
                }
            });

            // Listen for page load completion
            window.addEventListener('load', stopLoading);

            // Fallback for cases where 'load' event doesn't fire
            setTimeout(stopLoading, 5000);
        });
    </script>
</body>
</html>