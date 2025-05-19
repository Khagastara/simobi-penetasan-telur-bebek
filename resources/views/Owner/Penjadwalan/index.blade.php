@extends('layouts.owner')

@section('content')
    <main class="flex-1 flex flex-col font-['Poppins'] bg-[#D4E6B5] min-h-screen">

        <!-- Top Banner -->
        <header class="topbar p-6 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-[#877B66]">Jadwal Pembiakan</h1>
                    <p class="text-sm text-gray-700">Kelola jadwal penetasan telur bebek</p>
                </div>
                <div class="text-right text-gray-800">
                    <p class="font-semibold">Halo, <span class="italic">{{ Auth::user()->owner->nama }}</span></p>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <section class="p-8">
            @if(session('success'))
                <div class="bg-green-500 text-white px-4 py-2 rounded-lg shadow mb-4">
                    {{ session('success') }}
                </div>
                <script>
                    setTimeout(() => {
                        document.querySelector('.bg-green-500').remove();
                    }, 3000);
                </script>
            @endif

            <a href="{{ route('owner.penjadwalan.create') }}" class="inline-block mb-4 bg-[#AFC97E] text-white hover:bg-[#8fa866] px-4 py-2 rounded-lg shadow transition">
                <i class="fas fa-plus mr-2"></i> Tambah Jadwal
            </a>

            <div class="overflow-x-auto bg-white p-6 rounded-xl shadow">
                <table class="min-w-full divide-y divide-gray-300 text-sm text-left text-gray-700">
                    <thead class="bg-[#FFDF64] text-[#877B66]">
                        <tr>
                            <th class="px-4 py-2 font-semibold">Tanggal</th>
                            <th class="px-4 py-2 font-semibold">Detail Kegiatan</th>
                            <th class="px-4 py-2 font-semibold">Status Kegiatan</th>
                            <th class="px-4 py-2 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($penjadwalanKegiatans as $penjadwalan)
                            <tr>
                                <td class="px-4 py-3">{{ $penjadwalan->tgl_penjadwalan->format('d-m-Y') }}</td>
                                <td class="px-4 py-3">
                                    @foreach($penjadwalan->detailPenjadwalan as $detail)
                                        <div class="mb-1">{{ $detail->waktu_kegiatan }} - {{ $detail->keterangan }}</div>
                                    @endforeach
                                </td>
                                <td class="px-4 py-3">
                                    @foreach($penjadwalan->detailPenjadwalan as $status)
                                        <div class="mb-1">{{ $status->statusKegiatan->nama_status_kgtn }}</div>
                                    @endforeach
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('owner.penjadwalan.edit', $penjadwalan->id) }}" class="inline-block bg-yellow-400 text-white hover:bg-yellow-500 px-3 py-1 rounded shadow">
                                        <i class="fas fa-edit mr-1"></i> Ubah
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-center text-gray-500">Belum ada jadwal</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>
@endsection
