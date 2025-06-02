@extends('layouts.owner')

@section('content')

<main class="flex-1 flex flex-col font-['Poppins'] bg-[#D4E6B5] min-h-screen">
    <section class="p-8">
        <div class="bg-white p-6 rounded-xl shadow mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-700">Grafik Keuangan</h2>
                <button id="openCreateModal"
                    class="bg-[#AFC97E] text-white hover:bg-[#8fa866] px-4 py-2 rounded shadow text-sm transition">
                    Tambah Data
                </button>
            </div>
            <canvas id="keuanganChart" width="400" height="200"></canvas>
        </div>

        <div class="overflow-x-auto bg-white p-6 rounded-xl shadow">
            <table class="min-w-full divide-y divide-gray-300 text-sm text-left text-gray-700">
                <thead class="bg-[#FFDF64] text-[#877B66]">
                    <tr>
                        <th class="px-4 py-2 font-semibold">No</th>
                        <th class="px-4 py-2 font-semibold">Tanggal Rekapitulasi</th>
                        <th class="px-4 py-2 font-semibold">Saldo Pemasukkan</th>
                        <th class="px-4 py-2 font-semibold">Saldo Pengeluaran</th>
                        <th class="px-4 py-2 font-semibold">Total Penjualan</th>
                        <th class="px-4 py-2 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($keuangans as $index => $keuangan)
                        <tr>
                            <td class="px-4 py-3">{{ $index + 1 }}</td>
                            <td class="px-4 py-3">{{ $keuangan->tgl_rekapitulasi }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($keuangan->saldo_pemasukkan, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($keuangan->saldo_pengeluaran, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">{{ $keuangan->total_penjualan }}</td>
                            <td class="px-4 py-3">
                                <button onclick="openDetailModal({{ $keuangan->id }}, '{{ $keuangan->tgl_rekapitulasi }}', {{ $keuangan->saldo_pemasukkan }}, {{ $keuangan->saldo_pengeluaran }}, {{ $keuangan->total_penjualan }})"
                                    class="inline-block bg-[#AFC97E] text-white hover:bg-[#8fa866] px-3 py-1 rounded shadow text-sm transition">
                                    Detail
                                </button>
                                <a href="{{ route('owner.keuangan.edit', $keuangan->id) }}"
                                    class="inline-block bg-[#FFDF64] text-[#877B66] hover:bg-[#e6c64f] px-3 py-1 rounded shadow text-sm transition">
                                    Ubah
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-center text-gray-500">Tidak ada data keuangan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</main>

<div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Tambah Data Keuangan</h3>
                <button id="closeCreateModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="createForm" action="{{ route('owner.keuangan.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Rekapitulasi</label>
                    <p class="text-sm text-gray-600 bg-gray-50 p-2 rounded">{{ now()->format('Y-m-d') }} (Hari Ini)</p>
                    <input type="hidden" name="tgl_rekapitulasi" value="{{ now()->toDateString() }}">
                </div>

                <div class="mb-4">
                    <label for="modal_saldo_pengeluaran" class="block text-sm font-medium text-gray-700">Saldo Pengeluaran</label>
                    <input type="number" name="saldo_pengeluaran" id="modal_saldo_pengeluaran"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#AFC97E] focus:border-[#AFC97E]"
                        placeholder="Masukkan saldo pengeluaran" required>
                    <span id="saldo_pengeluaran_error" class="text-red-500 text-sm hidden"></span>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" id="cancelCreate"
                        class="bg-gray-300 text-gray-700 hover:bg-gray-400 px-4 py-2 rounded shadow text-sm transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="bg-[#AFC97E] text-white hover:bg-[#8fa866] px-4 py-2 rounded shadow text-sm transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Detail Data Keuangan</h3>
                <button id="closeDetailModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal Rekapitulasi</label>
                    <p id="detail_tgl_rekapitulasi" class="mt-1 text-sm text-gray-900 bg-gray-50 p-2 rounded"></p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Saldo Pemasukkan</label>
                    <p id="detail_saldo_pemasukkan" class="mt-1 text-sm text-gray-900 bg-gray-50 p-2 rounded"></p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Saldo Pengeluaran</label>
                    <p id="detail_saldo_pengeluaran" class="mt-1 text-sm text-gray-900 bg-gray-50 p-2 rounded"></p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Total Penjualan</label>
                    <p id="detail_total_penjualan" class="mt-1 text-sm text-gray-900 bg-gray-50 p-2 rounded"></p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Saldo Bersih</label>
                    <p id="detail_saldo_bersih" class="mt-1 text-sm font-medium bg-gray-50 p-2 rounded"></p>
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button id="closeDetailBtn"
                    class="bg-gray-300 text-gray-700 hover:bg-gray-400 px-4 py-2 rounded shadow text-sm transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('keuanganChart').getContext('2d');
    const keuanganChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($keuanganLabels) !!},
            datasets: [
                {
                    label: 'Saldo Pemasukkan',
                    data: {!! json_encode($keuanganPemasukkan) !!},
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                },
                {
                    label: 'Saldo Pengeluaran',
                    data: {!! json_encode($keuanganPengeluaran) !!},
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderWidth: 2,
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Tanggal Rekapitulasi',
                    },
                },
                y: {
                    title: {
                        display: true,
                        text: 'Saldo (Rp)',
                    },
                },
            },
        },
    });

    const createModal = document.getElementById('createModal');
    const detailModal = document.getElementById('detailModal');
    const openCreateModalBtn = document.getElementById('openCreateModal');
    const closeCreateModalBtn = document.getElementById('closeCreateModal');
    const cancelCreateBtn = document.getElementById('cancelCreate');
    const closeDetailModalBtn = document.getElementById('closeDetailModal');
    const closeDetailBtn = document.getElementById('closeDetailBtn');

    openCreateModalBtn.addEventListener('click', function() {
        createModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    });

    function closeCreateModal() {
        createModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('createForm').reset();
        document.getElementById('saldo_pengeluaran_error').classList.add('hidden');
    }

    closeCreateModalBtn.addEventListener('click', closeCreateModal);
    cancelCreateBtn.addEventListener('click', closeCreateModal);

    function closeDetailModal() {
        detailModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    closeDetailModalBtn.addEventListener('click', closeDetailModal);
    closeDetailBtn.addEventListener('click', closeDetailModal);

    function openDetailModal(id, tanggal, pemasukkan, pengeluaran, totalPenjualan) {
        document.getElementById('detail_tgl_rekapitulasi').textContent = tanggal;
        document.getElementById('detail_saldo_pemasukkan').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(pemasukkan);
        document.getElementById('detail_saldo_pengeluaran').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(pengeluaran);
        document.getElementById('detail_total_penjualan').textContent = totalPenjualan;

        const saldoBersih = pemasukkan - pengeluaran;
        const saldoBersihElement = document.getElementById('detail_saldo_bersih');
        saldoBersihElement.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(saldoBersih);

        if (saldoBersih > 0) {
            saldoBersihElement.className = 'mt-1 text-sm font-medium bg-green-50 text-green-700 p-2 rounded';
        } else if (saldoBersih < 0) {
            saldoBersihElement.className = 'mt-1 text-sm font-medium bg-red-50 text-red-700 p-2 rounded';
        } else {
            saldoBersihElement.className = 'mt-1 text-sm font-medium bg-gray-50 text-gray-700 p-2 rounded';
        }

        detailModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    window.addEventListener('click', function(event) {
        if (event.target === createModal) {
            closeCreateModal();
        }
        if (event.target === detailModal) {
            closeDetailModal();
        }
    });

    document.getElementById('createForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;

        submitBtn.textContent = 'Menyimpan...';
        submitBtn.disabled = true;

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeCreateModal();
                location.reload();
            } else {
                if (data.errors) {
                    if (data.errors.saldo_pengeluaran) {
                        document.getElementById('saldo_pengeluaran_error').textContent = data.errors.saldo_pengeluaran[0];
                        document.getElementById('saldo_pengeluaran_error').classList.remove('hidden');
                    }
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.submit();
        })
        .finally(() => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
    });
</script>

@endsection
