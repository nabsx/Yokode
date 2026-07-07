@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Sidebar - Profile & Stats -->
    <div class="lg:col-span-1">
        <!-- Profile Card -->
        <div class="bg-white rounded-lg shadow p-6 mb-4">
            <h2 class="text-xl font-bold mb-4">Profil Saya</h2>
            <p class="text-gray-600 font-semibold">{{ Auth::user()->name }}</p>
            <p class="text-gray-500 text-sm">{{ Auth::user()->email }}</p>
            
            <div class="mt-6">
                <div class="flex justify-between text-sm">
                    <span class="font-medium">Level {{ $currentLevel }}</span>
                    <span>{{ $currentExp }} / {{ $currentExp + $expToNextLevel }} EXP</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $levelProgress }}%;"></div>
                </div>
            </div>
            
            <div class="mt-6">
                <div class="flex justify-between text-sm">
                    <span class="font-medium">Progress Belajar</span>
                    <span>{{ $completedCount }} / {{ $totalLessons }} Modul</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                    <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ $progressPercentage }}%;"></div>
                </div>
            </div>
            
            @if(!Auth::user()->is_premium_active)
                <a href="{{ route('premium.index') }}" class="block mt-6 text-center bg-yellow-500 text-white py-2 rounded-lg hover:bg-yellow-600">
                    Upgrade ke Premium
                </a>
            @else
                <div class="mt-6 text-center bg-green-100 text-green-700 py-2 rounded-lg">
                    ✅ Premium Aktif
                </div>
            @endif
        </div>
        
        <!-- Gamifikasi Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">🎮 Statistik Gamifikasi</h2>
            
            <!-- Hearts -->
            <div class="flex items-center justify-between mb-4 p-3 bg-red-50 rounded-lg">
                <div class="flex items-center gap-2">
                    <span class="text-2xl">❤️</span>
                    <span class="font-medium">Hearts</span>
                </div>
                <div class="flex items-center gap-1">
                    @for($i = 1; $i <= $streak->user->hearts->current_hearts ?? 5; $i++)
                        <span class="text-red-500 text-xl">❤️</span>
                    @endfor
                    @for($i = ($streak->user->hearts->current_hearts ?? 5) + 1; $i <= 5; $i++)
                        <span class="text-gray-300 text-xl">🖤</span>
                    @endfor
                </div>
            </div>
            
            <!-- Streak -->
            <div class="flex items-center justify-between mb-4 p-3 bg-orange-50 rounded-lg">
                <div class="flex items-center gap-2">
                    <span class="text-2xl">🔥</span>
                    <span class="font-medium">Streak</span>
                </div>
                <div class="text-right">
                    <span class="text-2xl font-bold text-orange-600">{{ $streak->current_streak ?? 0 }}</span>
                    <span class="text-sm text-gray-500">hari</span>
                    <div class="text-xs text-gray-400">Terpanjang: {{ $streak->longest_streak ?? 0 }} hari</div>
                </div>
            </div>
            
            <!-- League -->
            <div class="flex items-center justify-between mb-4 p-3 bg-purple-50 rounded-lg">
                <div class="flex items-center gap-2">
                    <span class="text-2xl">👑</span>
                    <span class="font-medium">Liga</span>
                </div>
                <div class="text-right">
                    <span class="text-xl font-bold text-purple-600">{{ Auth::user()->league }}</span>
                    <div class="text-xs text-gray-500">Progress ke liga berikutnya</div>
                    <div class="w-32 bg-gray-200 rounded-full h-1.5 mt-1">
                        <div class="bg-purple-600 h-1.5 rounded-full" style="width: {{ Auth::user()->league_progress }}%;"></div>
                    </div>
                </div>
            </div>
            
            <!-- Coins -->
            <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                <div class="flex items-center gap-2">
                    <span class="text-2xl">🪙</span>
                    <span class="font-medium">Koin</span>
                </div>
                <span class="text-2xl font-bold text-yellow-600">{{ Auth::user()->coins ?? 0 }}</span>
            </div>
        </div>
    </div>
    
    <!-- Right Sidebar - Lesson List & Daily Quests -->
    <div class="lg:col-span-2">
        <!-- Daily Quests -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">📅 Misi Harian</h2>
                <span class="text-sm text-gray-500">{{ $completedQuests ?? 0 }} / {{ $totalQuests ?? 3 }} Selesai</span>
            </div>
            
            <div class="space-y-3">
                @forelse($dailyQuests ?? [] as $quest)
                    <div class="flex items-center justify-between p-3 rounded-lg {{ $quest->completed ? 'bg-green-50 border border-green-200' : 'bg-gray-50' }}">
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                @if($quest->completed)
                                    <span class="text-green-500">✅</span>
                                @else
                                    <span class="text-gray-400">📌</span>
                                @endif
                                <span class="font-medium {{ $quest->completed ? 'text-green-700' : 'text-gray-700' }}">
                                    {{ $quest->dailyQuest->title }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ $quest->dailyQuest->description }}</p>
                            <div class="flex items-center gap-3 mt-2">
                                <div class="flex items-center gap-1 text-xs text-gray-500">
                                    <span>⭐ +{{ $quest->dailyQuest->reward_exp }} EXP</span>
                                </div>
                                @if($quest->dailyQuest->reward_coins > 0)
                                    <div class="flex items-center gap-1 text-xs text-gray-500">
                                        <span>🪙 +{{ $quest->dailyQuest->reward_coins}} Koin</span>
                                    </div>
                                @endif
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                                <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ min(100, ($quest->progress / $quest->dailyQuest->target) * 100) }}%;"></div>
                            </div>
                            <div class="text-right text-xs text-gray-400 mt-1">
                                {{ $quest->progress }} / {{ $quest->dailyQuest->target }}
                            </div>
                        </div>
                        @if($quest->completed)
                            <div class="ml-3">
                                <span class="text-green-500 text-xl">✓</span>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-4 text-gray-500">
                        Belum ada misi hari ini. Ayo mulai belajar!
                    </div>
                @endforelse
            </div>
        </div>
        
        <!-- Recent Achievements -->
