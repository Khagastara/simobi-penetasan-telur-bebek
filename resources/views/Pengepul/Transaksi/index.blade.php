@extends('layouts.pengepul')

@section('content')
@php
    function getStatusBadgeColor($status) {
        return match($status) {
            'Menunggu Pembayaran' => 'warning',
            'Pembayaran Valid' => 'info',
            'Pembayaran Lunas' => 'lime-500',
            'Packing' => 'primary',
            'Pengiriman' => 'secondary',
            'Selesai' => 'success',
            default => 'secondary',
        };
    }
@endphp

<main class="flex-1 flex flex-col font-['Poppins'] bg-[#D4E6B5] min-h-screen">
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
                    <tr class="text-center">
                        <th class="px-4 py-2 font-semibold">No</th>
                        <th class="px-4 py-2 font-semibold">Tanggal Transaksi</th>
                        <th class="px-4 py-2 font-semibold">Nama Stok</th>
                        <th class="px-4 py-2 font-semibold">Kuantitas</th>
                        <th class="px-4 py-2 font-semibold">Total Transaksi</th>
                        <th class="px-4 py-2 font-semibold">Metode Pembayaran</th>
                        <th class="px-4 py-2 font-semibold">Status</th>
                        <th class="px-4 py-2 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @php $rowIndex = 0; @endphp
                    @forelse ($transaksis as $index => $transaksi)
                        <tr class="{{ $rowIndex % 2 == 0 ? 'bg-white' : 'bg-gray-100' }} text-center">
                            <td class="px-4 py-3">{{ $index + 1 }}</td>
                            <td class="px-4 py-3">{{ $transaksi['tanggal_transaksi']}}</td>
                            <td class="px-4 py-3">{{ $transaksi['nama_stok'] }}</td>
                            <td class="px-4 py-3">{{ $transaksi['kuantitas'] }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($transaksi['total_transaksi'], 0, ',', '.') }}</td>
                            <td class="px-4 py-3">{{ $transaksi['metode_pembayaran'] }}</td>
                            <td class="px-4 py-3">
                                @if($transaksi['status'] == 'Pembayaran Lunas')
                                    <span class="inline-block px-2 py-1 rounded text-white text-xs bg-lime-500">
                                        {{ $transaksi['status'] }}
                                    </span>
                                @elseif ($transaksi['status'] == 'Dibatalkan')
                                    <span class="inline-block px-2 py-1 rounded text-white text-xs bg-red-500">
                                        {{ $transaksi['status'] }}
                                    </span>
                                @else
                                    <span class="inline-block px-2 py-1 rounded text-white text-xs bg-{{ getStatusBadgeColor($transaksi['status']) }}">
                                        {{ $transaksi['status'] }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-center gap-2">
                                    <button onclick="showTransactionDetail({{ $transaksi['id'] }})" class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded shadow text-xs">
                                        Detail
                                    </button>
                                </div>
                                <div class="flex justify-center gap-2">
                                    @if($transaksi['status'] == 'Menunggu Pembayaran')
                                    <a href="{{ route('pengepul.transaksi.payment', $transaksi['id']) }}" class="inline-block bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded shadow text-xs">
                                        Bayar
                                    </a>
                                @endif
                                </div>
                            </td>
                        </tr>
                        @php $rowIndex++; @endphp
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-4 text-center text-gray-500">Tidak ada data transaksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">
                {{ $transaksis->links() }}
            </div>
        </div>
    </section>
</main>

<div id="transactionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="bg-[#FFDF64] px-6 py-4 rounded-t-lg">
                <h3 class="text-lg font-semibold text-[#877B66]">Detail Transaksi</h3>
                <button onclick="closeModal()" class="float-right -mt-6 text-[#877B66] hover:text-gray-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <div id="modalContent">
                    <div class="flex justify-center items-center h-32">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
async function showTransactionDetail(id) {
    const modal = document.getElementById('transactionModal');
    const modalContent = document.getElementById('modalContent');

    modal.classList.remove('hidden');

    modalContent.innerHTML = `
        <div class="flex justify-center items-center h-32">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
        </div>
    `;

    try {
        const response = await fetch(`{{ url('p/riwayat-transaksi') }}/${id}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const data = await response.json();

        modalContent.innerHTML = `
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">ID Transaksi</label>
                        <p class="text-sm text-gray-900">${data.id}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Username</label>
                        <p class="text-sm text-gray-900">${data.username}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Stok</label>
                        <p class="text-sm text-gray-900">${data.nama_stok}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kuantitas</label>
                        <p class="text-sm text-gray-900">${data.kuantitas}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Total Transaksi</label>
                        <p class="text-sm text-gray-900">Rp ${new Intl.NumberFormat('id-ID').format(data.total_transaksi)}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                        <p class="text-sm text-gray-900">${data.metode_pembayaran}</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal Transaksi</label>
                    <p class="text-sm text-gray-900">${data.tanggal_transaksi}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Status Saat Ini</label>
                    <span class="inline-block px-2 py-1 rounded text-white text-xs bg-${getStatusColor(data.status)}">
                        ${data.status}
                    </span>
                </div>
            </div>
        `;

    } catch (error) {
        console.error('Error:', error);
        modalContent.innerHTML = `
            <div class="text-center text-red-500">
                <p>Terjadi kesalahan saat memuat data.</p>
                <button onclick="closeModal()" class="mt-2 px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                    Tutup
                </button>
            </div>
        `;
    }
}

function closeModal() {
    document.getElementById('transactionModal').classList.add('hidden');
}

function getStatusColor(status) {
    switch (status) {
        case 'Menunggu Pembayaran':
            return 'yellow-500';
        case 'Pembayaran Valid':
            return 'blue-400';
        case 'Pembayaran Lunas':
            return 'lime-500';
        case 'Dikemas':
            return 'blue-600';
        case 'Dikirim':
            return 'gray-500';
        case 'Selesai':
            return 'green-500';
        default:
            return 'gray-400';
    }
}

function showSuccessMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'bg-green-500 text-white px-4 py-2 rounded-lg shadow mb-4';
    alertDiv.textContent = message;

    const section = document.querySelector('section');
    section.insertBefore(alertDiv, section.firstChild);

    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}

function showErrorMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'bg-red-500 text-white px-4 py-2 rounded-lg shadow mb-4';
    alertDiv.textContent = message;

    const section = document.querySelector('section');
    section.insertBefore(alertDiv, section.firstChild);

    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}

document.getElementById('transactionModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>

@endsection
