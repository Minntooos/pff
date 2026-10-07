<x-admin-layout>

    <x-slot name="header">
        User Management
    </x-slot>
    <div class="bg-white shadow-sm border-b border-gray-200 px-4 py-4 flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800"> </h2>
        <a href="{{ route('admin.users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full transition-colors duration-200">
            <i class="fas fa-plus mr-2"></i> Add New User
        </a>
    </div>
        <div class="flex-1 flex flex-col overflow-hidden">
           

            <!-- Main content area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <!-- Search and filter section -->
                    <form action="{{ route('admin.manage-users') }}" method="GET" class="mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <div class="flex-grow mr-4">
                                <input type="text" name="search" placeholder="Search users..." value="{{ request('search') }}" class="w-full p-2 text-lg border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <select name="usertype" class="p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="all" {{ request('usertype') == 'all' ? 'selected' : '' }}>All Types</option>
                                    <option value="admin" {{ request('usertype') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="user" {{ request('usertype') == 'user' ? 'selected' : '' }}>User</option>
                                </select>
                            </div>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors ml-2">
                                <i class="fas fa-search mr-2"></i> Search
                            </button>
                        </div>
                    </form>
                    <div class="mb-4">
                        <a href="{{ route('admin.users.activity') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            View User Activity
                        </a>
                    </div>
                    <!-- User table -->
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ route('admin.manage-users', ['sort' => 'id', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'] + request()->except(['sort', 'direction'])) }}">
                                            ID
                                            @if(request('sort') == 'id')
                                                <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ route('admin.manage-users', ['sort' => 'name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'] + request()->except(['sort', 'direction'])) }}">
                                            Name
                                            @if(request('sort') == 'name')
                                                <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ route('admin.manage-users', ['sort' => 'email', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'] + request()->except(['sort', 'direction'])) }}">
                                            Email
                                            @if(request('sort') == 'email')
                                                <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ route('admin.manage-users', ['sort' => 'usertype', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'] + request()->except(['sort', 'direction'])) }}">
                                            User Type
                                            @if(request('sort') == 'usertype')
                                                <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>                                        <a href="{{ route('admin.manage-users', ['sort' => 'last_active_at', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'] + request()->except(['sort', 'direction'])) }}">
                                            Last Active
                                            @if(request('sort') == 'last_active_at')
                                                <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($users as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full" src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}'s profile picture">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst($user->usertype) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->last_active_at ? $user->last_active_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('admin.users.show', $user) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($users->hasPages())
                    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 mt-4">
                        {{ $users->links() }}
                    </div>
                @endif
                </div>
            </main>
        </div>
    </div>
</body>
</html>
</x-admin-layout>
