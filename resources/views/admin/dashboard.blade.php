@extends('layouts.admin')

@section('title', 'Dashboard')
@section('subtitle', 'Welcome to the YoKode Admin Dashboard')

@section('content')
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Total Users Card -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Users</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_users'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Active Users Card -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Active Users (7d)</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['active_users'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Premium Users Card -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Premium Users</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['premium_users'] }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-crown text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Lessons Card -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Lessons</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_lessons'] }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-book text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Quizzes Card -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Quizzes</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_quizzes'] }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-question-circle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Categories Card -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-indigo-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Categories</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_categories'] }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-tag text-indigo-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Users Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Users by Experience -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-crown text-yellow-500"></i>
                    Top Users by Experience
                </h3>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach($topUsers as $index => $user)
                    <a href="{{ route('admin.users.show', $user) }}" class="px-6 py-4 hover:bg-gray-50 transition flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-600">
                                #{{ $index + 1 }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-gray-800">Lv. {{ $user->level }}</p>
                            <p class="text-sm text-gray-500">{{ $user->total_exp }} XP</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Recent Users -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-clock text-blue-500"></i>
                    Recent Users
                </h3>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach($recentUsers as $user)
                    <a href="{{ route('admin.users.show', $user) }}" class="px-6 py-4 hover:bg-gray-50 transition flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-800">{{ $user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">
                                {{ $user->created_at->diffForHumans() }}
                            </p>
                            @if($user->is_premium)
                                <span class="inline-block px-2 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded">Premium</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-bolt text-yellow-500"></i>
                Quick Actions
            </h3>
        </div>
        <div class="px-6 py-4 grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.users.index') }}" class="p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition text-center">
                <i class="fas fa-users text-blue-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Manage Users</p>
            </a>
            <a href="{{ route('admin.lessons.create') }}" class="p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition text-center">
                <i class="fas fa-plus text-orange-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">New Lesson</p>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition text-center">
                <i class="fas fa-tag text-indigo-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Categories</p>
            </a>
            <a href="{{ route('admin.analytics') }}" class="p-4 bg-green-50 rounded-lg hover:bg-green-100 transition text-center">
                <i class="fas fa-chart-bar text-green-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Analytics</p>
            </a>
        </div>
    </div>
@endsection
