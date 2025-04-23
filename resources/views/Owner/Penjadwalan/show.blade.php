@extends('layouts.owner')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5>Detail Jadwal Pembiakan</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>Tanggal:</strong>
                    <p>{{ $detailPenjadwalan->penjadwalanKegiatan->tgl_penjadwalan->format('d F Y') }}</p>
                </div>
                <div class="col-md-4">
                    <strong>Waktu:</strong>
                    <p>{{ \Carbon\Carbon::parse($detailPenjadwalan->waktu_kegiatan)->format('H:i') }}</p>
                </div>
                <div class="col-md-4">
                    <strong>Status:</strong>
                    <p>
                        <span class="badge bg-{{ $detailPenjadwalan->statusKegiatan->class }}">
                            {{ $detailPenjadwalan->statusKegiatan->nama_status }}
                        </span>
                    </p>
                </div>
            </div>

            <div class="mb-3">
                <strong>Keterangan:</strong>
                <p>{{ $detailPenjadwalan->keterangan }}</p>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('owner.penjadwalan.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <div>
                    <a href="{{ route('owner.penjadwalan.edit', $detailPenjadwalan->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Ubah
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
