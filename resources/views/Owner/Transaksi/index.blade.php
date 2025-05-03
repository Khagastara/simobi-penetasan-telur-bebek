@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Riwayat Transaksi</h1>

        <table class="table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>ID Transaksi</th>
                    <th>Nama Stok</th>
                    <th>Kuantitas</th>
                    <th>Sub Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksis as $transaksi)
                    @foreach($transaksi->detailTransaksi as $detail)
                        <tr>
                            <td>
                                <a href="{{ route('owner.transaksi.show', $transaksi->id) }}">
                                    {{ $transaksi->pengepul->akun->username }}
                                </a>
                            </td>
                            <td>{{ $transaksi->id }}</td>
                            <td>{{ $detail->stokDistribusi->nama_stok }}</td>
                            <td>{{ $detail->kuantitas }}</td>
                            <td>{{ $detail->sub_total }}</td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada transaksi yang ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
