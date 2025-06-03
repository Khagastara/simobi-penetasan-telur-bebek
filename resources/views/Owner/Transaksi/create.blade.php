@extends('layouts.owner')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Buat Transaksi</span>
                    <a href="{{ route('stok.pengepul.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                </div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <img src="{{ asset('storage/' . $stokDistribusi->gambar) }}" class="img-fluid" alt="{{ $stokDistribusi->nama_stok }}">
                                        </div>
                                        <div class="col-md-8">
                                            <h5>{{ $stokDistribusi->nama_stok }}</h5>
                                            <p>{{ $stokDistribusi->deskripsi }}</p>
                                            <p><strong>Harga:</strong> Rp {{ number_format($stokDistribusi->harga_stok, 0, ',', '.') }}</p>
                                            <p><strong>Stok tersedia:</strong> {{ $stokDistribusi->jumlah_stok }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('transaksi.pengepul.store', $stokDistribusi->id) }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <label for="kuantitas" class="col-md-4 col-form-label text-md-right">Kuantitas</label>
                            <div class="col-md-6">
                                <input id="kuantitas" type="number" class="form-control @error('kuantitas') is-invalid @enderror"
                                    name="kuantitas" value="{{ old('kuantitas', 1) }}" required min="1" max="{{ $stokDistribusi->jumlah_stok }}">

                                @error('kuantitas')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="total" class="col-md-4 col-form-label text-md-right">Total</label>
                            <div class="col-md-6">
                                <input id="total" type="text" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="metode_pembayaran" class="col-md-4 col-form-label text-md-right">Metode Pembayaran</label>
                            <div class="col-md-6">
                                <select id="metode_pembayaran" class="form-control @error('metode_pembayaran') is-invalid @enderror"
                                        name="metode_pembayaran" required>
                                    <option value="">Pilih Metode Pembayaran</option>
                                    @foreach($metodePembayaran as $metode)
                                        <option value="{{ $metode->id }}">{{ $metode->nama_metode }}</option>
                                    @endforeach
                                </select>

                                @error('metode_pembayaran')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Buat Pesanan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        const hargaStok = {{ $stokDistribusi->harga_stok }};

        function hitungTotal() {
            const kuantitas = $('#kuantitas').val();
            const total = hargaStok * kuantitas;
            $('#total').val('Rp ' + total.toLocaleString('id-ID'));
        }

        hitungTotal();

        $('#kuantitas').on('input', function() {
            hitungTotal();
        });
    });
</script>
@endsection
