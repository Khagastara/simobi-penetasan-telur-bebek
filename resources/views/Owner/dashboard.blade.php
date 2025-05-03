@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Dashboard Owner</h1>
        <p>Welcome, {{ Auth::user()->owner->nama }}!</p>
        <a href="{{ route('owner.profil.show') }}" class="btn btn-info">Profil</a>
        <a href="{{ route('owner.penjadwalan.index') }}" class="btn btn-primary">Jadwal</a>
        <a href="{{ route('owner.transaksi.index') }}" class="btn btn-info">Riwayat Transaksi</a>
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>
@endsection
