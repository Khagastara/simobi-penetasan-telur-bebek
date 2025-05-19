@extends('layouts.owner')

@section('content')
    <main class="flex-1 flex flex-col font-['Poppins'] bg-[#D4E6B5] min-h-screen">
        <header class="topbar p-6 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-[#877B66]">Profil Owner</h1>
                    <p class="text-sm text-gray-700">Informasi akun Anda</p>
                </div>
                <div class="text-right text-gray-800">
                    <p class="font-semibold">Halo, <span class="italic">{{ $owner->nama }}</span></p>
                </div>
            </div>
        </header>

        <section class="p-8">
            <div class="bg-white p-6 rounded-xl shadow max-w-3xl mx-auto space-y-4">
                <p><strong>Nama:</strong> {{ $owner->nama }}</p>
                <p><strong>No. HP:</strong> {{ $owner->no_hp }}</p>
                <p><strong>Email:</strong> {{ $owner->akun->email }}</p>
                <p><strong>Username:</strong> {{ $owner->akun->username }}</p>

                <div class="flex gap-4 mt-6">
                    <a href="{{ route('owner.profil.edit') }}" class="bg-[#E2D686] hover:bg-[#FFDF64] text-[#877B66] font-semibold px-4 py-2 rounded-lg shadow transition">
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
    </main>

    <script>
        function confirmLogout() {
            if (confirm('Apakah Anda yakin ingin logout?')) {
                document.getElementById('logout-form').submit();
            }
        }
    </script>
@endsection
