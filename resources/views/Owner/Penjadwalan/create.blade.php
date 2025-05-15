@extends('layouts.app')

@section('content')
    <main class="flex-1 flex flex-col font-['Poppins'] bg-[#D4E6B5] min-h-screen">
        <!-- Top Banner -->
        <header class="topbar p-6 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-[#877B66]">Tambah Jadwal Pembiakan</h1>
                    <p class="text-sm text-gray-700">Isi informasi jadwal dan detail kegiatan pembiakan</p>
                </div>
                <div class="text-right text-gray-800">
                    <p class="font-semibold">Halo, <span class="italic">{{ Auth::user()->owner->nama }}</span></p>
                </div>
        </header>

        <!-- Main Content -->
        <section class="p-8">
            <div class="bg-white p-6 rounded-xl shadow max-w-3xl mx-auto">
                <form action="{{ route('owner.penjadwalan.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="tgl_penjadwalan" class="block text-sm font-medium text-[#877B66] mb-1">Tanggal Penjadwalan:</label>
                        <input type="date" name="tgl_penjadwalan" class="form-input w-full border-gray-300 rounded-lg shadow-sm focus:ring-[#AFC97E] focus:border-[#AFC97E]" required>
                    </div>

                    <h3 class="text-lg font-semibold text-[#877B66] border-b pb-2">Detail Kegiatan</h3>

                    <div id="detail-container" class="space-y-4">
                        <div class="detail-item border border-gray-200 p-4 rounded-lg bg-gray-50 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Kegiatan:</label>
                                <input type="time" name="detail_penjadwalan[0][waktu_kegiatan]" class="form-input w-full border-gray-300 rounded-lg shadow-sm focus:ring-[#AFC97E] focus:border-[#AFC97E]" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan:</label>
                                <input type="text" name="detail_penjadwalan[0][keterangan]" class="form-input w-full border-gray-300 rounded-lg shadow-sm focus:ring-[#AFC97E] focus:border-[#AFC97E]" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status Kegiatan:</label>
                                <select name="detail_penjadwalan[0][id_status_kegiatan]" class="form-select w-full border-gray-300 rounded-lg shadow-sm focus:ring-[#AFC97E] focus:border-[#AFC97E]" required>
                                    @foreach($statusKegiatan as $status)
                                        <option value="{{ $status->id }}">{{ $status->nama_status_kgtn }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <button type="button" id="add-detail" class="bg-[#E2D686] hover:bg-[#FFDF64] text-[#877B66] font-semibold px-4 py-2 rounded-lg shadow transition">
                            + Tambah Detail Kegiatan
                        </button>

                        <button type="submit" class="bg-[#AFC97E] hover:bg-[#8fa866] text-white font-semibold px-6 py-2 rounded-lg shadow transition">
                            Simpan Jadwal
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <script>
        document.getElementById('add-detail').addEventListener('click', function() {
            const container = document.getElementById('detail-container');
            const index = container.getElementsByClassName('detail-item').length;

            const newDetail = document.createElement('div');
            newDetail.classList.add('detail-item', 'border', 'border-gray-200', 'p-4', 'rounded-lg', 'bg-gray-50', 'space-y-4');
            newDetail.innerHTML = `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Kegiatan:</label>
                    <input type="time" name="detail_penjadwalan[${index}][waktu_kegiatan]" class="form-input w-full border-gray-300 rounded-lg shadow-sm focus:ring-[#AFC97E] focus:border-[#AFC97E]" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan:</label>
                    <input type="text" name="detail_penjadwalan[${index}][keterangan]" class="form-input w-full border-gray-300 rounded-lg shadow-sm focus:ring-[#AFC97E] focus:border-[#AFC97E]" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Kegiatan:</label>
                    <select name="detail_penjadwalan[${index}][id_status_kegiatan]" class="form-select w-full border-gray-300 rounded-lg shadow-sm focus:ring-[#AFC97E] focus:border-[#AFC97E]" required>
                        @foreach($statusKegiatan as $status)
                            <option value="{{ $status->id }}">{{ $status->nama_status_kegiatan }}</option>
                        @endforeach
                    </select>
                </div>
            `;
            container.appendChild(newDetail);
        });
    </script>
@endsection
