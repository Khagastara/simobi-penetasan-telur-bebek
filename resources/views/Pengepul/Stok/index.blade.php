@extends('layouts.pengepul')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Stok Distribusi</h4>
                </div>

                <div class="card-body">
                    <div class="row">
                        @forelse ($stokDistribusi as $stok)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    <img src="{{ asset($stok->gambar_stok) }}"
                                        class="card-img-top"
                                        alt="{{ $stok->nama_stok }}"
                                        style="height: 200px; object-fit: cover;">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $stok->nama_stok }}</h5>
                                        <p class="card-text text-primary fw-bold">Rp {{ number_format($stok->harga_stok, 0, ',', '.') }}</p>
                                        <a href="{{ route('pengepul.stok.show', $stok->id) }}" class="btn btn-info">Detail</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info">
                                    Tidak ada data stok distribusi yang tersedia saat ini.
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
