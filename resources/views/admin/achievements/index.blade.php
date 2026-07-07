@extends('layouts.admin')

@section('title', 'Achievement Management')
@section('subtitle', 'Manage gamification achievements')

@section('content')
    <div class="bg-white rounded-lg shadow">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800">All Achievements</h3>
        </div>

        <!-- Content -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
            @forelse($achievements as $achievement)
                <div class="bg-gradient-to-br from-purple-50 to-blue-50 rounded-lg p-6 border border-purple-200">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">{{ $achievement->name }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $achievement->description }}</p>
                        </div>
                        <span class="text-4xl">{{ $achievement->icon ?? '🏆' }}</span>
                    </div>
                    <div class="mt-4 pt-4 border-t border-purple-200">
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div>
                                <p class="text-gray-600">Reward XP</p>
                                <p class="font-bold text-orange-600">{{ $achievement->reward_exp ?? 0 }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Reward Coins</p>
                                <p class="font-bold text-yellow-600">{{ $achievement->reward_coins ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center">
                    <i class="fas fa-inbox text-4xl text-gray-300 mb-4 block"></i>
                    <p class="text-gray-500">No achievements found</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $achievements->links() }}
        </div>
    </div>
@endsection
