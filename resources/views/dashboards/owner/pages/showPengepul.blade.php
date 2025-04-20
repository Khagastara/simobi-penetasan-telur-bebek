@extends('layouts.dashboard')

@section('content')
<div class="container">
    <h2>Detail Akun Pengepul</h2>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Informasi Pengepul</h5>
            <p><strong>Nama:</strong> {{ $pengepul->nama }}</p>
            <p><strong>No. HP:</strong> {{ $pengepul->no_hp }}</p>
            <p><strong>Email:</strong> {{ $pengepul->akun->email }}</p>
            <p><strong>Username:</strong> {{ $pengepul->akun->username }}</p>

            <a href="{{ route('dashboard.owner.pengepul.index') }}"
               class="btn btn-secondary">
                Kembali ke Riwayat
            </a>
        </div>
    </div>
</div>
@endsection
