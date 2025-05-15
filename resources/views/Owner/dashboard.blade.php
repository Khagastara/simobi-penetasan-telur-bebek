@extends('layouts.app')

@section('content')
    <div class="flex min-h-screen bg-[#D4E6B5] font-['Poppins']">
        <!-- Sidebar -->
        <aside class="sidebar w-64 min-h-screen p-6 text-white shadow-lg">
            <h2 class="text-xl font-bold mb-8 text-white">SiMOBI Owner</h2>
            <nav class="space-y-3">
                <a href="{{ route('owner.dashboard') }}" class="block px-4 py-2 rounded hover:text-black {{ request()->routeIs('owner.dashboard') ? 'active-menu' : '' }}">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
                <a href="{{ route('owner.penjadwalan.index') }}" class="block px-4 py-2 rounded hover:text-black {{ request()->routeIs('owner.penjadwalan*') ? 'active-menu' : '' }}">
                    <i class="fas fa-egg mr-2"></i> Jadwal
                </a>
                <a href="{{ route('owner.stok.index') }}" class="block px-4 py-2 rounded hover:text-black {{ request()->routeIs('owner.transaksi*') ? 'active-menu' : '' }}">
                    <i class="fas fa-exchange-alt mr-2"></i> Stok Distribusi                    
                </a>
                <a href="{{ route('owner.transaksi.index') }}" class="block px-4 py-2 rounded hover:text-black {{ request()->routeIs('owner.transaksi*') ? 'active-menu' : '' }}">
                    <i class="fas fa-exchange-alt mr-2"></i> Riwayat Transaksi
                </a>
                <a href="{{ route('owner.profil.show') }}" class="block px-4 py-2 rounded hover:text-black {{ request()->routeIs('owner.profil*') ? 'active-menu' : '' }}">
                    <i class="fas fa-user-circle mr-2"></i> Profil
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                    class="block px-4 py-2 rounded hover:text-black">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col">
            <!-- Top Banner -->
            <header class="topbar p-6 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold text-[#877B66]">SiMOBI</h1>
                        <p class="text-sm text-gray-700">Sistem Manajemen Operasional Penetasan Telur Bebek - <span class="font-medium">Owner</span></p>
                    </div>
                    <div class="text-right text-gray-800">
                        <p class="font-semibold">Halo, <span class="italic">{{ Auth::user()->owner->nama }}</span></p>
                    </div>
                </div>
            </header>

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
