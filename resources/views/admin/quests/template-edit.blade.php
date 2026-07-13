@extends('layouts.admin')

@section('title', "Edit $dayName Quest Template")
@section('subtitle', "Configure the daily quest template for $dayName")

@section('content')
    <div class="bg-white rounded-lg shadow">
        <form action="{{ route('admin.quests.template.update', $dayOfWeek) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Day of Week Display -->
                <div class="md:col-span-2">
                    <div class="inline-block px-4 py-2 bg-blue-100 text-blue-800 rounded-lg font-semibold">
                        {{ $dayName }}
                    </div>
                </div>

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Quest Title *</label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title"
                        value="{{ old('title', $quest->title ?? '') }}"
                        placeholder="e.g., Complete 3 Lessons"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                        required
                    >
                    @error('title')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Quest Type *</label>
                    <select 
                        id="type" 
                        name="type"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('type') border-red-500 @enderror"
                        required
                    >
                        <option value="">Select a type...</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}" {{ old('type', $quest->type ?? '') == $type ? 'selected' : '' }}>
                                {{ str_replace('_', ' ', ucfirst($type)) }}
                            </option>
                        @endforeach
                    </select>
                    @error('type')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea 
                        id="description" 
                        name="description"
                        rows="3"
                        placeholder="Describe what users need to do to complete this quest..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                        required
                    >{{ old('description', $quest->description ?? '') }}</textarea>
                    @error('description')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Target -->
                <div>
                    <label for="target" class="block text-sm font-medium text-gray-700 mb-2">Target Number *</label>
                    <input 
                        type="number" 
                        id="target" 
                        name="target"
                        value="{{ old('target', $quest->target ?? '1') }}"
                        placeholder="e.g., 3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('target') border-red-500 @enderror"
                        min="1"
                        required
                    >
                    @error('target')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Reward XP -->
                <div>
                    <label for="reward_exp" class="block text-sm font-medium text-gray-700 mb-2">Reward XP *</label>
                    <input 
                        type="number" 
                        id="reward_exp" 
                        name="reward_exp"
                        value="{{ old('reward_exp', $quest->reward_exp ?? '100') }}"
                        placeholder="e.g., 100"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('reward_exp') border-red-500 @enderror"
                        min="0"
                        required
                    >
                    @error('reward_exp')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Reward Coins -->
                <div>
                    <label for="reward_coins" class="block text-sm font-medium text-gray-700 mb-2">Reward Coins (Optional)</label>
                    <input 
                        type="number" 
                        id="reward_coins" 
                        name="reward_coins"
                        value="{{ old('reward_coins', $quest->reward_coins ?? '0') }}"
                        placeholder="e.g., 50"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('reward_coins') border-red-500 @enderror"
                        min="0"
                    >
                    @error('reward_coins')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Preview Card -->
            <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                <h4 class="font-semibold text-gray-800 mb-3">Preview</h4>
                <div class="bg-white p-4 rounded border border-gray-200">
                    <div class="space-y-2">
                        <p class="font-semibold text-gray-900" id="preview-title">{{ $quest->title ?? 'Quest Title' }}</p>
                        <p class="text-sm text-gray-600" id="preview-description">{{ $quest->description ?? 'Quest description...' }}</p>
                        <div class="flex gap-4 mt-3">
                            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded" id="preview-type">
                                {{ str_replace('_', ' ', ucfirst($quest->type ?? 'Type')) }}
                            </span>
                            <span class="text-sm text-gray-600">
                                Target: <span class="font-bold" id="preview-target">{{ $quest->target ?? '1' }}</span>
                            </span>
                        </div>
                        <div class="flex gap-4 mt-3 text-sm">
                            <span class="text-orange-600 font-semibold">
                                <span id="preview-reward-exp">{{ $quest->reward_exp ?? '100' }}</span> XP
                            </span>
                            @if($quest && $quest->reward_coins > 0)
                                <span class="text-yellow-600 font-semibold">
                                    <span id="preview-reward-coins">{{ $quest->reward_coins ?? '0' }}</span> 🪙
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    <i class="fas fa-save mr-2"></i>Save Template
                </button>
                <a href="{{ route('admin.quests.templates') }}" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition font-medium">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
        // Live preview update
        document.getElementById('title').addEventListener('input', function(e) {
            document.getElementById('preview-title').textContent = e.target.value || 'Quest Title';
        });

        document.getElementById('description').addEventListener('input', function(e) {
            document.getElementById('preview-description').textContent = e.target.value || 'Quest description...';
        });

        document.getElementById('type').addEventListener('change', function(e) {
            const typeText = e.target.options[e.target.selectedIndex].text;
            document.getElementById('preview-type').textContent = typeText;
        });

        document.getElementById('target').addEventListener('input', function(e) {
            document.getElementById('preview-target').textContent = e.target.value || '1';
        });

        document.getElementById('reward_exp').addEventListener('input', function(e) {
            document.getElementById('preview-reward-exp').textContent = e.target.value || '100';
        });

        document.getElementById('reward_coins').addEventListener('input', function(e) {
            document.getElementById('preview-reward-coins').textContent = e.target.value || '0';
        });
    </script>
@endsection
