@extends('layouts.owner')

@section('content')
    <main class="flex-1 flex flex-col font-['Poppins'] bg-[#D4E6B5] min-h-screen">
        <!-- Top Banner -->
        <header class="topbar p-6 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-[#877B66]">Data Stok Distribusi</h1>
                    <p class="text-sm text-gray-700">Kelola data stok produk yang tersedia untuk distribusi</p>
                </div>
                <div class="text-right text-gray-800">
                    <p class="font-semibold">Halo, <span class="italic">{{ Auth::user()->owner->nama }}</span></p>
                </div>
            </div>
        </header>

        <!-- Main Content -->
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

            <a href="{{ route('owner.stok.create') }}" class="inline-block mb-4 bg-[#AFC97E] text-white hover:bg-[#8fa866] px-4 py-2 rounded-lg shadow transition">
                <i class="fas fa-plus mr-2"></i> Tambah Stok
            </a>

            <!-- Table (tidak diubah sesuai permintaan) -->
            <div class="overflow-x-auto bg-white p-6 rounded-xl shadow">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Stok</th>
                                <th>Gambar</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($stokDistribusi as $index => $stok)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $stok->nama_stok }}</td>
                                    <td>
                                        <img src="{{ asset($stok->gambar_stok) }}"
                                             alt="{{ $stok->nama_stok }}"
                                             class="img-thumbnail rounded shadow"
                                             style="max-height: 100px; max-width: 150px;">
                                    </td>
                                    <td>Rp {{ number_format($stok->harga_stok, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('owner.stok.show', $stok->id) }}" class="btn btn-info btn-sm">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data stok distribusi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
@endsection
