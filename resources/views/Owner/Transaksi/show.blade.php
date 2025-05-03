@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Detail Akun Pengepul</h1>

        <p><strong>Nama:</strong> {{ $pengepul->nama }}</p>
        <p><strong>No. HP:</strong> {{ $pengepul->no_hp }}</p>
        <p><strong>Email:</strong> {{ $pengepul->akun->email }}</p>
        <p><strong>Username:</strong> {{ $pengepul->akun->username }}</p>

        <a href="{{ route('owner.transaksi.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
@endsection
