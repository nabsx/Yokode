@extends('layouts.admin')

@section('title', 'Edit Category')
@section('subtitle', $category->name)

@section('content')
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800">Edit Category</h3>
        </div>

        <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Category Name *</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name', $category->name) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                        required
                    >
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Icon -->
                <div>
                    <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">Icon (Emoji)</label>
                    <input 
                        type="text" 
                        id="icon" 
                        name="icon" 
                        value="{{ old('icon', $category->icon) }}"
                        placeholder="e.g., 📚"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('icon') border-red-500 @enderror"
                    >
                    @error('icon')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea 
                        id="description" 
                        name="description"
                        rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                    >{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Order -->
                <div>
                    <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                    <input 
                        type="number" 
                        id="order" 
                        name="order" 
                        value="{{ old('order', $category->order) }}"
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
                <a href="{{ route('admin.categories.index') }}" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition font-medium">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
