@extends('layouts.app')

@section('content')
<main class="flex-1 flex flex-col font-['Poppins'] bg-[#D4E6B5] min-h-screen">
    <!-- Topbar -->
    <header class="topbar p-6 shadow-md">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-[#877B66]">Tambah Stok Distribusi</h1>
                <p class="text-sm text-gray-700">Formulir untuk menambahkan data stok baru</p>
            </div>
            <div class="text-right text-gray-800">
                <p class="font-semibold">Halo, <span class="italic">{{ Auth::user()->owner->nama }}</span></p>
            </div>
        </div>
    </header>

    <!-- Content -->
    <section class="p-8">
        @if (session('error'))
            <div class="bg-red-500 text-white px-4 py-2 rounded-lg shadow mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white p-6 rounded-xl shadow-md max-w-3xl mx-auto">
            <form action="{{ route('owner.stok.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label for="nama_stok" class="block font-semibold text-[#877B66] mb-1">Nama Stok <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_stok" id="nama_stok"
                        value="{{ old('nama_stok') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-[#AFC97E]" required>
                </div>

                <div class="mb-4">
                    <label for="gambar_stok" class="block font-semibold text-[#877B66] mb-1">Gambar Stok <span class="text-red-500">*</span></label>
                    <input type="file" name="gambar_stok" id="gambar_stok"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG, GIF. Maksimal ukuran: 2MB.</p>
                </div>

                <div class="mb-4">
                    <label for="jumlah_stok" class="block font-semibold text-[#877B66] mb-1">Jumlah Stok <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah_stok" id="jumlah_stok"
                        value="{{ old('jumlah_stok') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-[#AFC97E]" required>
                </div>

                <div class="mb-4">
                    <label for="harga_stok" class="block font-semibold text-[#877B66] mb-1">Harga Stok <span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Rp</span>
                        <input type="number" name="harga_stok" id="harga_stok"
                            value="{{ old('harga_stok') }}"
                            class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-[#AFC97E]" required>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="deskripsi_stok" class="block font-semibold text-[#877B66] mb-1">Deskripsi Stok</label>
                    <textarea name="deskripsi_stok" id="deskripsi_stok" rows="4"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-[#AFC97E]">{{ old('deskripsi_stok') }}</textarea>
                </div>

                <!-- Tombol bawah -->
                <div class="flex justify-between">
                    <a href="{{ route('owner.stok.index') }}"
                        class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg shadow transition">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                    <button type="submit"
                        class="bg-[#AFC97E] hover:bg-[#94b260] text-white px-4 py-2 rounded-lg shadow transition">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </section>
</main>
@endsection
