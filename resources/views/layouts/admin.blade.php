<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'TrackSave Admin' }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{ $head ?? '' }}
</head>
<body class="font-sans bg-gray-100 text-gray-800">
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64">
                <div class="flex items-center justify-center h-16 bg-blue-800">
                    <span class="text-white text-2xl font-semibold">TrackSave Admin</span>
                </div>
                <div class="flex-grow flex flex-col overflow-y-auto bg-blue-800 text-white">
                    <nav class="flex-1 px-2 py-4 space-y-2">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors duration-200">
                            <i class="fas fa-tachometer-alt mr-3"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('admin.manage-users') }}" class="flex items-center px-4 py-2 {{ request()->routeIs('admin.manage-users') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors duration-200">
                            <i class="fas fa-users mr-3"></i>
                            <span>Users</span>
                        </a>
                        <a href="{{ route('admin.pending-resources') }}" class="flex items-center px-4 py-2 {{ request()->routeIs('admin.pending-resources') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors duration-200">
                            <i class="fas fa-book mr-3"></i>
                            <span>Resources</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 hover:bg-blue-700 rounded-lg transition-colors duration-200">
                            <i class="fas fa-cog mr-3"></i>
                            <span>Settings</span>
                        </a>
                        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 hover:bg-blue-700 rounded-lg transition-colors duration-200">
                            <i class="fas fa-user-circle mr-3"></i>
                            <span>User Dashboard</span>
                        </a>
                    </nav>
                </div>
            </div>
        </aside>

        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top bar -->
            <header class="flex justify-between items-center p-4 bg-white border-b border-gray-200">
                <div class="flex items-center">
                    <button class="text-gray-500 focus:outline-none focus:text-gray-700 md:hidden">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <h1 class="text-2xl font-semibold text-gray-800 ml-4">{{ $header }}</h1>
                </div>
                <div class="flex items-center">
                    <button class="flex items-center text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700">
            <img class="h-10 w-10 rounded-full" src="{{ Auth::user()->usertype === 'admin' ? asset('images/a.jpeg') : (Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('images/default-avatar.png')) }}" alt="{{ Auth::user()->name }}">                        <svg class="ml-1 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </header>

            <!-- Main content area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>