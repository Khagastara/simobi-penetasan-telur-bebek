@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Ubah Stok Distribusi</h4>
                </div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('owner.stok.update', $stok->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nama_stok" class="form-label">Nama Stok <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_stok" name="nama_stok" value="{{ old('nama_stok', $stok->nama_stok) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="gambar_stok" class="form-label">Gambar Stok</label>
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $stok->gambar_stok) }}"
                                    alt="{{ $stok->nama_stok }}"
                                    class="img-thumbnail"
                                    style="max-height: 150px;">
                            </div>
                            <input type="file" class="form-control" id="gambar_stok" name="gambar_stok">
                            <small class="text-muted">Format: JPG, JPEG, PNG, GIF. Maksimal ukuran: 2MB. Biarkan kosong jika tidak ingin mengubah gambar.</small>
                        </div>

                        <div class="mb-3">
                            <label for="jumlah_stok" class="form-label">Jumlah Stok <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="jumlah_stok" name="jumlah_stok" value="{{ old('jumlah_stok', $stok->jumlah_stok) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="harga_stok" class="form-label">Harga Stok <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="harga_stok" name="harga_stok" value="{{ old('harga_stok', $stok->harga_stok) }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi_stok" class="form-label">Deskripsi Stok</label>
                            <textarea class="form-control" id="deskripsi_stok" name="deskripsi_stok" rows="4">{{ old('deskripsi_stok', $stok->deskripsi_stok) }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('owner.stok.show', $stok->id) }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
