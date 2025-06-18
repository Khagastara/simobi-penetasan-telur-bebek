@extends('layouts.pengepul')

@section('content')
<section class="p-8">
    <div class="bg-white p-6 rounded-xl shadow max-w-3xl mx-auto space-y-4">
        <h1 class="text-2xl font-bold text-[#877B66]">Profil Pengepul</h1>
        <p><strong>Nama:</strong> {{ $pengepul->nama }}</p>
        <p><strong>No. HP:</strong> {{ $pengepul->no_hp }}</p>
        <p><strong>Email:</strong> {{ $pengepul->akun->email }}</p>
        <p><strong>Username:</strong> {{ $pengepul->akun->username }}</p>

        <div class="flex gap-4 mt-6">
            <a href="{{ route('pengepul.profil.edit') }}" class="bg-[#E2D686] hover:bg-[#FFDF64] text-[#877B66] font-semibold px-4 py-2 rounded-lg shadow transition">
                Ubah Data
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="button" onclick="confirmLogout()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg shadow transition">
                    Logout
                </button>
            </form>
        </div>
    </div>
</section>

<script>
    function confirmLogout() {
        if (confirm('Apakah Anda yakin ingin logout?')) {
            document.getElementById('logout-form').submit();
        }
    }
</script>
@endsection
