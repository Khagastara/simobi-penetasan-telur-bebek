@extends('layouts.app')

@section('content')
<main class="flex-1 flex flex-col font-['Poppins'] bg-[#D4E6B5] min-h-screen">
    <!-- Top Banner -->
    <header class="topbar p-6 shadow-md">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-[#877B66]">Detail Transaksi</h1>
                <p class="text-sm text-gray-700">Informasi lengkap transaksi #{{ $transaksiDetail['id'] }}</p>
            </div>
            <div class="text-right text-gray-800">
                <p class="font-semibold">Halo, <span class="italic">{{ Auth::user()->owner->nama }}</span></p>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <section class="p-8 max-w-4xl mx-auto">
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

        @if (session('error'))
            <div class="bg-red-500 text-white px-4 py-2 rounded-lg shadow mb-4">
                {{ session('error') }}
            </div>
            <script>
                setTimeout(() => {
                    document.querySelector('.bg-red-500').remove();
                }, 3000);
            </script>
        @endif

        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-[#877B66]">ID Transaksi: <span class="font-normal">#{{ $transaksiDetail['id'] }}</span></h3>
            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                @if($transaksiDetail['status'] === 'Menunggu Pembayaran') bg-yellow-300 text-yellow-800
                @elseif($transaksiDetail['status'] === 'Pembayaran Valid') bg-blue-300 text-blue-800
                @elseif($transaksiDetail['status'] === 'Packing') bg-indigo-300 text-indigo-800
                @elseif($transaksiDetail['status'] === 'Pengiriman') bg-gray-300 text-gray-800
                @elseif($transaksiDetail['status'] === 'Selesai') bg-green-300 text-green-800
                @else bg-gray-300 text-gray-800
                @endif
            ">
                {{ $transaksiDetail['status'] }}
            </span>
        </div>

        <!-- Dropdown Ubah Status -->
        <div class="mb-8">
            <label for="statusDropdown" class="block mb-2 font-semibold text-[#877B66]">Ubah Status</label>
            <div class="relative inline-block text-left">
                <button id="statusDropdown" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-yellow-400 text-white hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-300" aria-expanded="true" aria-haspopup="true">
                    Ubah Status
                    <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <ul id="statusDropdownMenu" class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                    @foreach($statusOptions as $status)
                        <li>
                            <form action="{{ route('transaksi.update-status', $transaksiDetail['id']) }}" method="POST" class="m-0">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="{{ $status }}">
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ $status }}
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Informasi Pelanggan dan Pembayaran -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div>
                <h6 class="text-[#877B66] font-semibold mb-2">Informasi Pelanggan</h6>
                <p><strong>Username:</strong> {{ $transaksiDetail['username'] }}</p>
            </div>
            <div>
                <h6 class="text-[#877B66] font-semibold mb-2">Informasi Pembayaran</h6>
                <p><strong>Metode Pembayaran:</strong> {{ $transaksiDetail['metode_pembayaran'] }}</p>
                <p><strong>Tanggal Transaksi:</strong> {{ $transaksiDetail['tanggal_transaksi'] }}</p>
            </div>
        </div>

        <!-- Tabel Produk -->
        <div class="overflow-x-auto bg-white p-6 rounded-xl shadow">
            <table class="min-w-full divide-y divide-gray-300 text-sm text-left text-gray-700">
                <thead class="bg-[#FFDF64] text-[#877B66]">
                    <tr>
                        <th class="px-4 py-2 font-semibold">Produk</th>
                        <th class="px-4 py-2 font-semibold">Kuantitas</th>
                        <th class="px-4 py-2 font-semibold text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr>
                        <td class="px-4 py-3">{{ $transaksiDetail['nama_stok'] }}</td>
                        <td class="px-4 py-3">{{ $transaksiDetail['kuantitas'] }}</td>
                        <td class="px-4 py-3 text-right">Rp {{ number_format($transaksiDetail['total_transaksi'], 0, ',', '.') }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" class="px-4 py-3 text-right font-semibold">Total Pembayaran:</th>
                        <th class="px-4 py-3 text-right font-semibold">Rp {{ number_format($transaksiDetail['total_transaksi'], 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Tombol Kembali -->
        <div class="mt-6">
            <a href="{{ route('owner.transaksi.index') }}" class="inline-block bg-gray-400 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-500 transition">Kembali</a>
        </div>
    </section>
</main>

@push('scripts')
<script>
    // Toggle dropdown menu
    const btn = document.getElementById('statusDropdown');
    const menu = document.getElementById('statusDropdownMenu');

    btn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
    });

    // Close dropdown if clicked outside
    window.addEventListener('click', function(e) {
        if (!btn.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });
</script>
@endpush
@endsection
