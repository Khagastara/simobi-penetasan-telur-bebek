@extends('layouts.dashboard')

@section('content')
<div class="container">
    <h2>Riwayat Transaksi Pengepul</h2>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>ID Transaksi</th>
                    <th>Nama Stok</th>
                    <th>Kuantitas</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                @foreach ($transaction->detailTransaksi as $detail)
                <tr>
                    <td>
                        <a href="{{ route('dashboard.owner.pengepul.show', $transaction->pengepul->id) }}">
                            {{ $transaction->pengepul->akun->username }}
                        </a>
                    </td>
                    <td>{{ $transaction->id }}</td>
                    <td>{{ $detail->stokDistribusi->nama_stok }}</td>
                    <td>{{ $detail->kuantitas }}</td>
                    <td>Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('dashboard.owner.pengepul.show', $transaction->pengepul->id) }}"
                           class="btn btn-sm btn-info">
                            Detail
                        </a>
                    </td>
                </tr>
                @endforeach
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $transactions->links() }}
</div>
@endsection
