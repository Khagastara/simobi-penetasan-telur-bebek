@extends('layouts.owner')

@section('content')
@php
    function getStatusBadgeColor($status) {
        return match($status) {
            'Menunggu Pembayaran' => 'warning',
            'Pembayaran Valid' => 'primary',
            'Packing' => 'info',
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
                    <tr>
                        <th class="px-4 py-2 font-semibold">No</th>
                        <th class="px-4 py-2 font-semibold">Tanggal Transaksi</th>
                        <th class="px-4 py-2 font-semibold">Username</th>
                        <th class="px-4 py-2 font-semibold">Nama Stok</th>
                        <th class="px-4 py-2 font-semibold">Kuantitas</th>
                        <th class="px-4 py-2 font-semibold">Total Transaksi</th>
                        <th class="px-4 py-2 font-semibold">Status</th>
                        <th class="px-4 py-2 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @php $rowIndex = 0; @endphp
                    @forelse ($transaksis as $index => $transaksi)
                        <tr class="{{ $rowIndex % 2 == 0 ? 'bg-white' : 'bg-gray-100' }}">
                            <td class="px-4 py-3">{{ $index + 1 }}</td>
                            <td class="px-4 py-3">{{ $transaksi['tgl_transaksi'] }}</td>
                            <td class="px-4 py-3">{{ $transaksi['username'] }}</td>
                            <td class="px-4 py-3">{{ $transaksi['nama_stok'] }}</td>
                            <td class="px-4 py-3">{{ $transaksi['kuantitas'] }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($transaksi['total_transaksi'], 0, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-block px-2 py-1 rounded text-white text-xs bg-{{ getStatusBadgeTailwindColor($transaksi['status']) }}">
                                    {{ $transaksi['status'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <button onclick="showTransactionDetail({{ $transaksi['id'] }})" class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded shadow text-xs">
                                    Detail
                                </button>
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
                {{ $transaksis->links() }} <!-- Add this line for pagination links -->
            </div>
        </div>
    </section>
</main>

<!-- Modal -->
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
                    <!-- Content will be loaded here -->
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
        const response = await fetch(`{{ url('o/riwayat-transaksi') }}/${id}`, {
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

                <hr class="my-4">

                <form id="updateStatusForm" onsubmit="updateStatus(event, ${data.id})">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Update Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            ${data.statusOptions.map(option =>
                                `<option value="${option}" ${option === data.status ? 'selected' : ''}>${option}</option>`
                            ).join('')}
                        </select>
                    </div>

                    <div class="flex justify-end space-x-3 mt-4">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Update Status
                        </button>
                    </div>
                </form>
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

async function updateStatus(event, id) {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);

    formData.append('_method', 'PUT');

    try {
        const response = await fetch(`{{ url('o/riwayat-transaksi') }}/${id}/update-status`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData
        });

        const result = await response.json();

        if (response.ok && result.success) {
            showSuccessMessage('Status berhasil diubah');

            closeModal();

            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            const message = result.message || 'Terjadi kesalahan saat mengupdate status';
            showErrorMessage(message);
        }

    } catch (error) {
        console.error('Error:', error);
        showErrorMessage('Terjadi kesalahan saat mengupdate status');
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

@php
function getStatusBadgeTailwindColor($status) {
    switch ($status) {
        case 'Menunggu Pembayaran':
            return 'yellow-500';
        case 'Pembayaran Valid':
            return 'blue-400';
        case 'Packing':
            return 'blue-600';
        case 'Pengiriman':
            return 'gray-500';
        case 'Selesai':
            return 'green-500';
        default:
            return 'gray-400';
    }
}
@endphp
