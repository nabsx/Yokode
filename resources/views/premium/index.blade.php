@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-yellow-600 mb-2">Upgrade ke Premium1</h1>
            <p class="text-gray-600">Akses semua modul eksklusif dan fitur lengkap!</p>
        </div>
        
        @if($isPremium)
            <div class="bg-green-50 border border-green-200 rounded-lg p-6 text-center">
                <div class="text-green-600 text-4xl mb-2">✅</div>
                <h2 class="text-xl font-bold text-green-700">Premium Aktif!</h2>
                <p class="text-green-600 mt-2">
                    Premium berlaku sampai: <strong>{{ $premiumExpiresAt ? $premiumExpiresAt->format('d M Y') : 'Selamanya' }}</strong>
                </p>
                <a href="{{ route('dashboard') }}" class="inline-block mt-4 bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                    Kembali ke Dashboard
                </a>
            </div>
        @else
            <!-- Package Card -->
            <div class="grid md:grid-cols-2 gap-6 mb-8">
                <div class="border rounded-lg p-6 text-center">
                    <div class="text-gray-400 text-2xl mb-2">📖</div>
                    <h3 class="text-xl font-bold">Gratis</h3>
                    <p class="text-gray-500 mt-2">Akses terbatas</p>
                    <ul class="mt-4 text-left space-y-2">
                        <li class="text-gray-600">✓ Modul dasar</li>
                        <li class="text-gray-600">✓ Kuis terbatas</li>
                        <li class="text-gray-400 line-through">Modul premium</li>
                        <li class="text-gray-400 line-through">Sertifikat</li>
                    </ul>
                </div>
                
                <div class="border-2 border-yellow-400 rounded-lg p-6 text-center bg-yellow-50">
                    <div class="text-yellow-500 text-2xl mb-2">⭐</div>
                    <h3 class="text-xl font-bold text-yellow-700">Premium</h3>
                    <p class="text-gray-500 mt-2">Akses penuh</p>
                    <ul class="mt-4 text-left space-y-2">
                        <li class="text-gray-600">✓ Semua modul</li>
                        <li class="text-gray-600">✓ Semua kuis</li>
                        <li class="text-gray-600">✓ Modul eksklusif</li>
                        <li class="text-gray-600">✓ Sertifikat</li>
                    </ul>
                    <div class="mt-6">
                        <p class="text-2xl font-bold text-yellow-700">Rp20.000</p>
                        <p class="text-sm text-gray-500">/bulan</p>
                    </div>
                    
                    <form action="{{ route('premium.subscribe') }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit" class="w-full bg-yellow-500 text-white py-2 rounded-lg hover:bg-yellow-600 transition">
                            Upgrade Sekarang
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4 text-center text-gray-500 text-sm">
                <p>Pembayaran akan diproses dengan sistem yang aman.</p>
                <p>Premium akan aktif selama 30 hari.</p>
            </div>
        @endif
    </div>
</div>
@endsection