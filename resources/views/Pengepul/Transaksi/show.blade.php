@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Detail Transaksi #{{ $transaksiDetail['id'] }}</span>
                    <a href="{{ route('transaksi.pengepul.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Informasi Transaksi</h5>
                            <table class="table table-sm">
                                <tr>
                                    <th>ID Transaksi</th>
                                    <td>{{ $transaksiDetail['id'] }}</td>
                                </tr>
                                <tr>
                                    <th>Username</th>
                                    <td>{{ $transaksiDetail['username'] }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Transaksi</th>
                                    <td>{{ $transaksiDetail['tanggal_transaksi'] }}</td>
                                </tr>
                                <tr>
                                    <th>Metode Pembayaran</th>
                                    <td>{{ $transaksiDetail['metode_pembayaran'] }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge badge-{{ getStatusBadgeClass($transaksiDetail['status']) }}">
                                            {{ $transaksiDetail['status'] }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <h5>Detail Produk</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Stok</th>
                                    <th>Kuantitas</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $transaksiDetail['nama_stok'] }}</td>
                                    <td>{{ $transaksiDetail['kuantitas'] }}</td>
                                    <td>Rp {{ number_format($transaksiDetail['total_transaksi'], 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-right">Total Transaksi:</th>
                                    <th>Rp {{ number_format($transaksiDetail['total_transaksi'], 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mt-4">
                        <h5>Petunjuk Pembayaran</h5>
                        @if($transaksiDetail['metode_pembayaran'] == 'Transfer')
                            @if($transaksiDetail['status'] == 'Menunggu Pembayaran')
                                <div class="alert alert-info">
                                    <p>Silakan transfer ke rekening berikut:</p>
                                    <ul>
                                        <li>Bank: Bank BRI</li>
                                        <li>No. Rekening: 123456789</li>
                                        <li>Atas Nama: SIMOBI Penetasan Telur Bebek</li>
                                        <li>Jumlah: Rp {{ number_format($transaksiDetail['total_transaksi'], 0, ',', '.') }}</li>
                                    </ul>
                                    <p>Setelah melakukan pembayaran, harap tunggu konfirmasi dari kami.</p>
                                </div>
                            @elseif($transaksiDetail['status'] == 'Pembayaran Valid')
                                <div class="alert alert-success">
                                    <p>Pembayaran Anda telah dikonfirmasi. Pesanan Anda sedang diproses.</p>
                                </div>
                            @endif
                        @elseif($transaksiDetail['metode_pembayaran'] == 'Tunai')
                            <div class="alert alert-info">
                                <p>Pembayaran tunai akan dilakukan saat pengambilan/pengiriman barang.</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-4">
                        <h5>Status Pesanan</h5>
                        <div class="progress-tracker">
                            <ul class="progress-steps">
                                <li class="progress-step {{ in_array($transaksiDetail['status'], ['Menunggu Pembayaran', 'Pembayaran Valid', 'Packing', 'Pengiriman', 'Selesai']) ? 'is-active' : '' }}">
                                    <div class="progress-marker"></div>
                                    <div class="progress-text">Menunggu Pembayaran</div>
                                </li>
                                <li class="progress-step {{ in_array($transaksiDetail['status'], ['Pembayaran Valid', 'Packing', 'Pengiriman', 'Selesai']) ? 'is-active' : '' }}">
                                    <div class="progress-marker"></div>
                                    <div class="progress-text">Pembayaran Valid</div>
                                </li>
                                <li class="progress-step {{ in_array($transaksiDetail['status'], ['Packing', 'Pengiriman', 'Selesai']) ? 'is-active' : '' }}">
                                    <div class="progress-marker"></div>
                                    <div class="progress-text">Packing</div>
                                </li>
                                <li class="progress-step {{ in_array($transaksiDetail['status'], ['Pengiriman', 'Selesai']) ? 'is-active' : '' }}">
                                    <div class="progress-marker"></div>
                                    <div class="progress-text">Pengiriman</div>
                                </li>
                                <li class="progress-step {{ $transaksiDetail['status'] == 'Selesai' ? 'is-active' : '' }}">
                                    <div class="progress-marker"></div>
                                    <div class="progress-text">Selesai</div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Styling untuk progress tracker */
    .progress-tracker {
        margin: 20px 0;
    }

    .progress-steps {
        display: flex;
        justify-content: space-between;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .progress-step {
        flex: 1;
        position: relative;
        text-align: center;
    }

    .progress-step:not(:last-child):after {
        content: '';
        position: absolute;
        top: 15px;
        left: 50%;
        width: 100%;
        height: 2px;
        background-color: #ddd;
        z-index: 1;
    }

    .progress-step.is-active:not(:last-child):after {
        background-color: #28a745;
    }

    .progress-marker {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #ddd;
        margin: 0 auto 10px;
        position: relative;
        z-index: 2;
    }

    .progress-step.is-active .progress-marker {
        background-color: #28a745;
    }

    .progress-text {
        font-size: 0.8rem;
    }

    .progress-step.is-active .progress-text {
        font-weight: bold;
    }
</style>
@endsection

@php
function getStatusBadgeClass($status) {
    switch($status) {
        case 'Menunggu Pembayaran':
            return 'warning';
        case 'Pembayaran Valid':
            return 'success';
        case 'Packing':
            return 'info';
        case 'Pengiriman':
            return 'primary';
        case 'Selesai':
            return 'success';
        default:
            return 'secondary';
    }
}
@endphp
