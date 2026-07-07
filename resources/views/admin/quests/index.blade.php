@extends('layouts.admin')

@section('title', 'Daily Quests Management')
@section('subtitle', 'Manage daily quests for gamification')

@section('content')
    <div class="bg-white rounded-lg shadow">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800">All Daily Quests</h3>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Title</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Type</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Target</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Rewards</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($quests as $quest)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-gray-800">{{ $quest->title }}</p>
                                    <p class="text-sm text-gray-500">{{ $quest->description }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">
                                    {{ $quest->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-800">{{ $quest->target }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <p class="text-sm"><span class="text-orange-600 font-bold">{{ $quest->reward_exp }}</span> XP</p>
                                    <p class="text-sm"><span class="text-yellow-600 font-bold">{{ $quest->reward_coins }}</span> 🪙</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 text-sm">
                                {{ $quest->date }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-4 block"></i>
                                <p class="text-gray-500">No daily quests found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $quests->links() }}
        </div>
    </div>
@endsection
