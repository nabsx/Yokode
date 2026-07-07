@extends('layouts.admin')

@section('title', 'Analytics & Reports')
@section('subtitle', 'Platform statistics and insights')

@section('content')
    <!-- Premium Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Premium Users</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $premiumStats['total_premium'] }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-crown text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Active Premium Users</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $premiumStats['active_premium'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Lessons Section -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-chart-bar text-blue-600"></i>
                Top 10 Most Completed Lessons
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Lesson Title</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Completions</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Progress</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($topLessons as $lesson)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-800">{{ $lesson->title }}</p>
                                @if($lesson->category)
                                    <p class="text-sm text-gray-500">{{ $lesson->category->name }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">
                                    {{ $lesson->user_progresses_count ?? 0 }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min(100, (($lesson->user_progresses_count ?? 0) / 50) * 100) }}%"></div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center">
                                <p class="text-gray-500">No lesson data available</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- User Growth Section -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-chart-line text-green-600"></i>
                Recent User Growth (Last 30 Days)
            </h3>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-2 text-left text-gray-600 font-semibold">Date</th>
                            <th class="px-4 py-2 text-left text-gray-600 font-semibold">New Users</th>
                            <th class="px-4 py-2 text-left text-gray-600 font-semibold">Visualization</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($userGrowth as $day)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="px-4 py-3">{{ \Carbon\Carbon::parse($day->date)->format('d M Y') }}</td>
                                <td class="px-4 py-3 font-semibold">{{ $day->count }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-full bg-gray-200 rounded-full h-6 max-w-xs">
                                            <div class="bg-green-500 h-6 rounded-full flex items-center justify-center text-white text-xs font-bold" style="width: {{ min(100, $day->count * 10) }}%">
                                                {{ $day->count }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-gray-500">
                                    No user growth data available
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
