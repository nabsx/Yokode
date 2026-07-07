@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-yellow-600 mb-2">🛒 Toko</h1>
            <p class="text-gray-600">Tukarkan koinmu dengan item keren!</p>
            <div class="inline-block bg-yellow-100 rounded-full px-4 py-2 mt-2">
                <span class="text-yellow-600 font-bold">🪙 {{ Auth::user()->coins }} Koin</span>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($shopItems as $item)
                <div class="border rounded-lg p-4 text-center hover:shadow-lg transition">
                    <div class="text-4xl mb-3">
                        @if($item->type == 'heart') ❤️
                        @elseif($item->type == 'booster') ⚡
                        @else 🎨
                        @endif
                    </div>
                    <h3 class="font-bold text-lg">{{ $item->name }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $item->description }}</p>
                    <div class="mt-4">
                        <span class="text-yellow-600 font-bold">🪙 {{ number_format($item->price_coins) }}</span>
                    </div>
                    
                    <form action="{{ route('shop.buy', $item->id) }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-yellow-500 text-white py-2 rounded-lg hover:bg-yellow-600 transition"
                                {{ Auth::user()->coins < $item->price_coins ? 'disabled' : '' }}>
                            {{ Auth::user()->coins < $item->price_coins ? 'Koin Kurang' : 'Beli' }}
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
        
        <div class="mt-8 text-center text-gray-500 text-sm">
            <p>💡 Tips: Selesaikan daily quests untuk mendapatkan koin gratis!</p>
        </div>
    </div>
</div>
@endsection