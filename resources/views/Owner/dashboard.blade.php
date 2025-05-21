{{-- @extends('layouts.app') --}}
@extends('layouts.owner')

@section('content')

            <!-- Dashboard Content -->
            <div class="p-8">
                <h1 class="text-2xl font-bold text-[#877B66] mb-4">Dashboard Owner</h1>
                <p class="mb-6">Welcome, {{ Auth::user()->owner->nama }}!</p>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <a href="{{ route('owner.profil.show') }}" class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition border-l-4 border-[#AFC97E]">
                        <h3 class="text-lg font-bold text-[#877B66] mb-2">Profil</h3>
                        <p class="text-sm text-gray-600">Kelola informasi profil owner</p>
                    </a>
                    <a href="{{ route('owner.penjadwalan.index') }}" class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition border-l-4 border-[#AFC97E]">
                        <h3 class="text-lg font-bold text-[#877B66] mb-2">Jadwal</h3>
                        <p class="text-sm text-gray-600">Kelola jadwal penetasan telur</p>
                    </a>
                    <a href="{{ route('owner.transaksi.index') }}" class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition border-l-4 border-[#AFC97E]">
                        <h3 class="text-lg font-bold text-[#877B66] mb-2">Riwayat Transaksi</h3>
                        <p class="text-sm text-gray-600">Lihat riwayat transaksi</p>
                    </a>
                </div>
            </div>
        </main>
    @if(session('success'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg">
            {{ session('success') }}
        </div>
        <script>
            setTimeout(() => {
                document.querySelector('.fixed').remove();
            }, 3000);
        </script>
    @endif

    <style>
        .sidebar {
            background-color: #AFC97E;
        }
        .sidebar a:hover {
            background-color: #E2D686;
            color: #000;
        }
        .topbar {
            background-color: #FFDF64;
        }
        .active-menu {
            background-color: #E2D686;
            color: #000;
            font-weight: 500;
        }
    </style>
@endsection
