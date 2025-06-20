@extends('layouts.owner')

@section('content')
    <main class="flex-1 flex flex-col font-['Poppins'] bg-[#D4E6B5] min-h-screen">
        <header class="topbar p-6 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-[#877B66]">Detail Stok Distribusi</h1>
                    <p class="text-sm text-gray-700">Informasi lengkap terkait stok distribusi</p>
                </div>
                <div class="text-right text-gray-800">
                    <p class="font-semibold">Halo, <span class="italic">{{ Auth::user()->owner->nama }}</span></p>
                </div>
            </div>
        </header>

        <section class="p-8">
            @if (session('success'))
                <div class="bg-green-500 text-white px-4 py-2 rounded-lg shadow mb-4">
                    {{ session('success') }}
                </div>
                <script>
                    setTimeout(() => {
                        document.querySelector('.bg-green-500').remove();
                    }, 3000);
                </script>
            @endif

            <div class="bg-white p-6 rounded-xl shadow-md max-w-4xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="text-center">
                        <img src="{{ $stok->gambar_stok ? Storage::url($stok->gambar_stok) : asset('images/stok/no-image.png') }}"
                                    alt="{{ $stok->nama_stok }}"
                                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                                    onerror="this.src='{{ asset('images/stok/no-image.png') }}'">
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-[#877B66] mb-4">{{ $stok->nama_stok }}</h2>
                        <table class="w-full text-sm text-gray-700">
                            <tr>
                                <th class="w-1/3 align-top">Harga</th>
                                <td>: Rp {{ number_format($stok->harga_stok, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th class="align-top">Jumlah Stok</th>
                                <td>: {{ $stok->jumlah_stok }}</td>
                            </tr>
                            <tr>
                                <th class="align-top">Deskripsi</th>
                                <td>: {{ $stok->deskripsi_stok ?: '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="mt-6 flex justify-between">
                    <a href="{{ route('owner.stok.index') }}" class="inline-block bg-gray-400 text-white hover:bg-gray-500 px-4 py-2 rounded-lg shadow transition">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                    <a href="{{ route('owner.stok.edit', $stok->id) }}" class="inline-block bg-yellow-400 text-white hover:bg-yellow-500 px-4 py-2 rounded-lg shadow transition">
                        <i class="fas fa-edit mr-1"></i> Ubah
                    </a>
                </div>
            </div>
        </section>
    </main>
@endsection
