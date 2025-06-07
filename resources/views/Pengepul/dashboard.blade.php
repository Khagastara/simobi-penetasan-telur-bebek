@extends('layouts.pengepul')

@section('content')
    <div class="container">
        {{-- <a href="{{ route('transaksi.index') }}" class="btn btn-primary">Manage Transactions</a> --}}
        <a href="{{ route('pengepul.profil.show') }}" class="btn btn-info">Profil</a>
        <a href="{{ route('pengepul.stok.index') }}" class="btn btn-info">stok</a>
        <a href="{{ route('pengepul.transaksi.index') }}" class="btn btn-info">transaksi</a>
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>

    </div>
@endsection
