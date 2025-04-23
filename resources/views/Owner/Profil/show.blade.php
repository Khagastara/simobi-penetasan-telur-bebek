@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Profil Owner</h1>
        <p><strong>Nama:</strong> {{ $owner->nama }}</p>
        <p><strong>No. HP:</strong> {{ $owner->no_hp }}</p>
        <p><strong>Email:</strong> {{ $owner->akun->email }}</p>
        <p><strong>Username:</strong> {{ $owner->akun->username }}</p>
        <a href="{{ route('owner.profil.edit') }}" class="btn btn-warning">Ubah Data</a>
    </div>
@endsection
