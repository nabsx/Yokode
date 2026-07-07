@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Profile Header -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-700 rounded-lg shadow p-8 mb-6 text-white">
        <div class="flex items-center gap-6">
            <div class="bg-white rounded-full w-24 h-24 flex items-center justify-center">
                <span class="text-5xl">{{ Auth::user()->level >= 10 ? '👨‍🎓' : '📖' }}</span>
            </div>
            <div class="flex-1">
                <h1 class="text-3xl font-bold">{{ Auth::user()->name }}</h1>
                <p class="text-blue-100">{{ Auth::user()->email }}</p>
                <div class="flex gap-4 mt-2">
                    <span class="bg-blue-600 px-3 py-1 rounded-full text-sm">Level {{ Auth::user()->level }}</span>
                    <span class="bg-purple-600 px-3 py-1 rounded-full text-sm">Liga {{ Auth::user()->league }}</span>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}" class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-50 transition">
                ✏️ Edit Profil
            </a>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Stats Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">📊 Statistik Belajar</h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center pb-2 border-b">
                    <span>Total EXP</span>
                    <span class="font-bold">{{ number_format($user->total_exp) }}</span>
                </div>
                <div class="flex justify-between items-center pb-2 border-b">
                    <span>Modul Selesai</span>
                    <span class="font-bold">{{ $user->completed_lessons_count }}</span>
                </div>
                <div class="flex justify-between items-center pb-2 border-b">
                    <span>Jawaban Benar</span>
                    <span class="font-bold text-green-600">{{ $totalCorrectAnswers }}</span>
                </div>
                <div class="flex justify-between items-center pb-2 border-b">
                    <span>Jawaban Salah</span>
                    <span class="font-bold text-red-600">{{ $totalWrongAnswers }}</span>
                </div>
                <div class="flex justify-between items-center pb-2 border-b">
                    <span>Akurasi</span>
                    <span class="font-bold">{{ $accuracy }}%</span>
                </div>
                <div class="flex justify-between items-center pb-2 border-b">
                    <span>🔥 Streak Terpanjang</span>
                    <span class="font-bold text-orange-600">{{ $user->streak->longest_streak }} hari</span>
                </div>
                <div class="flex justify-between items-center pb-2 border-b">
                    <span>🪙 Koin</span>
                    <span class="font-bold text-yellow-600">{{ number_format($user->coins) }}</span>
                </div>
                <div class="flex justify-between items-center pb-2 border-b">
                    <span>🏆 Pencapaian</span>
                    <span class="font-bold">{{ $totalAchievements }} / {{ $totalPossibleAchievements }}</span>
                </div>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">📝 Aktivitas Terbaru</h2>
            <div class="space-y-3">
                @forelse($recentActivities as $activity)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <div class="font-medium">{{ $activity->lesson->title }}</div>
                            <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($activity->completed_at)->diffForHumans() }}</div>
                        </div>
                        <div class="text-green-600 font-bold">+{{ $activity->lesson->exp_reward }} EXP</div>
                    </div>
                @empty
                    <div class="text-center py-4 text-gray-500">
                        Belum ada aktivitas. Ayo mulai belajar!
                    </div>
                @endforelse
            </div>
        </div>
        
        <!-- Achievements -->
        <div class="bg-white rounded-lg shadow p-6 md:col-span-2">
            <h2 class="text-xl font-bold mb-4">🏆 Pencapaian</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @forelse($achievements as $achievement)
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <span class="text-3xl">{{ $achievement->icon }}</span>
                        <div class="flex-1">
                            <div class="font-semibold">{{ $achievement->name }}</div>
                            <div class="text-xs text-gray-500">{{ $achievement->description }}</div>
                            <div class="text-xs text-green-600">Dapat {{ \Carbon\Carbon::parse($achievement->pivot->earned_at)->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 text-center py-4 text-gray-500">
                        Belum ada pencapaian. Selesaikan modul dan dapatkan streak untuk membuka pencapaian!
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection