@extends('layouts.owner')

@section('content')
    <main class="flex-1 flex flex-col font-['Poppins'] bg-[#D4E6B5] min-h-screen">
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

            @if(session('error'))
                <div class="bg-red-500 text-white px-4 py-2 rounded-lg shadow mb-4">
                    {{ session('error') }}
                </div>
                <script>
                    setTimeout(() => {
                        document.querySelector('.bg-red-500').remove();
                    }, 3000);
                </script>
            @endif
            <div class="bg-white p-6 rounded-xl shadow mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Filter Jadwal</h3>
                <form method="GET" action="{{ route('owner.penjadwalan.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="month" class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                        <select name="month" id="month" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#AFC97E] focus:border-transparent">
                            <option value="">Semua Bulan</option>
                            @foreach($availableMonths as $monthNum => $monthName)
                                <option value="{{ $monthNum }}" {{ $filterMonth == $monthNum ? 'selected' : '' }}>
                                    {{ $monthName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                        <select name="year" id="year" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#AFC97E] focus:border-transparent">
                            <option value="">Semua Tahun</option>
                            @foreach($availableYears as $year)
                                <option value="{{ $year }}" {{ $filterYear == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="bg-[#AFC97E] text-white hover:bg-[#8fa866] px-4 py-2 rounded-lg shadow transition">
                            <i class="fas fa-filter mr-2"></i> Filter
                        </button>
                        <a href="{{ route('owner.penjadwalan.index') }}" class="bg-gray-500 text-white hover:bg-gray-600 px-4 py-2 rounded-lg shadow transition">
                            <i class="fas fa-times mr-2"></i> Reset
                        </a>
                    </div>
                    <div class="flex items-end">
                        <a href="{{ route('owner.penjadwalan.create') }}" class="bg-[#AFC97E] text-white hover:bg-[#8fa866] px-4 py-2 rounded-lg shadow transition">
                            <i class="fas fa-plus mr-2"></i> Tambah Jadwal
                        </a>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto bg-white p-6 rounded-xl shadow">
                <table class="min-w-full divide-y divide-gray-300 text-sm text-left text-gray-700">
                    <thead class="bg-[#FFDF64] text-[#877B66]">
                        <tr>
                            <th class="px-4 py-3 font-semibold text-center">Tanggal</th>
                            <th class="px-4 py-3 font-semibold text-center">Waktu</th>
                            <th class="px-4 py-3 font-semibold text-center">Detail Kegiatan</th>
                            <th class="px-4 py-3 font-semibold text-center">Keterangan</th>
                            <th class="px-4 py-3 font-semibold text-center">Status Kegiatan</th>
                            <th class="px-4 py-3 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @php $rowIndex = 0; @endphp
                        @forelse($penjadwalanKegiatans as $penjadwalan)
                            <tr class="{{ $rowIndex % 2 == 0 ? 'bg-white' : 'bg-gray-100' }}">
                                <td class="px-4 py-3 text-center">
                                    @php
                                        \Carbon\Carbon::setLocale('id');
                                        $formattedDate = \Carbon\Carbon::parse($penjadwalan->tgl_penjadwalan)->translatedFormat('j F Y');
                                    @endphp
                                    {{ $formattedDate }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @foreach($penjadwalan->detailPenjadwalan as $detail)
                                        @php
                                            $formattedTime = \Carbon\Carbon::parse($detail->waktu_kegiatan)->format('H:i');
                                        @endphp
                                        <div class="mb-1">{{ $formattedTime }}</div>
                                    @endforeach
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @foreach($penjadwalan->detailPenjadwalan as $detail)
                                        <div class="mb-1">{{ $detail->keterangan }}</div>
                                    @endforeach
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex flex-col items-center gap-1">
                                        @foreach($penjadwalan->detailPenjadwalan as $detail)
                                            @php
                                                $statusName = $detail->statusKegiatan->nama_status_kgtn;
                                                $badgeColor = '';

                                                switch($statusName) {
                                                    case 'To Do':
                                                        $badgeColor = 'bg-blue-500';
                                                        break;
                                                    case 'Selesai':
                                                        $badgeColor = 'bg-green-500';
                                                        break;
                                                    case 'Gagal':
                                                        $badgeColor = 'bg-red-500';
                                                        break;
                                                    default:
                                                        $badgeColor = 'bg-gray-500';
                                                }
                                            @endphp
                                            <div class="flex justify-center">
                                                <span class="px-2 py-1 text-xs font-medium text-white rounded-full {{ $badgeColor }}">
                                                    {{ $statusName }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-1">
                                        <div class="flex flex-col gap-1">
                                            @foreach($penjadwalan->detailPenjadwalan as $detail)
                                                @php
                                                    $scheduledDateTime = \Carbon\Carbon::parse($penjadwalan->tgl_penjadwalan->format('Y-m-d') . ' ' . $detail->waktu_kegiatan);
                                                    $currentDateTime = \Carbon\Carbon::now();
                                                    $isLate = $currentDateTime->diffInMinutes($scheduledDateTime, false) < -30;
                                                @endphp
                                                <div class="flex gap-1 items-center">
                                                    @if($detail->statusKegiatan->nama_status_kgtn === 'To Do')
                                                        @if($isLate)
                                                            <button type="button" disabled class="px-3 py-1 rounded text-xs font-medium bg-green-300 text-gray-700 cursor-not-allowed opacity-60">
                                                                <i class="fas fa-check mr-1"></i> Selesai
                                                            </button>
                                                        @else
                                                            <form action="{{ route('owner.penjadwalan.duration', $detail->id) }}" method="POST" class="inline">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="status" value="Selesai">
                                                                <button type="submit"
                                                                    class="px-3 py-1 rounded text-xs font-medium transition bg-green-500 text-white hover:bg-green-600"
                                                                    onclick="return checkScheduleTime('{{ $penjadwalan->tgl_penjadwalan->format('Y-m-d') }} {{ $detail->waktu_kegiatan }}')">
                                                                    <i class="fas fa-check mr-1"></i> Selesai
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @elseif($detail->statusKegiatan->nama_status_kgtn === 'Selesai')
                                                        <span class="px-3 py-1 rounded text-xs font-medium bg-gray-100 text-gray-600">
                                                            <i class="fas fa-check mr-1"></i> Sudah Selesai
                                                        </span>
                                                    @elseif($detail->statusKegiatan->nama_status_kgtn === 'Gagal')
                                                        <span class="px-3 py-1 rounded text-xs font-medium bg-red-100 text-red-600">
                                                            <i class="fas fa-times mr-1"></i> Gagal
                                                        </span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items justify-center">
                                        <div class="flex flex-col gap-1">
                                            <a href="{{ route('owner.penjadwalan.edit', $penjadwalan->id) }}" class="bg-yellow-400 text-white hover:bg-yellow-500 px-3 py-1 rounded text-xs font-medium transition">
                                                <i class="fas fa-edit mr-1"></i> Ubah
                                            </a>
                                            <form action="{{ route('owner.penjadwalan.delete', $penjadwalan->id) }}" method="POST" class="inline" onsubmit="return confirmDelete()">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full bg-red-500 text-white hover:bg-red-600 px-3 py-1 rounded text-xs font-medium transition">
                                                    <i class="fas fa-trash mr-1"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @php $rowIndex++; @endphp
                        @empty
                            <tr class="bg-white">
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                    <i class="fas fa-calendar-times text-4xl mb-2 text-gray-300"></i>
                                    <p>
                                        @if($filterMonth || $filterYear)
                                            Tidak ada jadwal untuk filter yang dipilih
                                        @else
                                            Belum ada jadwal
                                        @endif
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            <div class="mt-4">
                {{ $penjadwalanKegiatans->links() }} <!-- Add this line for pagination links -->
                </div>
            </div>
        </section>
    </main>

    <script>
        function checkScheduleTime(scheduledDateTimeStr) {
            const scheduledDateTime = new Date(scheduledDateTimeStr);
            const now = new Date();

            if (now < scheduledDateTime) {
                alert('Penjadwalan belum waktunya');
                return false;
            }

            return true;
        }

        function confirmDelete() {
            return confirm('Apakah Anda yakin ingin menghapus jadwal ini? Tindakan ini tidak dapat dibatalkan.');
        }
    </script>
@endsection
