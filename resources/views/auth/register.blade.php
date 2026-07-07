<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar - YoKode</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-green-500 to-teal-600 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="text-6xl mb-3">🎮</div>
            <h1 class="text-4xl font-bold text-white mb-2">YoKode</h1>
            <p class="text-green-100">Mulai Petualangan Belajarmu!</p>
        </div>
        
        <!-- Card Register -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Buat Akun Baru</h2>
            
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <!-- Name -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        👤 Nama Lengkap
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                           placeholder="Masukkan nama lengkap">
                </div>
                
                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        📧 Email
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                           placeholder="contoh@email.com">
                    <p class="text-xs text-gray-500 mt-1">Kami tidak akan membagikan email Anda</p>
                </div>
                
                <!-- Password -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        🔒 Password
                    </label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                           placeholder="Minimal 8 karakter">
                </div>
                
                <!-- Confirm Password -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        🔒 Konfirmasi Password
                    </label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                           placeholder="Masukkan password lagi">
                </div>
                
                <!-- Button Register -->
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 text-white font-bold py-3 rounded-xl transition duration-200 transform hover:scale-[1.02]">
                    ✨ Daftar Sekarang
                </button>
            </form>
            
            <!-- Login Link -->
            <div class="mt-6 text-center">
                <p class="text-gray-600">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-green-600 font-semibold hover:underline">
                        Masuk
                    </a>
                </p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-6 text-green-100 text-sm">
            <p>© 2026 YoKode - Belajar Coding Jadi Menyenangkan 🎯</p>
        </div>
    </div>
</body>
</html>