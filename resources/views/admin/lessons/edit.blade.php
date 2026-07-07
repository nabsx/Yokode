@extends('layouts.admin')

@section('title', 'Edit Lesson')
@section('subtitle', $lesson->title)

@section('content')
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800">Edit Lesson</h3>
        </div>

        <form action="{{ route('admin.lessons.update', $lesson) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title" 
                        value="{{ old('title', $lesson->title) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                        required
                    >
                    @error('title')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select 
                        id="category_id" 
                        name="category_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('category_id') border-red-500 @enderror"
                        required
                    >
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $lesson->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Difficulty -->
                <div>
                    <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-2">Difficulty</label>
                    <select 
                        id="difficulty" 
                        name="difficulty"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('difficulty') border-red-500 @enderror"
                    >
                        <option value="">Select Difficulty</option>
                        <option value="easy" {{ old('difficulty', $lesson->difficulty) === 'easy' ? 'selected' : '' }}>Easy</option>
                        <option value="medium" {{ old('difficulty', $lesson->difficulty) === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="hard" {{ old('difficulty', $lesson->difficulty) === 'hard' ? 'selected' : '' }}>Hard</option>
                    </select>
                    @error('difficulty')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea 
                        id="description" 
                        name="description"
                        rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                        required
                    >{{ old('description', $lesson->description) }}</textarea>
                    @error('description')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content -->
                <div class="md:col-span-2">
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content (HTML allowed)</label>
                    <textarea 
                        id="content" 
                        name="content"
                        rows="8"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('content') border-red-500 @enderror font-mono"
                    >{{ old('content', $lesson->content) }}</textarea>
                    @error('content')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Order -->
                <div>
                    <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Order</label>
                    <input 
                        type="number" 
                        id="order" 
                        name="order" 
                        value="{{ old('order', $lesson->order) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('order') border-red-500 @enderror"
                    >
                    @error('order')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
                <a href="{{ route('admin.lessons.index') }}" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition font-medium">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
