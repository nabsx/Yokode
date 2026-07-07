<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>YoKode - Belajar Logika Pemrograman</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        /* Hide scrollbar for sidebar */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        /* Sidebar animation */
        .sidebar-enter {
            transform: translateX(-100%);
        }
        .sidebar-enter-active {
            transform: translateX(0);
            transition: transform 300ms ease-out;
        }
        .sidebar-exit {
            transform: translateX(0);
        }
        .sidebar-exit-active {
            transform: translateX(-100%);
            transition: transform 300ms ease-in;
        }
        
        /* Overlay animation */
        .overlay-enter {
            opacity: 0;
        }
        .overlay-enter-active {
            opacity: 1;
            transition: opacity 300ms ease-out;
        }
        .overlay-exit {
            opacity: 1;
        }
        .overlay-exit-active {
            opacity: 0;
            transition: opacity 300ms ease-in;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Mobile Sidebar (Drawer) -->
    <div id="mobile-sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-white shadow-2xl transform -translate-x-full transition-transform duration-300 ease-out">
        <div class="flex flex-col h-full">
            <!-- Sidebar Header -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('images/logo.png') }}" alt="YoKode" class="h-8 w-auto">
                        <span class="text-xl font-bold text-white">YoKode</span>
                    </div>
                    <button id="close-sidebar" class="text-white text-2xl">&times;</button>
                </div>
                <div class="mt-4 text-white text-sm opacity-90">
                    Belajar Coding Jadi Menyenangkan
                </div>
            </div>
            
            <!-- Sidebar Menu Items -->
            <div class="flex-1 overflow-y-auto no-scrollbar">
                <div class="p-4 space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition">
                        <span class="text-xl">📊</span>
                        <span class="font-medium">Dashboard</span>
                    </a>
                    <a href="{{ route('leaderboard') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition">
                        <span class="text-xl">🏆</span>
                        <span class="font-medium">Leaderboard</span>
                    </a>
                    <a href="{{ route('profile') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition">
                        <span class="text-xl">👤</span>
                        <span class="font-medium">Profil Saya</span>
                    </a>
                    <a href="{{ route('shop.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition">
                        <span class="text-xl">🛒</span>
                        <span class="font-medium">Toko</span>
                    </a>
                    <a href="{{ route('premium.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition">
                        <span class="text-xl">💎</span>
                        <span class="font-medium">Premium</span>
                    </a>
                </div>
                
                <div class="border-t my-4"></div>
                
                <!-- User Stats in Sidebar -->
                <div class="p-4">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-2xl">👨‍🎓</span>
                            <div>
                                <div class="font-semibold text-gray-800">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-500">Level {{ Auth::user()->level }}</div>
                            </div>
                        </div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600">❤️ Hearts</span>
                            <span class="font-medium">{{ Auth::user()->hearts->current_hearts ?? 5 }}/5</span>
                        </div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600">🔥 Streak</span>
                            <span class="font-medium">{{ Auth::user()->streak->current_streak ?? 0 }} hari</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">🪙 Koin</span>
                            <span class="font-medium">{{ Auth::user()->coins ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar Footer -->
            <div class="p-4 border-t">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-4 py-3 w-full text-red-600 rounded-lg hover:bg-red-50 transition">
                        <span class="text-xl">🚪</span>
                        <span class="font-medium">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>
    
    <!-- Top Navbar (Mobile Friendly) -->
    <nav class="bg-white shadow-lg sticky top-0 z-30">
        <div class="px-4 py-3">
            <div class="flex justify-between items-center">
                <!-- Menu Button (Mobile) -->
                <button id="menu-button" class="p-2 rounded-lg hover:bg-gray-100 lg:hidden">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <span class="text-2xl">🎮</span>
                    <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent hidden sm:inline">YoKode</span>
                </a>
                
                <!-- Desktop Menu (Hidden on Mobile) -->
                <div class="hidden lg:flex items-center gap-6">
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-blue-600">Dashboard</a>
                    <a href="{{ route('leaderboard') }}" class="text-gray-600 hover:text-blue-600">🏆 Peringkat</a>
                    <a href="{{ route('profile') }}" class="text-gray-600 hover:text-blue-600">👤 Profil</a>
                    <a href="{{ route('shop.index') }}" class="text-gray-600 hover:text-blue-600">🛒 Toko</a>
                    <a href="{{ route('premium.index') }}" class="text-gray-600 hover:text-blue-600">💎 Premium</a>
                </div>
                
                <!-- User Info (Mobile Friendly) -->
                <div class="flex items-center gap-2">
                    <div class="hidden sm:flex items-center gap-2 bg-gray-100 rounded-full px-3 py-1.5">
                        <span class="text-red-500 text-sm">❤️ {{ Auth::user()->hearts->current_hearts ?? 5 }}</span>
                        <span class="text-orange-500 text-sm">🔥 {{ Auth::user()->streak->current_streak ?? 0 }}</span>
                        <span class="text-yellow-500 text-sm">🪙 {{ Auth::user()->coins ?? 0 }}</span>
                    </div>
                    
                    <form action="{{ route('logout') }}" method="POST" class="hidden lg:inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Logout</button>
                    </form>
                    
                    <!-- Mobile user icon -->
                    <div class="lg:hidden flex items-center gap-1">
                        <span class="text-red-500 text-sm">❤️ {{ Auth::user()->hearts->current_hearts ?? 5 }}</span>
                        <span class="text-orange-500 text-sm">🔥 {{ Auth::user()->streak->current_streak ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-8">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                {{ session('error') }}
            </div>
        @endif
        
        @yield('content')
    </main>
    
    <!-- JavaScript for Sidebar -->
    <script>
        const sidebar = document.getElementById('mobile-sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const menuBtn = document.getElementById('menu-button');
        const closeBtn = document.getElementById('close-sidebar');
        
        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');
            overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closeSidebar() {
            sidebar.classList.remove('translate-x-0');
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            document.body.style.overflow = '';
        }
        
        if (menuBtn) menuBtn.addEventListener('click', openSidebar);
        if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
        overlay.addEventListener('click', closeSidebar);
        
        // Close sidebar on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !sidebar.classList.contains('-translate-x-full')) {
                closeSidebar();
            }
        });
    </script>
</body>
</html>