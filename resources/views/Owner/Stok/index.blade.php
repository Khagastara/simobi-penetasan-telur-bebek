@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Data Stok Distribusi</h4>
                    <a href="{{ route('owner.stok.create') }}" class="btn btn-primary">Tambah</a>
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
                                    <th>Gambar</th>
                                    <th>Harga</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($stokDistribusi as $index => $stok)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $stok->nama_stok }}</td>
                                        <td>
                                            <img src="{{ asset('storage/' . $stok->gambar_stok) }}"
                                                alt="{{ $stok->nama_stok }}"
                                                class="img-thumbnail"
                                                style="max-height: 100px;">
                                        </td>
                                        <td>Rp {{ number_format($stok->harga_stok, 0, ',', '.') }}</td>
                                        <td>
                                            <a href="{{ route('owner.stok.show', $stok->id) }}" class="btn btn-info btn-sm">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada data stok distribusi</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
