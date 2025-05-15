@extends('layouts.app')

@section('content')
<main class="flex-1 flex flex-col font-['Poppins'] bg-[#D4E6B5] min-h-screen">
    <!-- Top Banner -->
    <header class="topbar p-6 shadow-md">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-[#877B66]">Riwayat Transaksi</h1>
                <p class="text-sm text-gray-700">Daftar transaksi yang telah dilakukan</p>
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

        <div class="overflow-x-auto bg-white p-6 rounded-xl shadow">
            <table class="min-w-full divide-y divide-gray-300 text-sm text-left text-gray-700">
                <thead class="bg-[#FFDF64] text-[#877B66]">
                    <tr>
                        <th class="px-4 py-2 font-semibold">No</th>
                        <th class="px-4 py-2 font-semibold">Username</th>
                        <th class="px-4 py-2 font-semibold">ID Transaksi</th>
                        <th class="px-4 py-2 font-semibold">Nama Stok</th>
                        <th class="px-4 py-2 font-semibold">Kuantitas</th>
                        <th class="px-4 py-2 font-semibold">Total Transaksi</th>
                        <th class="px-4 py-2 font-semibold">Status</th>
                        <th class="px-4 py-2 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($transaksis as $index => $transaksi)
                        <tr>
                            <td class="px-4 py-3">{{ $index + 1 }}</td>
                            <td class="px-4 py-3">{{ $transaksi['username'] }}</td>
                            <td class="px-4 py-3">{{ $transaksi['id'] }}</td>
                            <td class="px-4 py-3">{{ $transaksi['nama_stok'] }}</td>
                            <td class="px-4 py-3">{{ $transaksi['kuantitas'] }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($transaksi['total_transaksi'], 0, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded 
                                    @if($transaksi['status'] === 'Menunggu Pembayaran') bg-yellow-300 text-yellow-800
                                    @elseif($transaksi['status'] === 'Pembayaran Valid') bg-blue-300 text-blue-800
                                    @elseif($transaksi['status'] === 'Packing') bg-indigo-300 text-indigo-800
                                    @elseif($transaksi['status'] === 'Pengiriman') bg-gray-300 text-gray-800
                                    @elseif($transaksi['status'] === 'Selesai') bg-green-300 text-green-800
                                    @else bg-gray-300 text-gray-800
                                    @endif
                                ">
                                    {{ $transaksi['status'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('owner.transaksi.show', $transaksi['id']) }}" 
                                   class="inline-block bg-[#AFC97E] text-white hover:bg-[#8fa866] px-3 py-1 rounded shadow text-sm transition">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-4 text-center text-gray-500">Tidak ada data transaksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</main>
@endsection
