@extends('layouts.admin')

@section('title', 'Edit Daily Quest Template - ' . $template->getDayName())
@section('subtitle', 'Configure the daily quest for ' . $template->getDayNameEn())

@section('content')
    <div class="grid grid-cols-1 max-w-2xl">
        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('admin.quest-templates.update', $template) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Day Information -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm font-semibold text-gray-700 mb-2">Day of Week</p>
                    <p class="text-lg font-bold text-gray-800">{{ $template->getDayName() }} ({{ $template->getDayNameEn() }})</p>
                </div>

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Quest Title</label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="{{ old('title', $template->title) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-500 @enderror"
                        placeholder="e.g., Monday Lesson Challenge"
                        required
                    >
                    @error('title')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                        placeholder="Describe what users need to do for this quest"
                        required
                    >{{ old('description', $template->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type -->
                <div>
                    <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">Quest Type</label>
                    <select
                        id="type"
                        name="type"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('type') border-red-500 @enderror"
                        required
                    >
                        <option value="">-- Select Type --</option>
                        <option value="complete_lesson" @selected(old('type', $template->type) === 'complete_lesson')>Complete Lesson</option>
                        <option value="answer_quiz" @selected(old('type', $template->type) === 'answer_quiz')>Answer Quiz</option>
                        <option value="gain_exp" @selected(old('type', $template->type) === 'gain_exp')>Gain Experience</option>
                        <option value="login" @selected(old('type', $template->type) === 'login')>Login</option>
                        <option value="perfect_quiz" @selected(old('type', $template->type) === 'perfect_quiz')>Perfect Quiz</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Target -->
                <div>
                    <label for="target" class="block text-sm font-semibold text-gray-700 mb-2">Target (how much to complete)</label>
                    <input
                        type="number"
                        id="target"
                        name="target"
                        value="{{ old('target', $template->target) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('target') border-red-500 @enderror"
                        placeholder="e.g., 5"
                        min="1"
                        max="1000"
                        required
                    >
                    @error('target')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rewards -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="reward_exp" class="block text-sm font-semibold text-gray-700 mb-2">Experience Reward (XP)</label>
                        <input
                            type="number"
                            id="reward_exp"
                            name="reward_exp"
                            value="{{ old('reward_exp', $template->reward_exp) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('reward_exp') border-red-500 @enderror"
                            placeholder="e.g., 100"
                            min="0"
                            max="10000"
                            required
                        >
                        @error('reward_exp')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="reward_coins" class="block text-sm font-semibold text-gray-700 mb-2">Coins Reward (🪙)</label>
                        <input
                            type="number"
                            id="reward_coins"
                            name="reward_coins"
                            value="{{ old('reward_coins', $template->reward_coins) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('reward_coins') border-red-500 @enderror"
                            placeholder="e.g., 50"
                            min="0"
                            max="10000"
                            required
                        >
                        @error('reward_coins')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Preview -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm font-semibold text-blue-900 mb-3">Preview</p>
                    <div class="bg-white rounded p-3 text-sm">
                        <p class="font-semibold text-gray-800"><span id="preview-title">{{ $template->title }}</span></p>
                        <p class="text-gray-600 text-xs mb-2"><span id="preview-description">{{ $template->description }}</span></p>
                        <div class="flex gap-3 text-xs">
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded"><span id="preview-type">{{ str_replace('_', ' ', $template->type) }}</span></span>
                            <span class="text-orange-600 font-semibold">Target: <span id="preview-target">{{ $template->target }}</span></span>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-200 flex gap-3">
                            <span class="text-orange-600 font-bold"><span id="preview-exp">{{ $template->reward_exp }}</span> XP</span>
                            <span class="text-yellow-600 font-bold"><span id="preview-coins">{{ $template->reward_coins }}</span> 🪙</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <button
                        type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition"
                    >
                        Save Changes
                    </button>
                    <a
                        href="{{ route('admin.quest-templates.index') }}"
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded text-center transition"
                    >
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Live preview update
        document.querySelectorAll('input, select, textarea').forEach(el => {
            el.addEventListener('input', function() {
                if (this.name === 'title') {
                    document.getElementById('preview-title').textContent = this.value || 'Quest Title';
                } else if (this.name === 'description') {
                    document.getElementById('preview-description').textContent = this.value || 'Description';
                } else if (this.name === 'type') {
                    document.getElementById('preview-type').textContent = this.value.replace('_', ' ') || 'Type';
                } else if (this.name === 'target') {
                    document.getElementById('preview-target').textContent = this.value || '0';
                } else if (this.name === 'reward_exp') {
                    document.getElementById('preview-exp').textContent = this.value || '0';
                } else if (this.name === 'reward_coins') {
                    document.getElementById('preview-coins').textContent = this.value || '0';
                }
            });
        });
    </script>
@endsection
