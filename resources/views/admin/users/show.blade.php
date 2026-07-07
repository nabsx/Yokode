@extends('layouts.admin')

@section('title', 'User Details')
@section('subtitle', $user->name)

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Info Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-center mb-6">
                    <div class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user text-blue-600 text-4xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h2>
                    <p class="text-gray-500">{{ $user->email }}</p>
                </div>

                <div class="space-y-3 mb-6">
                    <div class="flex justify-between items-center p-3 bg-orange-50 rounded-lg">
                        <span class="text-gray-700 font-medium">Total XP</span>
                        <span class="text-orange-600 font-bold">{{ number_format($stats['total_exp']) }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                        <span class="text-gray-700 font-medium">Coins</span>
                        <span class="text-yellow-600 font-bold">{{ number_format($stats['coins']) }}</span>
                    </div>
                </div>

                <div class="border-t pt-4 space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Joined</span>
                        <span class="font-medium">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Last Active</span>
                        <span class="font-medium">{{ $user->updated_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status</span>
                        @if($user->is_premium)
                            <span class="px-3 py-1 bg-purple-100 text-purple-800 text-sm font-semibold rounded-full">
                                <i class="fas fa-crown mr-1"></i>Premium
                            </span>
                        @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-800 text-sm rounded-full">Free</span>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2 mt-6">
                    <a href="{{ route('admin.users.edit', $user) }}" class="w-full px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition text-center font-medium">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="w-full" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                            <i class="fas fa-trash mr-1"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="lg:col-span-2">
            <!-- Learning Stats -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-book text-blue-600"></i>
                    Learning Progress
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-blue-50 rounded-lg p-4 text-center">
                        <i class="fas fa-check-circle text-blue-600 text-2xl mb-2"></i>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['lessons_completed'] }}</p>
                        <p class="text-sm text-gray-600">Lessons Completed</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4 text-center">
                        <i class="fas fa-question-circle text-green-600 text-2xl mb-2"></i>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['quizzes_answered'] }}</p>
                        <p class="text-sm text-gray-600">Quizzes Answered</p>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-4 text-center">
                        <i class="fas fa-trophy text-purple-600 text-2xl mb-2"></i>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['achievements'] }}</p>
                        <p class="text-sm text-gray-600">Achievements</p>
                    </div>
                    <div class="bg-red-50 rounded-lg p-4 text-center">
                        <i class="fas fa-heart text-red-600 text-2xl mb-2"></i>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['hearts'] }}/5</p>
                        <p class="text-sm text-gray-600">Hearts</p>
                    </div>
                </div>
            </div>

            <!-- Gamification Stats -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-gamepad text-orange-600"></i>
                    Gamification Stats
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="border border-gray-200 rounded-lg p-4">
                        <p class="text-gray-600 text-sm mb-1">🔥 Current Streak</p>
                        <p class="text-3xl font-bold text-orange-600">{{ $stats['streak'] }}</p>
                        <p class="text-xs text-gray-500 mt-2">days</p>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4">
                        <p class="text-gray-600 text-sm mb-1">💎 Premium Status</p>
                        <p class="text-xl font-bold text-purple-600">
                            @if($user->is_premium)
                                Active
                            @else
                                Inactive
                            @endif
                        </p>
                        @if($user->premium_expires_at)
                            <p class="text-xs text-gray-500 mt-2">Expires: {{ $user->premium_expires_at->format('d M Y') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
            <i class="fas fa-arrow-left mr-2"></i>Back to Users
        </a>
    </div>
@endsection
