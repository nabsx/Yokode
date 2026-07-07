@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white rounded-2xl shadow-xl p-8">
        <div class="text-center">
            <!-- Icon -->
            <div class="text-6xl mb-4">📧</div>
            
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Verifikasi Email</h2>
            <p class="text-gray-600 mb-6">Silakan verifikasi alamat email Anda</p>
        </div>
        
        <div class="space-y-4">
            @if (session('resent'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                    ✅ Link verifikasi baru telah dikirim ke email Anda.
                </div>
            @endif
            
            <p class="text-gray-700 text-center">
                Sebelum melanjutkan, silakan cek email Anda untuk link verifikasi.
            </p>
            
            <p class="text-gray-600 text-center text-sm">
                Jika Anda tidak menerima email,
                <form method="POST" action="{{ route('verification.resend') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-blue-600 hover:text-blue-800 font-medium hover:underline">
                        klik di sini untuk mengirim ulang
                    </button>
                </form>
            </p>
        </div>
        
        <!-- Tombol kembali ke dashboard -->
        <div class="mt-6 text-center">
            <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 text-sm">
                ← Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection