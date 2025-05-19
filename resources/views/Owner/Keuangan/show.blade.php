@extends('layouts.owner')

@section('content')

<main class="flex-1 flex flex-col font-['Poppins'] bg-[#D4E6B5] min-h-screen">
    <!-- Top Banner -->
    <header class="topbar p-6 shadow-md">
        <h1 class="text-2xl font-semibold text-[#877B66]">Detail Data Keuangan</h1>
    </header>

    <!-- Main Content -->
    <section class="p-8">
        <div class="bg-white p-6 rounded-xl shadow">
            <div class="mb-4">
                <h2 class="text-lg font-semibold text-gray-700">Tanggal Rekapitulasi</h2>
                <p class="text-gray-600">{{ $keuangan->tgl_rekapitulasi }}</p>
            </div>

            <div class="mb-4">
                <h2 class="text-lg font-semibold text-gray-700">Saldo Pemasukkan</h2>
                <p class="text-gray-600">Rp {{ number_format($keuangan->saldo_pemasukkan, 0, ',', '.') }}</p>
            </div>

            <div class="mb-4">
                <h2 class="text-lg font-semibold text-gray-700">Saldo Pengeluaran</h2>
                <p class="text-gray-600">Rp {{ number_format($keuangan->saldo_pengeluaran, 0, ',', '.') }}</p>
            </div>

            <div class="mb-4">
                <h2 class="text-lg font-semibold text-gray-700">Total Penjualan</h2>
                <p class="text-gray-600">{{ $keuangan->total_penjualan }}</p>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('owner.keuangan.index') }}"
                    class="bg-gray-300 text-gray-700 hover:bg-gray-400 px-4 py-2 rounded shadow text-sm transition">
                    Kembali
                </a>
            </div>
        </div>
    </section>
</main>

@endsection
