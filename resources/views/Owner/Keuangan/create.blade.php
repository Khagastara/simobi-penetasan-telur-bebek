@extends('layouts.owner')

@section('content')

<main class="flex-1 flex flex-col font-['Poppins'] bg-[#D4E6B5] min-h-screen">
    <header class="topbar p-6 shadow-md">
        <h1 class="text-2xl font-semibold text-[#877B66]">Tambah Data Keuangan</h1>
    </header>
    <section class="p-8">
        <div class="bg-white p-6 rounded-xl shadow">
            <form action="{{ route('owner.keuangan.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="tgl_rekapitulasi" class="block text-sm font-medium text-gray-700">Tanggal Rekapitulasi</label>
                    <select name="tgl_rekapitulasi" id="tgl_rekapitulasi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#AFC97E] focus:border-[#AFC97E]">
                        <option value="{{ now()->toDateString() }}">-- Pilih Tanggal Rekapitulasi (Default: Hari Ini) --</option>
                        @foreach ($tanggalRekapitulasi as $tanggal)
                            <option value="{{ $tanggal->tgl_transaksi }}">
                                {{ \Carbon\Carbon::parse($tanggal->tgl_transaksi)->format('Y-m-d') }}
                            </option>
                        @endforeach
                    </select>
                    @error('tgl_rekapitulasi')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="saldo_pengeluaran" class="block text-sm font-medium text-gray-700">Saldo Pengeluaran</label>
                    <input type="number" name="saldo_pengeluaran" id="saldo_pengeluaran" value="{{ old('saldo_pengeluaran') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#AFC97E] focus:border-[#AFC97E]">
                    @error('saldo_pengeluaran')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-[#AFC97E] text-white hover:bg-[#8fa866] px-4 py-2 rounded shadow text-sm transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </section>
</main>

@endsection
