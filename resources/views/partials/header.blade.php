<header class="bg-ts-header p-4 text-white flex justify-between items-center">
    <div class="flex items-center">
        <img src="/path/to/logo.png" alt="TrackSave Logo" class="h-8 mr-2">
        <nav>
            <a href="/dashboard" class="text-white mr-4">Dashboard</a>
            <a href="/resources" class="text-white mr-4">Resources</a>
            <a href="/categories" class="text-white mr-4">Categories</a>
            <a href="/tags" class="text-white mr-4">Tags</a>
        </nav>
    </div>
    <div class="flex items-center">
        <span class="mr-4">Hello, {{ Auth::user()->name }}</span>
        <div class="relative">
            <button id="userMenuButton" class="focus:outline-none">
                <img src="{{ Auth::user()->usertype === 'admin' ? asset('images/a.jpeg') : (Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('images/default-avatar.png')) }}" alt="{{ Auth::user()->name }}" class="h-8 w-8 rounded-full">
            </button>
            <div id="userMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden">
                <a href="/profile" class="block px-4 py-2 text-gray-800">Profile</a>
                <a href="/logout" class="block px-4 py-2 text-gray-800">Logout</a>
            </div>
        </div>
    </div>
</header>
