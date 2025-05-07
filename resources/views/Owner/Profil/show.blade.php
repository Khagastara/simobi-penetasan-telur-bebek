@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Profil Owner</h1>
        <p><strong>Nama:</strong> {{ $owner->nama }}</p>
        <p><strong>No. HP:</strong> {{ $owner->no_hp }}</p>
        <p><strong>Email:</strong> {{ $owner->akun->email }}</p>
        <p><strong>Username:</strong> {{ $owner->akun->username }}</p>
        <a href="{{ route('owner.profil.edit') }}" class="btn btn-warning">Ubah Data</a>
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-danger" onclick="confirmLogout()">Logout</button>
        </form>
    </div>

    <script>
        function confirmLogout() {
            if (confirm('Apakah Anda yakin melakukan logout?')) {
                document.getElementById('logout-form').submit();
            }
        }
    </script>
@endsection
