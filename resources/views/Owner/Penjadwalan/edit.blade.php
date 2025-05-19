@extends('layouts.owner')

@section('content')
    <main class="flex-1 flex flex-col font-['Poppins'] bg-[#D4E6B5] min-h-screen">
        <!-- Top Banner -->
        <header class="topbar p-6 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-[#877B66]">Edit Jadwal Pembiakan</h1>
                    <p class="text-sm text-gray-700">Ubah detail jadwal penetasan telur bebek</p>
                </div>
                <div class="text-right text-gray-800">
                    <p class="font-semibold">Halo, <span class="italic">{{ Auth::user()->owner->nama }}</span></p>
                </div>
            </div>
        </header>
        <section class="p-8">
            @if ($errors->any())
                <div class="bg-red-500 text-white px-4 py-2 rounded-lg shadow mb-4">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="bg-white p-6 rounded-xl shadow max-w-3xl mx-auto">
                @if(isset($penjadwalanKegiatan))
                    <form action="{{ route('owner.penjadwalan.update', $penjadwalanKegiatan->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="tgl_penjadwalan" class="block text-sm font-medium text-[#877B66] mb-1">Tanggal Penjadwalan:</label>
                            <input type="date" name="tgl_penjadwalan" class="form-input w-full border-gray-300 rounded-lg shadow-sm focus:ring-[#AFC97E] focus:border-[#AFC97E]" value="{{ $penjadwalanKegiatan->tgl_penjadwalan->format('Y-m-d') }}" required>
                        </div>

                        <h3 class="text-lg font-semibold text-[#877B66] border-b pb-2">Detail Kegiatan</h3>

                        @foreach($penjadwalanKegiatan->detailPenjadwalan as $detail)
                            <div class="border border-gray-200 p-4 rounded-lg bg-gray-50 space-y-4">
                                <input type="hidden" name="detail_penjadwalan[{{ $loop->index }}][id]" value="{{ $detail->id }}">

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Kegiatan:</label>
                                    <input type="time" name="detail_penjadwalan[{{ $loop->index }}][waktu_kegiatan]" class="form-input w-full border-gray-300 rounded-lg shadow-sm focus:ring-[#AFC97E] focus:border-[#AFC97E]" value="{{ \Carbon\Carbon::parse($detail->waktu_kegiatan)->format('H:i') }}" required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan:</label>
                                    <input type="text" name="detail_penjadwalan[{{ $loop->index }}][keterangan]" class="form-input w-full border-gray-300 rounded-lg shadow-sm focus:ring-[#AFC97E] focus:border-[#AFC97E]" value="{{ $detail->keterangan }}" required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Kegiatan:</label>
                                    <select name="detail_penjadwalan[{{ $loop->index }}][id_status_kegiatan]" class="form-select w-full border-gray-300 rounded-lg shadow-sm focus:ring-[#AFC97E] focus:border-[#AFC97E]" required>
                                        @foreach($statusKegiatan as $status)
                                        <option value="{{ $status->id }}" {{ $detail->id_status_kegiatan == $status->id ? 'selected' : '' }}>
                                            {{ $status->nama_status_kgtn }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endforeach

                        <div class="text-right">
                            <button type="submit" class="bg-[#AFC97E] hover:bg-[#8fa866] text-white font-semibold px-6 py-2 rounded-lg shadow transition">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                @else
                    <div class="text-center py-8">
                        <p class="text-red-500">Data penjadwalan tidak ditemukan</p>
                    </div>
                @endif
            </div>
        </section>
    </main>
@endsection
