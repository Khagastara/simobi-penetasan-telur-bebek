@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Jadwal Pembiakan</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('owner.penjadwalan.create') }}" class="btn btn-primary mb-3">Tambah Jadwal</a>

        <table class="table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Detail Kegiatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penjadwalanKegiatans as $penjadwalan)
                    <tr>
                        <td>{{ $penjadwalan->tgl_penjadwalan->format('d-m-Y') }}</td>
                        <td>
                            @foreach($penjadwalan->detailPenjadwalan as $detail)
                                <div>{{ $detail->waktu_kegiatan }} - {{ $detail->keterangan }}</div>
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('owner.penjadwalan.edit', $penjadwalan->id) }}" class="btn btn-warning">Ubah</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
