@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="border-b pb-4 mb-4">
            <h1 class="text-2xl font-bold">🏆 Papan Peringkat</h1>
            <p class="text-gray-500">Top 50 pebelajar dengan EXP tertinggi</p>
        </div>
        
        <!-- League Info -->
        <div class="bg-gradient-to-r from-purple-500 to-purple-700 rounded-lg p-4 mb-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-sm opacity-80">Liga Kamu</span>
                    <div class="text-3xl font-bold">{{ Auth::user()->league }}</div>
                </div>
                <div class="text-right">
                    <span class="text-sm opacity-80">Progress ke {{ Auth::user()->league == 'Legend' ? 'Puncak' : 'Liga berikutnya' }}</span>
                    <div class="w-48 bg-purple-300 rounded-full h-2 mt-1">
                        <div class="bg-yellow-300 h-2 rounded-full" style="width: {{ Auth::user()->league_progress }}%;"></div>
                    </div>
                    <div class="text-sm mt-1">{{ Auth::user()->league_progress }}%</div>
                </div>
            </div>
        </div>
        
        <!-- User Rank -->
        <div class="bg-blue-50 rounded-lg p-4 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-gray-600">Peringkat Kamu</span>
                    <span class="text-2xl font-bold text-blue-600 ml-2">#{{ $userRank }}</span>
                </div>
                <div class="text-right">
                    <span class="text-gray-600">Total EXP</span>
                    <span class="text-xl font-bold ml-2">{{ number_format(Auth::user()->total_exp) }}</span>
                </div>
            </div>
        </div>
        
        <!-- Leaderboard Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Peringkat</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Pebelajar</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">Liga</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">Level</th>
                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-600">Total EXP</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($leaderboard as $index => $user)
                        <tr class="border-b hover:bg-gray-50 {{ Auth::user()->id === $user->id ? 'bg-yellow-50' : '' }}">
                            <td class="px-4 py-3">
                                @if($index + 1 == 1)
                                    <span class="text-2xl">🥇</span>
                                @elseif($index + 1 == 2)
                                    <span class="text-2xl">🥈</span>
                                @elseif($index + 1 == 3)
                                    <span class="text-2xl">🥉</span>
                                @else
                                    <span class="text-gray-500 font-medium">#{{ $index + 1 }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-medium">
                                {{ $user->name }}
                                @if(Auth::user()->id === $user->id)
                                    <span class="text-xs bg-yellow-200 text-yellow-800 px-2 py-0.5 rounded ml-2">Anda</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-sm font-medium text-purple-600">{{ $user->league }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-blue-600 font-medium">{{ $user->level }}</span>
                            </td>
                            <td class="px-4 py-3 text-right font-bold">
                                {{ number_format($user->total_exp) }} EXP
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection