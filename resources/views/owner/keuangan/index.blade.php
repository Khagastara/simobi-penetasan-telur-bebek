@extends('layouts.owner')

@section('content')

<main class="flex-1 flex flex-col font-['Poppins'] bg-[#D4E6B5] min-h-screen">
    <section class="p-8">
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-white">
                    <span class="text-[#877B66]">Periode Keuangan:</span>
                    <span class="ml-4 mr-4 bg-white px-4 py-2 rounded-full text-gray-700">
                        Tahun {{ $currentYear }}
                    </span>
                    <form method="GET" action="{{ route('owner.keuangan.index') }}" class="inline">
                        <input type="hidden" name="direction" value="prev">
                        <input type="hidden" name="current_year" value="{{ $currentYear }}">
                        <button type="submit" class="bg-white p-2 rounded-lg shadow hover:shadow-lg transition-all">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                    </form>
                    <form method="GET" action="{{ route('owner.keuangan.index') }}" class="inline">
                        <input type="hidden" name="direction" value="next">
                        <input type="hidden" name="current_year" value="{{ $currentYear }}">
                        <button type="submit" class="bg-white p-2 rounded-lg shadow hover:shadow-lg transition-all">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </form>
                </h2>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-3xl p-8 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-cyan-100 rounded-2xl flex items-center justify-center mr-4 border-2 border-blue-300">
                        <svg class="w-8 h-8" fill="none" stroke="url(#gradient2)" viewBox="0 0 24 24">
                            <defs>
                                <linearGradient id="gradient2" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color:#3B82F6;stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:#06B6D4;stop-opacity:1" />
                                </linearGradient>
                            </defs>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-gray-600 text-sm mb-1">Total Pemasukan</h3>
                        <p class="text-2xl font-bold text-gray-800">Rp{{ number_format($totalPemasukan, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-8 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-red-100 to-pink-100 rounded-2xl flex items-center justify-center mr-4 border-2 border-red-300">
                        <svg class="w-8 h-8" fill="none" stroke="url(#gradient3)" viewBox="0 0 24 24">
                            <defs>
                                <linearGradient id="gradient3" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color:#EF4444;stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:#EC4899;stop-opacity:1" />
                                </linearGradient>
                            </defs>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-gray-600 text-sm mb-1">Total Pengeluaran</h3>
                        <p class="text-2xl font-bold text-gray-800">Rp{{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-8 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-yellow-100 to-orange-100 rounded-2xl flex items-center justify-center mr-4 border-2 border-yellow-300">
                        <svg class="w-8 h-8" fill="none" stroke="url(#gradient1)" viewBox="0 0 24 24">
                            <defs>
                                <linearGradient id="gradient1" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color:#FCD34D;stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:#F97316;stop-opacity:1" />
                                </linearGradient>
                            </defs>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-gray-600 text-sm mb-1">Selisih Keuangan</h3>
                        <div class="flex items-center">
                            @if(($totalPemasukan - $totalPengeluaran) >= 0)
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#10B981" class="bi bi-arrow-up-circle-fill mr-2" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 0 0 8a8 8 0 0 0 16 0m-7.5 3.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707z"/>
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#EF4444" class="bi bi-arrow-down-circle-fill mr-2" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293z"/>
                                </svg>
                            @endif
                            <p class="text-2xl font-bold text-gray-800">Rp{{ number_format($totalPemasukan - $totalPengeluaran, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-700">Grafik Keuangan Tahunan {{ $currentYear }}</h2>
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
                    <tr class="text-center">
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
                        <tr class="text-center">
                            <td class="px-4 py-3">{{ $index + 1 }}</td>
                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($keuangan->tgl_rekapitulasi)->format('d M Y') }}</td>
                            <td class="px-4 py-3">Rp{{ number_format($keuangan->saldo_pemasukkan, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">Rp{{ number_format($keuangan->saldo_pengeluaran, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">{{ $keuangan->total_penjualan }}</td>
                            <td class="px-4 py-3">
                                <button onclick="openDetailModal({{ $keuangan->id }}, '{{ \Carbon\Carbon::parse($keuangan->tgl_rekapitulasi)->format('d/m/Y') }}', {{ $keuangan->saldo_pemasukkan }}, {{ $keuangan->saldo_pengeluaran }}, {{ $keuangan->total_penjualan }})"
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
                            <td colspan="6" class="px-4 py-4 text-center text-gray-500">Tidak ada data keuangan untuk tahun {{ $currentYear }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</main>

<!-- Modal Tambah Data -->
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
                    <label for="modal_tgl_rekapitulasi" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Rekapitulasi</label>
                    <input type="date" name="tgl_rekapitulasi" id="modal_tgl_rekapitulasi"
                        class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm focus:ring-[#AFC97E] focus:border-[#AFC97E] cursor-not-allowed"
                        readonly required>
                    <p class="text-xs text-gray-500 mt-1">Tanggal otomatis diatur ke hari ini</p>
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

<!-- Modal Detail -->
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
                    <p id="detail_saldo_pemasukkan" class="mt-1 text-sm font-medium bg-blue-50 text-blue-700 p-2 rounded"></p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Saldo Pengeluaran</label>
                    <p id="detail_saldo_pengeluaran" class="mt-1 text-sm font-medium bg-red-50 text-red-700 p-2 rounded"></p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Total Penjualan</label>
                    <p id="detail_total_penjualan" class="mt-1 text-sm text-gray-900 bg-gray-50 p-2 rounded"></p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Saldo Bersih</label>
                    <p id="detail_saldo_bersih" class="mt-1 text-sm font-medium p-2 rounded"></p>
                </div>

                <div class="flex justify-end">
                    <button type="button" id="closeDetailBtn"
                        class="bg-gray-300 text-gray-700 hover:bg-gray-400 px-4 py-2 rounded shadow text-sm transition">
                        Tutup
                    </button>
                </div>
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
                    borderColor: 'rgba(59, 130, 246, 1)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                },
                {
                    label: 'Saldo Pengeluaran',
                    data: {!! json_encode($keuanganPengeluaran) !!},
                    borderColor: 'rgba(239, 68, 68, 1)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(239, 68, 68, 1)',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 14,
                            family: 'Poppins'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: 'rgba(255, 255, 255, 0.2)',
                    borderWidth: 1,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                        }
                    }
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Bulan',
                        font: {
                            size: 14,
                            family: 'Poppins',
                            weight: 'bold'
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)',
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Saldo (Rp)',
                        font: {
                            size: 14,
                            family: 'Poppins',
                            weight: 'bold'
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)',
                    },
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                },
            },
        },
    });

    document.addEventListener('DOMContentLoaded', function() {
        setTodayDate();
    });

    function setTodayDate() {
        const today = new Date();
        const formattedDate = today.getFullYear() + '-' +
            String(today.getMonth() + 1).padStart(2, '0') + '-' +
            String(today.getDate()).padStart(2, '0');

        const dateInput = document.getElementById('modal_tgl_rekapitulasi');
        if (dateInput) {
            dateInput.value = formattedDate;
        }
    }

    const createModal = document.getElementById('createModal');
    const detailModal = document.getElementById('detailModal');
    const openCreateModalBtn = document.getElementById('openCreateModal');
    const closeCreateModalBtn = document.getElementById('closeCreateModal');
    const cancelCreateBtn = document.getElementById('cancelCreate');
    const closeDetailModalBtn = document.getElementById('closeDetailModal');
    const closeDetailBtn = document.getElementById('closeDetailBtn');

    openCreateModalBtn.addEventListener('click', function() {
        setTodayDate();

        createModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    });

    function closeCreateModal() {
        createModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('createForm').reset();
        document.getElementById('saldo_pengeluaran_error').classList.add('hidden');

        setTodayDate();
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
                    if (data.errors.tgl_rekapitulasi) {
                        alert(data.errors.tgl_rekapitulasi[0]);
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
