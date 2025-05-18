@extends('layouts.owner')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Detail Transaksi</h4>
                    <div class="dropdown">
                        <button class="btn btn-warning dropdown-toggle" type="button" id="statusDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Ubah Status
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="statusDropdown">
                            @foreach($statusOptions as $status)
                                <li>
                                    <form action="{{ route('transaksi.update-status', $transaksiDetail['id']) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="{{ $status }}">
                                        <button type="submit" class="dropdown-item">{{ $status }}</button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">ID Transaksi: #{{ $transaksiDetail['id'] }}</h5>
                            <span class="badge bg-{{ getStatusBadgeColor($transaksiDetail['status']) }} fs-6">
                                {{ $transaksiDetail['status'] }}
                            </span>
                        </div>
                        <hr>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Informasi Pelanggan</h6>
                            <p class="mb-1"><strong>Username:</strong> {{ $transaksiDetail['username'] }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Informasi Pembayaran</h6>
                            <p class="mb-1"><strong>Metode Pembayaran:</strong> {{ $transaksiDetail['metode_pembayaran'] }}</p>
                            <p class="mb-1"><strong>Tanggal Transaksi:</strong> {{ $transaksiDetail['tanggal_transaksi'] }}</p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th>Kuantitas</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $transaksiDetail['nama_stok'] }}</td>
                                    <td>{{ $transaksiDetail['kuantitas'] }}</td>
                                    <td class="text-end">Rp {{ number_format($transaksiDetail['total_transaksi'], 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-end">Total Pembayaran:</th>
                                    <th class="text-end">Rp {{ number_format($transaksiDetail['total_transaksi'], 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('owner.transaksi.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@php
function getStatusBadgeColor($status) {
    switch ($status) {
        case 'Menunggu Pembayaran':
            return 'warning';
        case 'Pembayaran Valid':
            return 'info';
        case 'Packing':
            return 'primary';
        case 'Pengiriman':
            return 'secondary';
        case 'Selesai':
            return 'success';
        default:
            return 'secondary';
    }
}
@endphp
