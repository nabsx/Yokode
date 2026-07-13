@extends('layouts.admin')

@section('title', 'Edit Lesson')
@section('subtitle', $lesson->title)

@section('content')
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800">Edit Lesson</h3>
        </div>

        <form action="{{ route('admin.lessons.update', $lesson) }}" method="POST" class="p-6" enctype="multipart/form-data">
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

                <!-- Banner Image -->
                <div class="md:col-span-2">
                    <label for="banner_image" class="block text-sm font-medium text-gray-700 mb-2">Banner Image (Foto)</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 cursor-pointer hover:border-blue-500 transition" id="image-drop-zone">
                        <input 
                            type="file" 
                            id="banner_image" 
                            name="banner_image"
                            accept="image/jpeg,image/png,image/webp"
                            class="hidden"
                            data-max-size="5242880"
                        >
                        <div class="text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-gray-700 font-medium">Klik untuk memilih atau drag & drop foto</p>
                            <p class="text-gray-500 text-sm mt-1">JPG, PNG atau WebP (Max 5MB)</p>
                        </div>
                    </div>
                    
                    <!-- Image Preview -->
                    <div id="image-preview" class="mt-4 hidden">
                        <div class="relative inline-block">
                            <img id="preview-img" src="" alt="Preview" class="max-h-64 rounded-lg shadow">
                            <button type="button" id="remove-image-btn" class="absolute top-2 right-2 bg-red-600 text-white rounded-full p-1 hover:bg-red-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Current Image Display -->
                    @if($lesson->banner_image)
                        <div id="current-image" class="mt-4">
                            <p class="text-sm font-medium text-gray-700 mb-2">Foto Banner Saat Ini:</p>
                            <div class="relative inline-block">
                                <img src="{{ $lesson->banner_image }}" alt="Current Banner" class="max-h-64 rounded-lg shadow">
                                <button type="button" id="remove-current-btn" class="absolute top-2 right-2 bg-red-600 text-white rounded-full p-1 hover:bg-red-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            <input type="hidden" id="remove-image-flag" name="remove_banner_image" value="0">
                        </div>
                    @endif
                    
                    @error('banner_image')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content -->
                <div class="md:col-span-2">
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content (HTML allowed) *</label>
                    <textarea 
                        id="content" 
                        name="content"
                        rows="8"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('content') border-red-500 @enderror font-mono"
                        required
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

                <!-- Premium Option -->
                <div class="md:col-span-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input 
                            type="checkbox" 
                            name="is_premium" 
                            value="1"
                            class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                            @if(old('is_premium', $lesson->is_premium)) checked @endif
                        >
                        <span class="text-sm font-medium text-gray-700">
                            <i class="fas fa-star text-yellow-500 mr-2"></i>Premium Lesson Only
                        </span>
                        <span class="text-xs text-gray-500">(Only premium users can access this lesson)</span>
                    </label>
                    @error('is_premium')
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

    <script>
        // Image upload handling
        const imageInput = document.getElementById('banner_image');
        const dropZone = document.getElementById('image-drop-zone');
        const imagePreview = document.getElementById('image-preview');
        const previewImg = document.getElementById('preview-img');
        const removeBtn = document.getElementById('remove-image-btn');
        const currentImage = document.getElementById('current-image');
        const removeCurrentBtn = document.getElementById('remove-current-btn');
        const removeImageFlag = document.getElementById('remove-image-flag');
        const maxSize = parseInt(imageInput.dataset.maxSize);

        // Click to select
        dropZone.addEventListener('click', function() {
            imageInput.click();
        });

        // File selected
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                handleImageFile(file);
            }
        });

        // Drag and drop
        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            dropZone.classList.add('border-blue-500', 'bg-blue-50');
        });

        dropZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            dropZone.classList.remove('border-blue-500', 'bg-blue-50');
        });

        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            dropZone.classList.remove('border-blue-500', 'bg-blue-50');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                const file = files[0];
                if (file.type.startsWith('image/')) {
                    imageInput.files = files;
                    handleImageFile(file);
                } else {
                    alert('Please select an image file');
                }
            }
        });

        // Handle image file
        function handleImageFile(file) {
            if (file.size > maxSize) {
                alert('File size must be less than 5MB');
                imageInput.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.classList.remove('hidden');
                
                // Hide current image when new one is selected
                if (currentImage) {
                    currentImage.classList.add('hidden');
                    removeImageFlag.value = '0'; // Reset flag
                }
            };
            reader.readAsDataURL(file);
        }

        // Remove selected image
        if (removeBtn) {
            removeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                imageInput.value = '';
                imagePreview.classList.add('hidden');
                
                // Show current image again if exists
                if (currentImage && removeImageFlag.value === '0') {
                    currentImage.classList.remove('hidden');
                }
            });
        }

        // Remove current image
        if (removeCurrentBtn) {
            removeCurrentBtn.addEventListener('click', function(e) {
                e.preventDefault();
                currentImage.classList.add('hidden');
                removeImageFlag.value = '1'; // Mark for deletion
            });
        }
    </script>
@endsection
