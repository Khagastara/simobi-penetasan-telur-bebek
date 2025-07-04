<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiMOBI Owner</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>{{ config('app.name', 'SIMOBI') }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap">
    <link rel="icon" href="{{ asset('images/icons/iconweb.png') }}" type="image/png" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}" defer></script>
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="font-['Poppins'] bg-[#D4E6B5] min-h-screen flex">

<aside class="sidebar w-64 min-h-screen p-6 text-black shadow-lg flex flex-col justify-between sticky top-0 h-screen" style="background-color: #AFC97E;">
    <div>
        <h2 class="text-xl font-bold mb-2 text-[#5B5447] flex items-center">
            <img src="{{ asset('images/icons/logo bebek.png') }}" alt="SiMOBI Icon" class="h-8 w-8 mr-2">
            SiMOBI Owner
        </h2>
        <div class="h-1 bg-[#E2D686] rounded-full w-4/4 mb-4"></div>
        <nav class="space-y-3">
            <a href="{{ route('owner.dashboard') }}" class="block px-4 py-2 rounded hover:text-black {{ request()->routeIs('owner.dashboard') ? 'active-menu' : '' }}">
                <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
            </a>
            <a href="{{ route('owner.penjadwalan.index') }}" class="block px-4 py-2 rounded hover:text-black {{ request()->routeIs('owner.penjadwalan*') ? 'active-menu' : '' }}">
                <i class="fas fa-egg mr-2"></i> Penjadwalan
            </a>
            <a href="{{ route('owner.stok.index') }}" class="block px-4 py-2 rounded hover:text-black {{ request()->routeIs('owner.stok*') ? 'active-menu' : '' }}">
                <i class="fas fa-warehouse mr-2"></i> Stok Distribusi
            </a>
            <a href="{{ route('owner.transaksi.index') }}" class="block px-4 py-2 rounded hover:text-black {{ request()->routeIs('owner.transaksi*') ? 'active-menu' : '' }}">
                <i class="fas fa-exchange-alt mr-2"></i> Transaksi
            </a>
            <a href="{{ route('owner.keuangan.index') }}" class="block px-4 py-2 rounded hover:text-black {{ request()->routeIs('owner.keuangan*') ? 'active-menu' : '' }}">
                <i class="fas fa-money-bill-wave mr-2"></i> Keuangan
            </a>
            <a href="{{ route('owner.profil.show') }}" class="block px-4 py-2 rounded hover:text-black {{ request()->routeIs('owner.profil*') ? 'active-menu' : '' }}">
                <i class="fas fa-user-circle mr-2"></i> Profil
            </a>
        </nav>
    </div>
</aside>
    <main class="flex-1 flex flex-col">
        <header class="topbar p-6 shadow-md" style="background-color: #FFDF64;">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-[#877B66]">SiMOBI</h1>
                    <p class="text-sm text-gray-700">Sistem Informasi Manajemen Operasional Penetasan Telur Bebek Terintegrasi - <span class="font-medium">Owner</span></p>
                </div>
                <div class="text-right text-gray-800">
                    <p class="font-semibold">Halo, <span class="italic">{{ Auth::user()->owner->nama }}</span></p>
                </div>
            </div>
        </header>
        <div class="p-8">
            @yield('content')
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
    .sidebar a {
        position: relative;
        display: flex;
        align-items: center;
        padding-left: 1rem;
        padding-right: 1rem;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
        color: black;
        text-decoration: none;
    }

    .sidebar a::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 0;
        background-color: #FFDF64;
        border-top-left-radius: 0.75rem;
        border-bottom-left-radius: 0.75rem;
        transition: width 0.3s ease;
    }

    .sidebar a:hover,
    .sidebar a.active-menu {
        background-color: #E2D686;
        color: #000;
        font-weight: 500;

        border-top-right-radius: 0.75rem;
        border-bottom-right-radius: 0.75rem;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    .sidebar a:hover::before,
    .sidebar a.active-menu::before {
        width: 6px;
    }
</style>

</body>
</html>
