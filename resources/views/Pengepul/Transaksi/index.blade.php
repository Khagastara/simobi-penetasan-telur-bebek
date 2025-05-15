@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Riwayat Transaksi</h4>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Stok</th>
                                    <th>Kuantitas</th>
                                    <th>Total Transaksi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transaksis as $index => $transaksi)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $transaksi['nama_stok'] }}</td>
                                        <td>{{ $transaksi['kuantitas'] }}</td>
                                        <td>Rp {{ number_format($transaksi['total_transaksi'], 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge bg-{{ getStatusBadgeColor($transaksi['status']) }}">
                                                {{ $transaksi['status'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('pengepul.transaksi.show', $transaksi['id']) }}" class="btn btn-info btn-sm">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data transaksi</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('pengepul.stok.index') }}" class="btn btn-primary">Buat Transaksi Baru</a>
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
