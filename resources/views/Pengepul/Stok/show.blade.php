@extends('layouts.pengepul

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Detail Stok Distribusi</h4>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="text-center mb-4">
                                <img src="{{ asset('storage/' . $stok->gambar_stok) }}"
                                    alt="{{ $stok->nama_stok }}"
                                    class="img-fluid rounded"
                                    style="max-height: 300px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4>{{ $stok->nama_stok }}</h4>
                            <hr>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Harga</th>
                                    <td>: Rp {{ number_format($stok->harga_stok, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Jumlah Stok</th>
                                    <td>: {{ $stok->jumlah_stok }}</td>
                                </tr>
                                <tr>
                                    <th>Deskripsi</th>
                                    <td>: {{ $stok->deskripsi_stok ?: '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('pengepul.stok.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
