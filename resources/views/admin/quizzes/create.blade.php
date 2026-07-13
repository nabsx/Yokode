@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Create Quiz</h1>
        <p class="text-gray-600 mt-2">Add a new quiz question to a lesson</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.quizzes.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Lesson Selection -->
            <div>
                <label for="lesson_id" class="block text-sm font-semibold text-gray-700 mb-2">Lesson</label>
                <select name="lesson_id" id="lesson_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('lesson_id') border-red-500 @enderror" required>
                    <option value="">-- Select Lesson --</option>
                    @foreach($lessons as $lesson)
                        <option value="{{ $lesson->id }}" @selected(old('lesson_id') == $lesson->id)>
                            {{ $lesson->title }} (Category: {{ $lesson->category->name ?? 'N/A' }})
                        </option>
                    @endforeach
                </select>
                @error('lesson_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Question -->
            <div>
                <label for="question" class="block text-sm font-semibold text-gray-700 mb-2">Question</label>
                <textarea name="question" id="question" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('question') border-red-500 @enderror" placeholder="Enter the quiz question" required>{{ old('question') }}</textarea>
                @error('question')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Options -->
            <div class="space-y-3">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Answer Options (2-4)</label>
                
                @for($i = 0; $i < 4; $i++)
                    <div class="flex gap-2">
                        <span class="flex items-center px-3 py-2 bg-gray-100 text-gray-700 font-semibold rounded-lg">Option {{ chr(65 + $i) }}</span>
                        <input type="text" name="options[]" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter option text" value="{{ old('options.' . $i) }}">
                    </div>
                @endfor
                @error('options')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-2">* Leave empty for options you don't need</p>
            </div>

            <!-- Correct Answer -->
            <div>
                <label for="correct_answer" class="block text-sm font-semibold text-gray-700 mb-2">Correct Answer</label>
                <select name="correct_answer" id="correct_answer" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('correct_answer') border-red-500 @enderror" required>
                    <option value="">-- Select Correct Answer --</option>
                    <option value="0" @selected(old('correct_answer') == '0')>Option A</option>
                    <option value="1" @selected(old('correct_answer') == '1')>Option B</option>
                    <option value="2" @selected(old('correct_answer') == '2')>Option C</option>
                    <option value="3" @selected(old('correct_answer') == '3')>Option D</option>
                </select>
                @error('correct_answer')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Points (Dropdown with increments of 10) -->
            <div>
                <label for="points" class="block text-sm font-semibold text-gray-700 mb-2">Points</label>
                <select name="points" id="points" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('points') border-red-500 @enderror" required>
                    <option value="">-- Select Points --</option>
                    @php
                        $pointsMultiples = [10, 20, 30, 40, 50, 60, 70, 80, 90, 100, 110, 120, 130, 140, 150];
                    @endphp
                    @foreach($pointsMultiples as $points)
                        <option value="{{ $points }}" @selected(old('points', '10') == $points)>
                            {{ $points }} points ({{ intdiv($points, 10) }} coin)
                        </option>
                    @endforeach
                </select>
                @error('points')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-2">* Points akan dikonversi menjadi coin (10 points = 1 coin)</p>
            </div>

            

            <!-- Actions -->
            <div class="flex gap-3 pt-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-plus mr-2"></i>Create Quiz
                </button>
                <a href="{{ route('admin.quizzes.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-400 transition">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
