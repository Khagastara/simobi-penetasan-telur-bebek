@extends('layouts.app')

@section('content')

<main class="flex-1 flex flex-col font-['Poppins'] bg-[#D4E6B5] min-h-screen">
    <!-- Top Banner -->
    <header class="topbar p-6 shadow-md">
        <h1 class="text-2xl font-semibold text-[#877B66]">Ubah Data Keuangan</h1>
    </header>

    <!-- Main Content -->
    <section class="p-8">
        <div class="bg-white p-6 rounded-xl shadow">
            <form action="{{ route('owner.keuangan.update', $keuangan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="tgl_rekapitulasi" class="block text-sm font-medium text-gray-700">Tanggal Rekapitulasi</label>
                    <input type="date" name="tgl_rekapitulasi" id="tgl_rekapitulasi" value="{{ old('tgl_rekapitulasi', $keuangan->tgl_rekapitulasi) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#AFC97E] focus:border-[#AFC97E]">
                    @error('tgl_rekapitulasi')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="saldo_pengeluaran" class="block text-sm font-medium text-gray-700">Saldo Pengeluaran</label>
                    <input type="number" name="saldo_pengeluaran" id="saldo_pengeluaran" value="{{ old('saldo_pengeluaran', $keuangan->saldo_pengeluaran) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#AFC97E] focus:border-[#AFC97E]">
                    @error('saldo_pengeluaran')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-[#AFC97E] text-white hover:bg-[#8fa866] px-4 py-2 rounded shadow text-sm transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </section>
</main>

@endsection
