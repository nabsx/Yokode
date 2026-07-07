@extends('layouts.admin')

@section('title', 'Quiz Management')
@section('subtitle', 'Manage all quizzes')

@section('content')
    <div class="bg-white rounded-lg shadow">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800">All Quizzes</h3>
        </div>

        <!-- Filters -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Search by title..." 
                        value="{{ request('search') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Title</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Questions</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Created</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($quizzes as $quiz)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-800">{{ $quiz->title }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">
                                    {{ $quiz->questions_count ?? 0 }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 text-sm">
                                {{ $quiz->created_at->format('d M Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-4 block"></i>
                                <p class="text-gray-500">No quizzes found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $quizzes->links() }}
        </div>
    </div>
@endsection
