@extends('layouts.dashboard')

@section('content')
<div class="container">
    <h2>Ubah Profil</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('dashboard.owner.profile.update') }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control" id="nama" name="nama"
                   value="{{ old('nama', $owner->nama) }}" required>
        </div>

        <div class="mb-3">
            <label for="no_hp" class="form-label">No. HP</label>
            <input type="text" class="form-control" id="no_hp" name="no_hp"
                   value="{{ old('no_hp', $owner->no_hp) }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email"
                   value="{{ old('email', $owner->akun->email) }}" required>
        </div>

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username"
                   value="{{ old('username', $owner->akun->username) }}" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password (Biarkan kosong jika tidak ingin mengubah)</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>
@endsection
