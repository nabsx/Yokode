<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YoKode - Belajar Logika Pemrograman dengan Gamifikasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .animate-pulse-slow {
            animation: pulseSlow 2s ease-in-out infinite;
        }
        @keyframes pulseSlow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-500 to-purple-600 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-white/10 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2">
                <img src="{{ asset('images/logo.png') }}" alt="YoKode" class="h-8 w-auto">
                    <span class="text-2xl font-bold text-white">YoKode</span>
                </div>
                <div class="flex gap-4">
                    <a href="{{ route('login') }}" class="text-white hover:text-blue-200 px-4 py-2 transition">Login</a>
                    <a href="{{ route('register') }}" class="bg-white text-purple-600 px-6 py-2 rounded-full font-semibold hover:bg-blue-50 transition transform hover:scale-105">Daftar Gratis</a>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto px-4 py-20">
        <div class="text-center">
            <!-- <div class="text-8xl mb-6 animate-bounce">🎯</div> -->
            <h1 class="text-5xl md:text-7xl font-extrabold text-white mb-4">
                Belajar Coding<br>
                <span class="text-yellow-300">Serasa Main Game!</span>
            </h1>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                Mulai petualanganmu dengan sistem gamifikasi yang membuat belajar logika pemrograman jadi menyenangkan!
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" 
                   class="inline-block bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold py-4 px-8 rounded-full text-lg transition transform hover:scale-105">
                    Mulai Belajar Gratis
                </a>
                <a href="#features" 
                   class="inline-block bg-white/20 backdrop-blur-md hover:bg-white/30 text-white font-bold py-4 px-8 rounded-full text-lg transition">
                    Lihat Fitur
                </a>
            </div>
            
            <!-- Stats -->
            <div class="flex flex-wrap justify-center gap-8 mt-12">
                <div class="text-center">
                    <div class="text-3xl font-bold text-white">5000+</div>
                    <div class="text-blue-100">Siswa Aktif</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-white">100+</div>
                    <div class="text-blue-100">Modul Belajar</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-white">50+</div>
                    <div class="text-blue-100">Kuis Interaktif</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Features Section -->
    <div id="features" class="bg-white py-20">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                    Fitur Unggulan YoKode
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Belajar pemrograman jadi lebih seru dengan berbagai fitur gamifikasi yang kami sediakan
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 text-center hover:shadow-xl transition transform hover:-translate-y-1">
                    <div class="text-6xl mb-4">❤️</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Hearts System</h3>
                    <p class="text-gray-600">Setiap jawaban salah mengurangi nyawa. Belajar lebih hati-hati dan fokus!</p>
                </div>
                
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-2xl p-6 text-center hover:shadow-xl transition transform hover:-translate-y-1">
                    <div class="text-6xl mb-4">🔥</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Daily Streak</h3>
                    <p class="text-gray-600">Login setiap hari untuk menjaga streak dan dapatkan bonus EXP!</p>
                </div>
                
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-6 text-center hover:shadow-xl transition transform hover:-translate-y-1">
                    <div class="text-6xl mb-4">🏆</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Achievements</h3>
                    <p class="text-gray-600">Raih berbagai pencapaian keren dan tunjukkan ke hebatmu!</p>
                </div>
                
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-6 text-center hover:shadow-xl transition transform hover:-translate-y-1">
                    <div class="text-6xl mb-4">👑</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">League System</h3>
                    <p class="text-gray-600">Naikkan ligamu dari Bronze hingga Legend!</p>
                </div>
                
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-2xl p-6 text-center hover:shadow-xl transition transform hover:-translate-y-1">
                    <div class="text-6xl mb-4">📅</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Daily Quests</h3>
                    <p class="text-gray-600">Selesaikan misi harian dan dapatkan EXP serta koin!</p>
                </div>
                
                <div class="bg-gradient-to-br from-pink-50 to-pink-100 rounded-2xl p-6 text-center hover:shadow-xl transition transform hover:-translate-y-1">
                    <div class="text-6xl mb-4">🛒</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Shop System</h3>
                    <p class="text-gray-600">Tukarkan koinmu dengan hearts, booster, dan item keren lainnya!</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Promo Section -->
    <div class="bg-gradient-to-r from-yellow-400 to-orange-500 py-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="text-5xl mb-4 animate-pulse-slow">🎉</div>
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                PROMO SPESIAL!
            </h2>
            <p class="text-xl text-white/90 mb-6 max-w-2xl mx-auto">
                Dapatkan akses PREMIUM gratis selama 7 hari untuk 100 pendaftar pertama!
            </p>
            <div class="bg-white/20 backdrop-blur-md rounded-2xl p-4 inline-block">
                <p class="text-white font-mono text-2xl font-bold">
                    KODE PROMO: YOKODE7
                </p>
            </div>
            <div class="mt-8">
                <a href="{{ route('register') }}" 
                   class="inline-block bg-white text-orange-600 font-bold py-3 px-8 rounded-full text-lg hover:bg-gray-100 transition transform hover:scale-105">
                    🎁 Klaim Promo Sekarang
                </a>
            </div>
        </div>
    </div>
    
    <!-- What Will You Learn Section -->
    <div class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                    Apa yang Akan Kamu Pelajari?
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Kurikulum lengkap dari dasar hingga mahir
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl p-5 shadow-md hover:shadow-lg transition">
                    <div class="text-4xl mb-3">🧩</div>
                    <h3 class="font-bold text-gray-800">Algoritma & Logika</h3>
                    <p class="text-sm text-gray-500 mt-1">Dasar pemrograman yang wajib dikuasai</p>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-md hover:shadow-lg transition">
                    <div class="text-4xl mb-3">🐍</div>
                    <h3 class="font-bold text-gray-800">Python Programming</h3>
                    <p class="text-sm text-gray-500 mt-1">Bahasa populer untuk pemula</p>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-md hover:shadow-lg transition">
                    <div class="text-4xl mb-3">🌐</div>
                    <h3 class="font-bold text-gray-800">HTML & CSS</h3>
                    <p class="text-sm text-gray-500 mt-1">Bangun website pertama Anda</p>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-md hover:shadow-lg transition">
                    <div class="text-4xl mb-3">📜</div>
                    <h3 class="font-bold text-gray-800">JavaScript</h3>
                    <p class="text-sm text-gray-500 mt-1">Buat website interaktif</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Testimonials Section -->
    <div class="bg-white py-20">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                    💬 Apa Kata Mereka?
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Bergabunglah dengan ribuan siswa yang sudah merasakan manfaat YoKode
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-gray-50 rounded-2xl p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white text-xl">👩</div>
                        <div>
                            <div class="font-bold">Sarah Dewi</div>
                            <div class="text-yellow-500 text-sm">⭐⭐⭐⭐⭐</div>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">"Belajar coding jadi seru banget! Sistem heart bikin aku lebih teliti dan streak bikin semangat belajar setiap hari."</p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white text-xl">👨</div>
                        <div>
                            <div class="font-bold">Rizky Pratama</div>
                            <div class="text-yellow-500 text-sm">⭐⭐⭐⭐⭐</div>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">"Dari nol sampai bisa bikin website cuma dalam 2 minggu! Materinya mudah dipahami dan kuisnya menantang."</p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center text-white text-xl">👩</div>
                        <div>
                            <div class="font-bold">Mentari Cahya</div>
                            <div class="text-yellow-500 text-sm">⭐⭐⭐⭐⭐</div>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">"Gamifikasi bikin belajar nggak bosan. Setiap hari nunggu quest baru dan pengen naikin level terus!"</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pricing Section -->
    <div class="bg-gradient-to-br from-blue-500 to-purple-600 py-20">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    💎 Pilih Paket Belajarmu
                </h2>
                <p class="text-blue-100 max-w-2xl mx-auto">
                    Mulai gratis, tingkatkan ke premium untuk akses tak terbatas!
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <!-- Free Plan -->
                <div class="bg-white rounded-2xl p-8 text-center hover:shadow-2xl transition transform hover:-translate-y-2">
                    <div class="text-5xl mb-4">🎓</div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Gratis Selamanya</h3>
                    <div class="text-4xl font-bold text-gray-800 mb-4">Rp0</div>
                    <ul class="text-left space-y-2 mb-6">
                        <li class="flex items-center gap-2">✅ Akses modul dasar</li>
                        <li class="flex items-center gap-2">✅ Kuis terbatas</li>
                        <li class="flex items-center gap-2">✅ Sistem gamifikasi</li>
                        <li class="flex items-center gap-2 text-gray-400">❌ Modul premium</li>
                    </ul>
                    <a href="{{ route('register') }}" class="block bg-gray-200 text-gray-700 py-3 rounded-xl font-semibold hover:bg-gray-300 transition">
                        Mulai Gratis
                    </a>
                </div>
                
                <!-- Premium Plan -->
                <div class="bg-gradient-to-br from-yellow-400 to-orange-500 rounded-2xl p-8 text-center shadow-2xl transform hover:-translate-y-2 transition">
                    <div class="text-5xl mb-4">⭐</div>
                    <h3 class="text-2xl font-bold text-white mb-2">Premium</h3>
                    <div class="text-4xl font-bold text-white mb-4">Rp20.000<span class="text-sm">/bulan</span></div>
                    <ul class="text-left space-y-2 mb-6 text-white">
                        <li class="flex items-center gap-2">✅ Akses SEMUA modul</li>
                        <li class="flex items-center gap-2">✅ Kuis unlimited</li>
                        <li class="flex items-center gap-2">✅ Sertifikat kelulusan</li>
                        <li class="flex items-center gap-2">✅ Prioritas support</li>
                    </ul>
                    <a href="{{ route('register') }}" class="block bg-white text-orange-600 py-3 rounded-xl font-bold hover:bg-gray-100 transition">
                        Upgrade Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- FAQ Section -->
    <div class="bg-white py-20">
        <div class="max-w-4xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                    ❓ Pertanyaan Umum
                </h2>
            </div>
            
            <div class="space-y-4">
                <div class="border rounded-xl p-5">
                    <h3 class="font-bold text-gray-800 mb-2">Apakah YoKode benar-benar gratis?</h3>
                    <p class="text-gray-600">Ya! Modul dasar bisa diakses gratis selamanya. Premium hanya untuk modul eksklusif dan fitur tambahan.</p>
                </div>
                <div class="border rounded-xl p-5">
                    <h3 class="font-bold text-gray-800 mb-2">Apakah butuh pengalaman coding sebelumnya?</h3>
                    <p class="text-gray-600">Tidak! YoKode dirancang khusus untuk pemula absolut.</p>
                </div>
                <div class="border rounded-xl p-5">
                    <h3 class="font-bold text-gray-800 mb-2">Bisa diakses lewat HP?</h3>
                    <p class="text-gray-600">Bisa! YoKode responsif di semua perangkat (HP, tablet, laptop).</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- CTA Footer -->
    <div class="bg-gray-900 py-16">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <!-- <div class="text-5xl mb-4">🚀</div> -->
            <h2 class="text-3xl font-bold text-white mb-4">
                Siap Memulai Petualanganmu?
            </h2>
            <p class="text-gray-400 mb-8 max-w-2xl mx-auto">
                Bergabunglah dengan ribuan siswa lainnya dan mulai belajar coding dengan cara yang menyenangkan!
            </p>
            <a href="{{ route('register') }}" 
               class="inline-block bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold py-4 px-8 rounded-full text-lg transition transform hover:scale-105">
                🎮 Daftar Sekarang
            </a>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="bg-gray-900 border-t border-gray-800 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center text-gray-500 text-sm">
            <p>© 2026 YoKode - Belajar Coding Jadi Menyenangkan 🎯</p>
            <p class="mt-2">Dibuat dengan ❤️ untuk para pejuang kode di Indonesia</p>
        </div>
    </footer>
</body>
</html>