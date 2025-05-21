@extends('layouts.pengepul')

@section('content')
    <div class="container">
        <h1>Profil Pengepul</h1>
        <p><strong>Nama:</strong> {{ $pengepul->nama }}</p>
        <p><strong>No. HP:</strong> {{ $pengepul->no_hp }}</p>
        <p><strong>Email:</strong> {{ $pengepul->akun->email }}</p>
        <p><strong>Username:</strong> {{ $pengepul->akun->username }}</p>
        <a href="{{ route('pengepul.profil.edit') }}" class="btn btn-warning">Ubah Data</a>
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
