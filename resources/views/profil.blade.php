@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Profile Header -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-700 rounded-lg shadow p-8 mb-6 text-white">
        <div class="flex items-center gap-6">
            <div class="bg-white rounded-full w-24 h-24 flex items-center justify-center">
                <span class="text-5xl">👨‍🎓</span>
            </div>
            <div>
                <h1 class="text-3xl font-bold">{{ Auth::user()->name }}</h1>
                <p class="text-blue-100">{{ Auth::user()->email }}</p>
                <div class="flex gap-4 mt-2">
                    <span class="bg-blue-600 px-3 py-1 rounded-full text-sm">Level {{ Auth::user()->level }}</span>
                    <span class="bg-purple-600 px-3 py-1 rounded-full text-sm">Liga {{ Auth::user()->league }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Stats Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">📊 Statistik Belajar</h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center pb-2 border-b">
                    <span>Total EXP</span>
                    <span class="font-bold">{{ number_format(Auth::user()->total_exp) }}</span>
                </div>
                <div class="flex justify-between items-center pb-2 border-b">
                    <span>Modul Selesai</span>
                    <span class="font-bold">{{ Auth::user()->completed_lessons_count }}</span>
                </div>
                <div class="flex justify-between items-center pb-2 border-b">
                    <span>Jawaban Benar</span>
                    <span class="font-bold">{{ Auth::user()->answers()->where('is_correct', true)->count() }}</span>
                </div>
                <div class="flex justify-between items-center pb-2 border-b">
                    <span>🔥 Streak Terpanjang</span>
                    <span class="font-bold text-orange-600">{{ Auth::user()->streak->longest_streak }} hari</span>
                </div>
                <div class="flex justify-between items-center pb-2 border-b">
                    <span>🪙 Koin</span>
                    <span class="font-bold text-yellow-600">{{ Auth::user()->coins }}</span>
                </div>
            </div>
        </div>
        
        <!-- Achievements Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">🏆 Pencapaian</h2>
            <div class="space-y-3">
                @forelse(Auth::user()->achievements as $achievement)
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <span class="text-3xl">{{ $achievement->icon }}</span>
                        <div>
                            <div class="font-semibold">{{ $achievement->name }}</div>
                            <div class="text-xs text-gray-500">{{ $achievement->description }}</div>
                            <div class="text-xs text-green-600 mt-1">Didapat {{ \Carbon\Carbon::parse($achievement->pivot->earned_at)->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-gray-500">
                        Belum ada pencapaian. Ayo selesaikan modul!
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection