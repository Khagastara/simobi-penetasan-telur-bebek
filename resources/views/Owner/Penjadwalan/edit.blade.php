@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Jadwal Pembiakan</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('owner.penjadwalan.update', $penjadwalanKegiatan->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="tgl_penjadwalan">Tanggal Penjadwalan:</label>
                <input type="date" name="tgl_penjadwalan" class="form-control" value="{{ $penjadwalanKegiatan->tgl_penjadwalan->format('Y-m-d') }}" required>
            </div>

            <h3>Detail Kegiatan</h3>
            @foreach($penjadwalanKegiatan->detailPenjadwalan as $detail)
                <div class="detail-item">
                    <input type="hidden" name="detail_penjadwalan[{{ $loop->index }}][id]" value="{{ $detail->id }}">

                    <div class="form-group">
                        <label for="waktu_kegiatan">Waktu Kegiatan:</label>
                        <input type="time" name="detail_penjadwalan[{{ $loop->index }}][waktu_kegiatan]"
                            class="form-control"
                            value="{{ isset($detail->waktu_kegiatan) ? \Carbon\Carbon::createFromFormat('H:i:s', $detail->waktu_kegiatan)->format('H:i') : '' }}"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan:</label>
                        <input type="text" name="detail_penjadwalan[{{ $loop->index }}][keterangan]" class="form-control" value="{{ $detail->keterangan }}" required>
                    </div>
                    <div class="form-group">
                        <label for="id_status_kegiatan">Status Kegiatan:</label>
                        <select name="detail_penjadwalan[{{ $loop->index }}][id_status_kegiatan]" class="form-control" required>
                            @foreach($statusKegiatan as $status)
                                <option value="{{ $status->id }}" {{ $detail->id_status_kegiatan == $status->id ? 'selected' : '' }}>
                                    {{ $status->nama_status_kgtn }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endforeach

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection
