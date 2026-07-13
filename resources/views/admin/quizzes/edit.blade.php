@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Edit Quiz</h1>
        <p class="text-gray-600 mt-2">Update quiz question and answers</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.quizzes.update', $quiz) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Lesson Selection -->
            <div>
                <label for="lesson_id" class="block text-sm font-semibold text-gray-700 mb-2">Lesson</label>
                <select name="lesson_id" id="lesson_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('lesson_id') border-red-500 @enderror" required>
                    <option value="">-- Select Lesson --</option>
                    @foreach($lessons as $lesson)
                        <option value="{{ $lesson->id }}" @selected($quiz->lesson_id == $lesson->id)>
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
                <textarea name="question" id="question" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('question') border-red-500 @enderror" placeholder="Enter the quiz question" required>{{ $quiz->question }}</textarea>
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
                        <input type="text" name="options[]" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter option text" value="{{ $quiz->options[$i] ?? '' }}">
                    </div>
                @endfor
                @error('options')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Correct Answer -->
            <div>
                <label for="correct_answer" class="block text-sm font-semibold text-gray-700 mb-2">Correct Answer</label>
                <select name="correct_answer" id="correct_answer" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('correct_answer') border-red-500 @enderror" required>
                    <option value="">-- Select Correct Answer --</option>
                    <option value="0" @selected($quiz->correct_answer == 0)>Option A</option>
                    <option value="1" @selected($quiz->correct_answer == 1)>Option B</option>
                    <option value="2" @selected($quiz->correct_answer == 2)>Option C</option>
                    <option value="3" @selected($quiz->correct_answer == 3)>Option D</option>
                </select>
                @error('correct_answer')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Points -->
            <div>
                <label for="points" class="block text-sm font-semibold text-gray-700 mb-2">Points</label>
                <input type="number" name="points" id="points" min="1" max="1000" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('points') border-red-500 @enderror" placeholder="Points for correct answer" value="{{ $quiz->points }}" required>
                @error('points')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Statistics -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="font-semibold text-blue-900 mb-2">Quiz Statistics</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-blue-700">Total Answers:</span>
                        <span class="font-bold text-blue-900">{{ $quiz->userAnswers->count() }}</span>
                    </div>
                    <div>
                        <span class="text-blue-700">Correct Answers:</span>
                        <span class="font-bold text-green-600">{{ $quiz->userAnswers->where('is_correct', true)->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-3 pt-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i>Update Quiz
                </button>
                <a href="{{ route('admin.quizzes.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-400 transition">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
            </div>
        </form>

        <!-- Delete Form (Outside Update Form) -->
        <form action="{{ route('admin.quizzes.destroy', $quiz) }}" method="POST" class="mt-6" onsubmit="return confirm('Are you sure you want to delete this quiz?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-6 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition">
                <i class="fas fa-trash mr-2"></i>Delete Quiz
            </button>
        </form>
    </div>
</div>
@endsection
