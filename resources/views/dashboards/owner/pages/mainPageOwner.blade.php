@extends('layouts.owner')

@section('title', 'Dashboard Owner')
@section('page-title', 'Dashboard Owner')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Card Breeding -->
    <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-egg text-[#AFC97E] mr-2"></i> Pembiakan Aktif
            </h3>
            <span class="bg-[#E2D686] text-[#877B66] px-3 py-1 rounded-full text-sm font-medium">
                {{ $activeBreedings }} Aktif
            </span>
        </div>
        <p class="text-gray-600 mt-2 text-sm">Jumlah proses pembiakan yang sedang berjalan</p>
        <a href="{{ route('owner.breeding.index') }}" class="mt-4 inline-flex items-center text-[#AFC97E] hover:text-[#8AA85D] text-sm">
            Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>

    <!-- Card Stock -->
    <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-boxes text-[#AFC97E] mr-2"></i> Stok Tersedia
            </h3>
            <span class="bg-[#E2D686] text-[#877B66] px-3 py-1 rounded-full text-sm font-medium">
                {{ $totalStock }} Item
            </span>
        </div>
        <p class="text-gray-600 mt-2 text-sm">Total stok telur yang siap didistribusikan</p>
        <a href="{{ route('owner.stock.index') }}" class="mt-4 inline-flex items-center text-[#AFC97E] hover:text-[#8AA85D] text-sm">
            Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>

    <!-- Card Transaction -->
    <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-exchange-alt text-[#AFC97E] mr-2"></i> Transaksi Baru
            </h3>
            <span class="bg-[#E2D686] text-[#877B66] px-3 py-1 rounded-full text-sm font-medium">
                {{ $newTransactions }} Baru
            </span>
        </div>
        <p class="text-gray-600 mt-2 text-sm">Transaksi yang perlu dikonfirmasi</p>
        <a href="{{ route('owner.transaction.index') }}" class="mt-4 inline-flex items-center text-[#AFC97E] hover:text-[#8AA85D] text-sm">
            Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>

    <!-- Recent Activity Section -->
    <div class="bg-white p-6 rounded-lg shadow col-span-1 md:col-span-2 lg:col-span-3">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-history text-[#AFC97E] mr-2"></i> Aktivitas Terkini
        </h3>
        <div class="space-y-4">
            @foreach($recentActivities as $activity)
            <div class="border-b border-gray-100 pb-3 last:border-0">
                <p class="text-sm text-gray-600">
                    <span class="font-medium">{{ $activity->description }}</span> - 
                    <span class="text-[#877B66]">{{ $activity->created_at->diffForHumans() }}</span>
                </p>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection