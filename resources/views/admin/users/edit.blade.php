@extends('layouts.admin')

@section('title', 'Edit User')
@section('subtitle', $user->name)

@section('content')
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800">Edit User Information</h3>
        </div>

        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name', $user->name) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                        required
                    >
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email', $user->email) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                        required
                    >
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Total Experience -->
                <div>
                    <label for="total_exp" class="block text-sm font-medium text-gray-700 mb-2">Total Experience (XP)</label>
                    <input 
                        type="number" 
                        id="total_exp" 
                        name="total_exp" 
                        value="{{ old('total_exp', $user->total_exp) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('total_exp') border-red-500 @enderror"
                        min="0"
                    >
                    @error('total_exp')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Coins -->
                <div>
                    <label for="coins" class="block text-sm font-medium text-gray-700 mb-2">Coins</label>
                    <input 
                        type="number" 
                        id="coins" 
                        name="coins" 
                        value="{{ old('coins', $user->coins) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('coins') border-red-500 @enderror"
                        min="0"
                    >
                    @error('coins')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Premium Status -->
                <div>
                    <label for="is_premium" class="block text-sm font-medium text-gray-700 mb-2">Premium Status</label>
                    <select 
                        id="is_premium" 
                        name="is_premium"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('is_premium') border-red-500 @enderror"
                    >
                        <option value="0" {{ old('is_premium', $user->is_premium) == 0 ? 'selected' : '' }}>Free</option>
                        <option value="1" {{ old('is_premium', $user->is_premium) == 1 ? 'selected' : '' }}>Premium</option>
                    </select>
                    @error('is_premium')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Premium Expires At -->
                <div>
                    <label for="premium_expires_at" class="block text-sm font-medium text-gray-700 mb-2">Premium Expires At (Optional)</label>
                    <input 
                        type="date" 
                        id="premium_expires_at" 
                        name="premium_expires_at" 
                        value="{{ old('premium_expires_at', $user->premium_expires_at ? $user->premium_expires_at->format('Y-m-d') : '') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('premium_expires_at') border-red-500 @enderror"
                    >
                    @error('premium_expires_at')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-blue-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    Adjusting these values will directly update the user's account. Use with caution.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
                <a href="{{ route('admin.users.show', $user) }}" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition font-medium">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
