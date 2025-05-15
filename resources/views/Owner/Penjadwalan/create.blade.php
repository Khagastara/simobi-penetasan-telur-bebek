@extends('layouts.app')

@section('content')
    <h1>Tambah Jadwal Pembiakan</h1>
    <form action="{{ route('owner.penjadwalan.store') }}" method="POST">
        @csrf
        <div>
            <label for="tgl_penjadwalan">Tanggal Penjadwalan:</label>
            <input type="date" name="tgl_penjadwalan" required>
        </div>
        <div id="detail-container">
            <h3>Detail Kegiatan</h3>
            <div class="detail-item">
                <label for="waktu_kegiatan">Waktu Kegiatan:</label>
                <input type="time" name="detail_penjadwalan[0][waktu_kegiatan]" required>
                <label for="keterangan">Keterangan:</label>
                <input type="text" name="detail_penjadwalan[0][keterangan]" required>
                <label for="id_status_kegiatan">Status Kegiatan:</label>
                <select name="detail_penjadwalan[0][id_status_kegiatan]" class="form-control" required>
                    @foreach($statusKegiatan as $status)
                        <option value="{{ $status->id }}">{{ $status->nama_status_kgtn }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <button type="button" id="add-detail">Tambah Detail Kegiatan</button>
        <button type="submit">Simpan Jadwal</button>
    </form>

    <script>
        document.getElementById('add-detail').addEventListener('click', function() {
            const container = document.getElementById('detail-container');
            const index = container.getElementsByClassName('detail-item').length;
            const newDetail = document.createElement('div');
            newDetail.classList.add('detail-item');
            newDetail.innerHTML = `
                <label for="waktu_kegiatan">Waktu Kegiatan:</label>
                <input type="time" name="detail_penjadwalan[${index}][waktu_kegiatan]" required>
                <label for="keterangan">Keterangan:</label>
                <input type="text" name="detail_penjadwalan[${index}][keterangan]" required>
                <label for="id_status_kegiatan">Status Kegiatan:</label>
                <select name="detail_penjadwalan[${index}][id_status_kegiatan]" required>
                    @foreach($statusKegiatan as $status)
                        <option value="{{ $status->id }}">{{ $status->nama_status_kegiatan }}</option>
                    @endforeach
                </select>
            `;
            container.appendChild(newDetail);
        });
    </script>
@endsection
