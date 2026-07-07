@extends('layouts.admin')

@section('title', 'Shop Items Management')
@section('subtitle', 'Manage shop items and boosters')

@section('content')
    <div class="bg-white rounded-lg shadow">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800">All Shop Items</h3>
        </div>

        <!-- Content -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
            @forelse($items as $item)
                <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">{{ $item->name }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($item->description, 60) }}</p>
                        </div>
                        <span class="text-4xl">{{ $item->icon ?? '🎁' }}</span>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-600 text-sm">Price</p>
                                <p class="font-bold text-lg">
                                    @if($item->price_type === 'coins')
                                        <span class="text-yellow-600">{{ $item->price }} 🪙</span>
                                    @else
                                        <span class="text-purple-600">{{ $item->price }} 💎</span>
                                    @endif
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-gray-600 text-sm">Type</p>
                                <p class="font-bold text-sm capitalize">{{ $item->type ?? 'Item' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center">
                    <i class="fas fa-inbox text-4xl text-gray-300 mb-4 block"></i>
                    <p class="text-gray-500">No shop items found</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $items->links() }}
        </div>
    </div>
@endsection
