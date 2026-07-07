@extends('layouts.admin')

@section('title', 'Category Management')
@section('subtitle', 'Manage lesson categories')

@section('content')
    <div class="bg-white rounded-lg shadow">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">All Categories</h3>
            <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-plus mr-2"></i>New Category
            </a>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Icon</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Lessons</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Created</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-gray-800">{{ $category->name }}</p>
                                    <p class="text-sm text-gray-500">{{ Str::limit($category->description, 50) }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($category->icon)
                                    <span class="text-2xl">{{ $category->icon }}</span>
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">
                                    {{ $category->lessons_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 text-sm">
                                {{ $category->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200 transition text-sm font-medium">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure? This will not delete associated lessons.');">
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
                            <td colspan="5" class="px-6 py-8 text-center">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-4 block"></i>
                                <p class="text-gray-500">No categories found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $categories->links() }}
        </div>
    </div>
@endsection
