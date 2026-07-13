@extends('layouts.admin')

@section('title', 'Daily Quest Templates')
@section('subtitle', 'Manage daily quest templates for each day of the week')

@section('content')
    <div class="grid grid-cols-1 gap-6">
        <!-- Info Box -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-start">
            <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0zM8 9a1 1 0 000 2h6a1 1 0 100-2H8zm0-4a1 1 0 110 2h3a1 1 0 100-2H8z" clip-rule="evenodd" />
            </svg>
            <div>
                <h3 class="font-semibold text-blue-900">Daily Quest Templates</h3>
                <p class="text-sm text-blue-800">Configure daily quests for each day of the week. Users will automatically receive these quests based on the current day.</p>
            </div>
        </div>

        <!-- Initialize Templates Button -->
        @if($templates->isEmpty())
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <p class="text-sm text-yellow-800 mb-3">No templates found. Click the button below to initialize default templates.</p>
                <form action="{{ route('admin.quest-templates.initialize') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-2 px-4 rounded">
                        Initialize Default Templates
                    </button>
                </form>
            </div>
        @endif

        <!-- Templates Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-7 gap-4">
            @php
                $days = [
                    0 => ['name' => 'Minggu', 'nameEn' => 'Sunday', 'color' => 'bg-red-50 border-red-200'],
                    1 => ['name' => 'Senin', 'nameEn' => 'Monday', 'color' => 'bg-blue-50 border-blue-200'],
                    2 => ['name' => 'Selasa', 'nameEn' => 'Tuesday', 'color' => 'bg-purple-50 border-purple-200'],
                    3 => ['name' => 'Rabu', 'nameEn' => 'Wednesday', 'color' => 'bg-green-50 border-green-200'],
                    4 => ['name' => 'Kamis', 'nameEn' => 'Thursday', 'color' => 'bg-yellow-50 border-yellow-200'],
                    5 => ['name' => 'Jumat', 'nameEn' => 'Friday', 'color' => 'bg-pink-50 border-pink-200'],
                    6 => ['name' => 'Sabtu', 'nameEn' => 'Saturday', 'color' => 'bg-indigo-50 border-indigo-200'],
                ];
            @endphp

            @foreach($days as $dayNum => $dayInfo)
                @php
                    $template = $templates->firstWhere('day_of_week', $dayNum);
                @endphp
                <div class="border rounded-lg p-4 {{ $dayInfo['color'] }} border {{ str_replace('bg-', 'border-', explode(' ', $dayInfo['color'])[0]) }}">
                    <div class="mb-3">
                        <h3 class="font-bold text-gray-800">{{ $dayInfo['name'] }}</h3>
                        <p class="text-xs text-gray-500">{{ $dayInfo['nameEn'] }}</p>
                    </div>

                    @if($template)
                        <div class="space-y-2 mb-4">
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ $template->title }}</p>
                                <p class="text-xs text-gray-600 line-clamp-2">{{ $template->description }}</p>
                            </div>
                            <div class="text-xs space-y-1">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Type:</span>
                                    <span class="font-semibold text-gray-700">{{ str_replace('_', ' ', $template->type) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Target:</span>
                                    <span class="font-semibold text-gray-700">{{ $template->target }}</span>
                                </div>
                                <div class="flex justify-between pt-1 border-t border-gray-300">
                                    <span class="text-orange-600 font-semibold">{{ $template->reward_exp }} XP</span>
                                    <span class="text-yellow-600 font-semibold">{{ $template->reward_coins }} 🪙</span>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('admin.quest-templates.edit', $template) }}" class="w-full inline-block text-center bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2 px-3 rounded transition">
                            Edit
                        </a>
                    @else
                        <div class="text-center py-6">
                            <p class="text-sm text-gray-500 mb-3">No template configured</p>
                            <p class="text-xs text-gray-400">Click "Initialize Default Templates" to create templates for all days</p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endsection
