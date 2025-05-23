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

            <a href="{{ route('owner.penjadwalan.create') }}" class="inline-block mb-4 bg-[#AFC97E] text-white hover:bg-[#8fa866] px-4 py-2 rounded-lg shadow transition">
                <i class="fas fa-plus mr-2"></i> Tambah Jadwal
            </a>

            <div class="overflow-x-auto bg-white p-6 rounded-xl shadow">
                <table class="min-w-full divide-y divide-gray-300 text-sm text-left text-gray-700">
                    <thead class="bg-[#FFDF64] text-[#877B66]">
                        <tr>
                            <th class="px-4 py-3 font-semibold text-center">Tanggal</th>
                            <th class="px-4 py-3 font-semibold text-center">Waktu</th>
                            <th class="px-4 py-3 font-semibold text-center">Detail Kegiatan</th>
                            <th class="px-4 py-3 font-semibold text-center">Status Kegiatan</th>
                            <th class="px-4 py-3 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @php $rowIndex = 0; @endphp
                        @forelse($penjadwalanKegiatans as $penjadwalan)
                            <tr class="{{ $rowIndex % 2 == 0 ? 'bg-white' : 'bg-gray-100' }}">
                                <td class="px-4 py-3 text-center">
                                    {{ $penjadwalan->tgl_penjadwalan->format('d-m-Y') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @foreach($penjadwalan->detailPenjadwalan as $detail)
                                        <div class="mb-1">{{ $detail->waktu_kegiatan }}</div>
                                    @endforeach
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @foreach($penjadwalan->detailPenjadwalan as $detail)
                                        <div class="mb-1">{{ $detail->keterangan }}</div>
                                    @endforeach
                                </td>
                                <td class="px-4 py-3 text-center">
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
                                        <div class="mb-1">
                                            <span class="px-2 py-1 text-xs font-medium text-white rounded-full {{ $badgeColor }}">
                                                {{ $statusName }}
                                            </span>
                                        </div>
                                    @endforeach
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-1">
                                        <div class="flex flex-col gap-1">
                                            @foreach($penjadwalan->detailPenjadwalan as $detail)
                                                @php
                                                    $scheduledDateTime = \Carbon\Carbon::parse($penjadwalan->tgl_penjadwalan->format('Y-m-d') . ' ' . $detail->waktu_kegiatan);
                                                    $currentDateTime = \Carbon\Carbon::now();
                                                    $isLate = $currentDateTime->diffInMinutes($scheduledDateTime, false) < -30;

                                                    $selesaiButtonClass = $isLate ? 'bg-green-300 text-gray-700 cursor-not-allowed opacity-60' : 'bg-green-500 text-white hover:bg-green-600';
                                                @endphp
                                                <div class="flex gap-1 items-center">
                                                    @if($detail->statusKegiatan->nama_status_kgtn !== 'Selesai' && $detail->statusKegiatan->nama_status_kgtn !== 'Gagal')
                                                        @if($isLate)
                                                            <button type="button" disabled class="px-3 py-1 rounded text-xs font-medium {{ $selesaiButtonClass }}">
                                                                <i class="fas fa-check mr-1"></i> Selesai
                                                            </button>
                                                        @else
                                                            <form action="{{ route('owner.penjadwalan.duration', $detail->id) }}" method="POST" class="inline">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="status" value="Selesai">
                                                                <button type="submit"
                                                                    class="px-3 py-1 rounded text-xs font-medium transition {{ $selesaiButtonClass }}"
                                                                    onclick="return checkScheduleTime('{{ $penjadwalan->tgl_penjadwalan->format('Y-m-d') }} {{ $detail->waktu_kegiatan }}')">
                                                                    <i class="fas fa-check mr-1"></i> Selesai
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @else
                                                        @if($isLate)
                                                            <button type="button" disabled class="px-3 py-1 rounded text-xs font-medium {{ $selesaiButtonClass }}">
                                                                <i class="fas fa-check mr-1"></i> Selesai
                                                            </button>
                                                        @else
                                                            <form action="{{ route('owner.penjadwalan.duration', $detail->id) }}" method="POST" class="inline">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="status" value="Selesai">
                                                                <button type="submit" class="px-3 py-1 rounded text-xs font-medium transition {{ $selesaiButtonClass }}">
                                                                    <i class="fas fa-check mr-1"></i> Selesai
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                        <a href="{{ route('owner.penjadwalan.edit', $penjadwalan->id) }}" class="bg-yellow-400 text-white hover:bg-yellow-500 px-3 py-1 rounded text-xs font-medium transition ml-1">
                                            <i class="fas fa-edit mr-1"></i> Ubah
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @php $rowIndex++; @endphp
                        @empty
                            <tr class="bg-white">
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                    <i class="fas fa-calendar-times text-4xl mb-2 text-gray-300"></i>
                                    <p>Belum ada jadwal</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
    </script>
@endsection
