# Lesson Banner Image Upload Feature

## Overview
This implementation adds photo/image upload functionality to lessons. Admins can now upload banner images when creating or editing lessons, and these images will be displayed at the top of lessons in the user view.

## Changes Made

### 1. Database Migration
**File:** `/database/migrations/2026_07_13_150000_add_banner_image_to_lessons_table.php`
- Adds `banner_image` column to the `lessons` table
- Column type: `string`, nullable
- Stores the URL/path to the uploaded image

### 2. Lesson Model
**File:** `/app/Models/Lesson.php`
- Added `banner_image` to the `$fillable` array to allow mass assignment

### 3. Admin Controller
**File:** `/app/Http/Controllers/AdminController.php`
- Updated `lessonsStore()` method:
  - Added validation for image uploads (jpg, png, webp, max 5MB)
  - Stores images in `storage/app/public/lessons/` directory
  - Converts file path to public URL using `Storage::url()`
  
- Updated `lessonsUpdate()` method:
  - Added validation for image uploads
  - Supports adding new images
  - Supports removing existing images via `remove_banner_image` flag
  - Handles file replacement if new image is uploaded

### 4. Admin Forms - Create Lesson
**File:** `/resources/views/admin/lessons/create.blade.php`
- Added image upload section with drag-and-drop functionality
- Features:
  - Click to select or drag & drop images
  - Real-time preview of selected image
  - File size validation (max 5MB)
  - File type validation (JPG, PNG, WebP only)
  - Remove button to clear selected image
  - Form enctype set to `multipart/form-data`
  - JavaScript for image preview and file handling

### 5. Admin Forms - Edit Lesson
**File:** `/resources/views/admin/lessons/edit.blade.php`
- Added image upload section (same as create form)
- Additional features:
  - Displays current banner image if one exists
  - Option to remove current image with a button
  - Allows replacing current image with a new one
  - Form enctype set to `multipart/form-data`
  - JavaScript for managing both current and new images

### 6. User Lesson View
**File:** `/resources/views/lessons/show.blade.php`
- Added banner image display at the top of lesson content
- Features:
  - Full-width banner image (h-64, object-cover fit)
  - Displays only if image exists (backward compatible)
  - Positioned before title and content
  - Responsive design with proper spacing

## How to Test

### 1. Create a New Lesson with Image
1. Go to Admin Panel → Lessons → Create New Lesson
2. Fill in the form fields (Title, Category, Content, etc.)
3. Click on the image upload area or drag & drop an image
4. Select a JPG, PNG, or WebP file (under 5MB)
5. Preview should appear showing the selected image
6. Submit the form
7. Image should be stored and visible in the lesson view

### 2. Edit a Lesson to Add/Replace Image
1. Go to Admin Panel → Lessons → Edit
2. Select an existing lesson
3. If lesson has no image, use the upload area to add one
4. If lesson has an image, it displays with a remove button
5. Click remove to delete current image, or select new one to replace
6. Submit the form
7. Changes should be reflected in the user's lesson view

### 3. View Lesson with Banner Image
1. Navigate to any lesson with a banner image
2. Banner should display at the top (full-width, height 256px)
3. Image should maintain aspect ratio and cover the space
4. Lesson content should appear below the banner

### 4. Test Validation
- Try uploading a file > 5MB (should show error)
- Try uploading a non-image file (should show error)
- Try uploading unsupported formats (should show error)

## File Structure
```
storage/
  app/
    public/
      lessons/          ← Images stored here
        image_name.jpg
        image_name.png
```

## Storage Configuration
Images are stored using Laravel's public disk. Ensure the following:
1. Symbolic link exists: `public/storage` → `storage/app/public`
2. Run `php artisan storage:link` if symbolic link doesn't exist
3. Proper file permissions on storage directory

## Notes
- Images are stored on the server (local public storage)
- For production, consider migrating to Vercel Blob or similar CDN service
- All image operations include proper validation
- Backward compatibility maintained - lessons without images work normally
- Images are stored with timestamp-based naming to avoid conflicts

## Future Enhancements
1. Integrate with Vercel Blob for cloud storage
2. Add image cropping/resizing tools
3. Add image optimization
4. Support for multiple images per lesson
5. Image gallery feature in lessons
