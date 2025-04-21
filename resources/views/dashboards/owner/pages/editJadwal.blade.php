@extends('layouts.dashboard')

@section('content')
<h2>Ubah Jadwal Pembiakan</h2>

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Data ada yang kosong!</strong>
    </div>
@endif

<form action="{{ route('dashboard.penjadwalan.update', $detail->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label>Tanggal:</label>
        <input type="date" name="tgl_penjadwalan"
               value="{{ old('tgl_penjadwalan', $detail->penjadwalan->tgl_penjadwalan) }}"
               class="form-control">
    </div>

    <div class="form-group">
        <label>Waktu:</label>
        <input type="time" name="waktu_kegiatan"
               value="{{ old('waktu_kegiatan', $detail->waktu_kegiatan) }}"
               class="form-control">
    </div>

    <div class="form-group">
        <label>Status:</label>
        <select name="id_status_kegiatan" class="form-control">
            @foreach($statusOptions as $status)
            <option value="{{ $status->id }}"
                {{ $detail->id_status_kegiatan == $status->id ? 'selected' : '' }}>
                {{ $status->nama_status_kgtn }}
            </option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
</form>
@endsection
