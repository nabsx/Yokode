<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - YoKode</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .sidebar-collapsed .sidebar-text {
            display: none;
        }
        
        .sidebar-collapsed .sidebar-icon {
            margin-right: 0;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen bg-gray-50">
        <!-- Sidebar -->
        <!-- FIX: aside sekarang flex flex-col agar footer (user info + logout) menempel di
             bawah secara natural, bukan lewat position:absolute yang sebelumnya "lepas"
             dari parent karena parent tidak relative. Ini juga yang menyebabkan garis
             border-t di footer terlihat "menempel"/tidak hilang di posisi yang salah. -->
        <aside class="w-64 bg-gradient-to-b from-blue-900 to-blue-800 text-white shadow-lg flex flex-col h-screen">
            <!-- Bagian nav yang bisa discroll -->
            <div class="flex-1 overflow-y-auto p-6">
                <div class="flex items-center gap-3 mb-8">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-blue-600">
                            <i class="fas fa-cog text-white"></i>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">YoKode Admin</h1>
                        <p class="text-xs text-blue-200">Management</p>
                    </div>
                </div>

                <!-- Navigation Menu -->
                <nav class="space-y-2">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700' : 'hover:bg-blue-700 transition' }}">
                        <i class="fas fa-chart-line w-5"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>

                    <div class="pt-4 pb-2">
                        <p class="px-4 text-xs font-semibold text-blue-200 uppercase">Management</p>
                    </div>

                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-blue-700' : 'hover:bg-blue-700 transition' }}">
                        <i class="fas fa-users w-5"></i>
                        <span class="sidebar-text">Users</span>
                    </a>

                    <a href="{{ route('admin.lessons.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.lessons.*') ? 'bg-blue-700' : 'hover:bg-blue-700 transition' }}">
                        <i class="fas fa-book w-5"></i>
                        <span class="sidebar-text">Lessons</span>
                    </a>

                    <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.categories.*') ? 'bg-blue-700' : 'hover:bg-blue-700 transition' }}">
                        <i class="fas fa-tag w-5"></i>
                        <span class="sidebar-text">Categories</span>
                    </a>

                    <a href="{{ route('admin.quizzes.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.quizzes.*') ? 'bg-blue-700' : 'hover:bg-blue-700 transition' }}">
                        <i class="fas fa-question-circle w-5"></i>
                        <span class="sidebar-text">Quizzes</span>
                    </a>

                    <div class="pt-4 pb-2">
                        <p class="px-4 text-xs font-semibold text-blue-200 uppercase">Gamification</p>
                    </div>

                    <a href="{{ route('admin.achievements.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.achievements.*') ? 'bg-blue-700' : 'hover:bg-blue-700 transition' }}">
                        <i class="fas fa-trophy w-5"></i>
                        <span class="sidebar-text">Achievements</span>
                    </a>

                    <a href="{{ route('admin.shop.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.shop.*') ? 'bg-blue-700' : 'hover:bg-blue-700 transition' }}">
                        <i class="fas fa-store w-5"></i>
                        <span class="sidebar-text">Shop Items</span>
                    </a>

                    <a href="{{ route('admin.quests.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.quests.index') ? 'bg-blue-700' : 'hover:bg-blue-700 transition' }}">
                        <i class="fas fa-list-check w-5"></i>
                        <span class="sidebar-text">Daily Quests</span>
                    </a>

                    <a href="{{ route('admin.quests.templates') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.quests.templates', 'admin.quests.template.*') ? 'bg-blue-700' : 'hover:bg-blue-700 transition' }}">
                        <i class="fas fa-calendar-days w-5"></i>
                        <span class="sidebar-text">Quest Templates</span>
                    </a>

                    <div class="pt-4 pb-2">
                        <p class="px-4 text-xs font-semibold text-blue-200 uppercase">Analytics</p>
                    </div>

                    <a href="{{ route('admin.analytics') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.analytics') ? 'bg-blue-700' : 'hover:bg-blue-700 transition' }}">
                        <i class="fas fa-chart-bar w-5"></i>
                        <span class="sidebar-text">Analytics</span>
                    </a>
                </nav>
            </div>

            <!-- User Info + Logout: sekarang bagian normal dari flex-col, bukan absolute.
                 flex-shrink-0 supaya tidak ikut mengecil saat nav di atasnya panjang. -->
            <div class="flex-shrink-0 p-6 border-t border-blue-700">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div class="text-sm">
                        <p class="font-medium">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-blue-200">Administrator</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm rounded-lg hover:bg-red-700 transition text-left text-red-100 hover:text-white">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="sidebar-text">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white border-b border-gray-200 shadow-sm">
                <div class="px-6 py-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">@yield('title', 'Admin Panel')</h2>
                        <p class="text-sm text-gray-500">@yield('subtitle', 'Welcome to the admin dashboard')</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ now()->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="flex-1 overflow-y-auto">
                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center gap-3">
                            <i class="fas fa-check-circle text-green-600"></i>
                            <div>
                                <p class="font-medium text-green-800">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg flex items-center gap-3">
                            <i class="fas fa-exclamation-circle text-red-600"></i>
                            <div>
                                <p class="font-medium text-red-800">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <p class="font-medium text-red-800 mb-2">There were some errors:</p>
                            <ul class="list-disc list-inside text-sm text-red-700">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>
</html>