@if(isset($recentAchievements) && $recentAchievements->count() > 0)
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h2 class="text-xl font-bold mb-4">🏆 Pencapaian Terbaru</h2>
    <div class="flex flex-wrap gap-3">
        @foreach($recentAchievements as $achievement)
            <div class="flex items-center gap-2 bg-gradient-to-r from-yellow-50 to-yellow-100 border border-yellow-200 rounded-lg px-3 py-2">
                <span class="text-2xl">{{ $achievement->icon }}</span>
                <div>
                    <div class="font-semibold text-sm">{{ $achievement->name }}</div>
                    <div class="text-xs text-gray-500">
                        @php
                            $earnedAt = $achievement->pivot->earned_at;
                            if (is_string($earnedAt)) {
                                $earnedAt = \Carbon\Carbon::parse($earnedAt);
                            }
                        @endphp
                        {{ $earnedAt->diffForHumans() }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif
        
<!-- Daftar Modul Belajar - GROUPING -->
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-bold mb-4">📚 Modul Belajar</h2>
    
    @if($categories->isEmpty())
        <div class="text-center py-8 text-gray-500">
            <div class="text-4xl mb-3">📭</div>
            <p>Belum ada modul yang tersedia.</p>
            <p class="text-sm">Admin sedang menyiapkan materi belajar.</p>
        </div>
    @else
        @foreach($categories as $category)
            <div class="mb-6 last:mb-0 border-b last:border-b-0 pb-4 last:pb-0">
                <!-- Category Header -->
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <span class="text-2xl">{{ $category->icon }}</span>
                        <h3 class="text-lg font-semibold text-gray-800">{{ $category->name }}</h3>
                        @if($category->progress > 0)
                            <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">
                                {{ $category->progress }}%
                            </span>
                        @else
                            <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">
                                Belum dimulai
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-400">
                        {{ $category->completed_count ?? 0 }} / {{ $category->total_count ?? $category->lessons->count() }} modul
                    </p>
                </div>
                
                <!-- Progress Bar per Kategori -->
                <div class="w-full bg-gray-200 rounded-full h-1.5 mb-3">
                    <div class="bg-{{ $category->color }}-500 h-1.5 rounded-full" style="width: {{ $category->progress }}%;"></div>
                </div>
                
                <!-- Description -->
                @if($category->description)
                    <p class="text-sm text-gray-500 mb-3">{{ $category->description }}</p>
                @endif
                
                <!-- Lessons in Category -->
                <div class="space-y-2">
                    @foreach($category->lessons as $lesson)
                        @php
                            $isCompleted = in_array($lesson->id, $completedLessons);
                            $isLocked = $lesson->is_premium && !Auth::user()->is_premium_active;
                        @endphp
                        
                        <a href="{{ route('lesson.show', $lesson->id) }}" 
                           class="block border rounded-lg p-3 hover:shadow-md transition {{ $isCompleted ? 'bg-green-50 border-green-300' : ($isLocked ? 'bg-gray-50 border-gray-200 opacity-70' : 'hover:bg-gray-50') }}">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="text-gray-400 text-xs">#{{ $lesson->order_number }}</span>
                                        <span class="font-medium {{ $isCompleted ? 'text-green-700' : ($isLocked ? 'text-gray-400' : '') }}">
                                            {{ $lesson->title }}
                                        </span>
                                        @if($lesson->is_premium)
                                            <span class="text-xs bg-yellow-200 text-yellow-800 px-2 py-0.5 rounded">⭐ Premium</span>
                                        @endif
                                        @if($isCompleted)
                                            <span class="text-xs bg-green-200 text-green-800 px-2 py-0.5 rounded">✓ Selesai</span>
                                        @endif
                                        @if($isLocked)
                                            <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded">🔒 Terkunci</span>
                                        @endif
                                    </div>
                                    <p class="text-gray-500 text-xs mt-1">⭐ +{{ $lesson->exp_reward }} EXP</p>
                                </div>
                                <div>
                                    @if($isCompleted)
                                        <span class="text-green-600 text-xl">✓</span>
                                    @elseif($isLocked)
                                        <span class="text-gray-400">🔒</span>
                                    @else
                                        <span class="text-blue-600 text-sm font-medium">Mulai →</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    @endif
</div>
    </div>
</div>
@endsection