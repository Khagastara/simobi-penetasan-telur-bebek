@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Dashboard Owner</h1>
        <p>Welcome, {{ Auth::user()->owner->nama }}!</p>
        <a href="{{ route('owner.profil.show') }}" class="btn btn-info">Profil</a>
        <a href="{{ route('owner.penjadwalan.index') }}" class="btn btn-primary">Jadwal</a>
        <a href="{{ route('owner.transaksi.index') }}" class="btn btn-info">Riwayat Transaksi</a>
        <a href="{{ route('owner.stok.index') }}" class="btn btn-info">Stok Distribusi</a>
        <a href="{{ route('owner.transaksi.index') }}" class="btn btn-info">Riwayat Transaksi</a>
    </div>
@endsection
