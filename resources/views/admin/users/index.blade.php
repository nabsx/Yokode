@extends('layouts.admin')

@section('title', 'User Management')
@section('subtitle', 'View and manage all users')

@section('content')
    <div class="bg-white rounded-lg shadow">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">All Users</h3>
        </div>

        <!-- Filters & Search -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Search by name or email..." 
                        value="{{ request('search') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>
                <div>
                    <select name="premium" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Users</option>
                        <option value="yes" {{ request('premium') === 'yes' ? 'selected' : '' }}>Premium Only</option>
                        <option value="no" {{ request('premium') === 'no' ? 'selected' : '' }}>Free Only</option>
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Level</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Experience</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Premium</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Joined</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-800">{{ $user->name }}</p>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">
                                    Level {{ $user->level }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ number_format($user->total_exp) }} XP</td>
                            <td class="px-6 py-4">
                                @if($user->is_premium)
                                    <span class="inline-block px-3 py-1 bg-purple-100 text-purple-800 text-sm font-semibold rounded-full">
                                        <i class="fas fa-crown mr-1"></i>Active
                                    </span>
                                @else
                                    <span class="inline-block px-3 py-1 bg-gray-100 text-gray-800 text-sm rounded-full">
                                        Free
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-600 text-sm">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.users.show', $user) }}" class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition text-sm font-medium">
                                        <i class="fas fa-eye mr-1"></i>View
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200 transition text-sm font-medium">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 transition text-sm font-medium">
                                            <i class="fas fa-trash mr-1"></i>Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-4 block"></i>
                                <p class="text-gray-500">No users found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    </div>
@endsection
