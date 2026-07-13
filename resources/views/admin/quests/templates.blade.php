@extends('layouts.admin')

@section('title', 'Daily Quest Templates')
@section('subtitle', 'Manage quest templates for each day of the week')

@section('content')
    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($templates as $dayOfWeek => $template)
            <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                <!-- Day Header -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-3 text-white">
                    <h3 class="font-bold text-lg">{{ $template['day_name'] }}</h3>
                </div>

                <!-- Content -->
                <div class="p-4">
                    @if($template['quest'])
                        <div class="space-y-3 mb-4">
                            <div>
                                <p class="text-xs text-gray-500 font-semibold uppercase">Title</p>
                                <p class="text-sm font-medium text-gray-800">{{ $template['quest']->title }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-semibold uppercase">Description</p>
                                <p class="text-sm text-gray-600">{{ Str::limit($template['quest']->description, 60) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-semibold uppercase">Type</p>
                                <span class="inline-block px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded font-semibold">
                                    {{ str_replace('_', ' ', ucfirst($template['quest']->type)) }}
                                </span>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase">Target</p>
                                    <p class="text-sm font-bold text-gray-800">{{ $template['quest']->target }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase">Rewards</p>
                                    <p class="text-xs">
                                        <span class="text-orange-600 font-bold">{{ $template['quest']->reward_exp }}</span> XP
                                        @if($template['quest']->reward_coins > 0)
                                            <br><span class="text-yellow-600 font-bold">{{ $template['quest']->reward_coins }}</span> 🪙
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="py-6 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <p class="text-gray-500 text-sm">No quest template set</p>
                        </div>
                    @endif
                </div>

                <!-- Edit Button -->
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
                    <a href="{{ route('admin.quests.template.edit', $dayOfWeek) }}" class="w-full inline-block text-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded hover:bg-blue-700 transition">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Info Box -->
    <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <div class="flex">
            <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
            <div>
                <h4 class="font-semibold text-blue-900 mb-1">How Daily Quest Templates Work</h4>
                <p class="text-sm text-blue-800">Set up a template for each day of the week. These templates will be used to generate daily quests automatically each day for all users.</p>
            </div>
        </div>
    </div>
@endsection
