@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white rounded-2xl shadow-xl p-8">
        <div class="text-center">
            <!-- Icon -->
            <div class="text-6xl mb-4">🔐</div>
            
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Konfirmasi Password</h2>
            <p class="text-gray-600 mb-6">Silakan konfirmasi password Anda sebelum melanjutkan</p>
        </div>
        
        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf
            
            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">
                    🔒 Password
                </label>
                <input id="password" type="password" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('password') border-red-500 @enderror" 
                       name="password" required autocomplete="current-password" 
                       placeholder="Masukkan password Anda">
                
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Buttons -->
            <div class="flex flex-col gap-3">
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-3 rounded-xl transition duration-200">
                    🔓 Konfirmasi Password
                </button>
                
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" 
                       class="text-center text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Lupa Password?
                    </a>
                @endif
            </div>
        </form>
        
        <!-- Back to Dashboard -->
        <div class="mt-6 text-center">
            <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 text-sm">
                ← Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection