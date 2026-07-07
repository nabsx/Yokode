@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white rounded-2xl shadow-xl p-8">
        <div class="text-center">
            <div class="text-6xl mb-4">🔑</div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Lupa Password?</h2>
            <p class="text-gray-600 mb-6">Masukkan email Anda, kami akan kirimkan link reset password</p>
        </div>
        
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">
                ✅ {{ session('status') }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            
            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">
                    📧 Email
                </label>
                <input id="email" type="email" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('email') border-red-500 @enderror" 
                       name="email" value="{{ old('email') }}" required autofocus
                       placeholder="contoh@email.com">
                
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Button -->
            <button type="submit" 
                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-3 rounded-xl transition duration-200">
                📩 Kirim Link Reset Password
            </button>
        </form>
        
        <!-- Back to Login -->
        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700 text-sm">
                ← Kembali ke Halaman Login
            </a>
        </div>
    </div>
</div>
@endsection