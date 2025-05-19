{{-- filepath: c:\laragon\www\simobi-penetasan-telur-bebek\resources\views\Owner\Keuangan\index.blade.php --}}
@extends('layouts.owner')

@section('content')

<main class="flex-1 flex flex-col font-['Poppins'] bg-[#D4E6B5] min-h-screen">
    <!-- Top Banner -->
    <header class="topbar p-6 shadow-md">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-[#877B66]">Data Keuangan</h1>
                <p class="text-sm text-gray-700">Daftar data keuangan yang telah tercatat</p>
            </div>
            <a href="{{ route('owner.keuangan.create') }}"
                class="bg-[#AFC97E] text-white hover:bg-[#8fa866] px-4 py-2 rounded shadow text-sm transition">
                Tambah Data
            </a>
        </div>
    </header>

    <!-- Grafik -->
    <section class="p-8">
        <div class="bg-white p-6 rounded-xl shadow mb-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Grafik Keuangan</h2>
            <canvas id="keuanganChart" width="400" height="200"></canvas>
        </div>

        <!-- Tabel Data -->
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
                                <a href="{{ route('owner.keuangan.show', $keuangan->id) }}"
                                    class="inline-block bg-[#AFC97E] text-white hover:bg-[#8fa866] px-3 py-1 rounded shadow text-sm transition">
                                    Detail
                                </a>
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
                    data: {!! json_encode($keuanganPemasukkan) !!}, // Data saldo pemasukkan
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                },
                {
                    label: 'Saldo Pengeluaran',
                    data: {!! json_encode($keuanganPengeluaran) !!}, // Data saldo pengeluaran
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
</script>

@endsection
