{{-- filepath: c:\laragon\www\simobi-penetasan-telur-bebek\resources\views\layouts\owner.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SiMOBI Owner</title>

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            background-color: #AFC97E;
            color: white;
            height: 100vh;
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
</head>
<body class="font-['Poppins'] bg-[#D4E6B5]">
    <div class="flex min-h-screen">
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
                <a href="{{ route('owner.stok.index') }}" class="block px-4 py-2 rounded hover:text-black {{ request()->routeIs('owner.stok*') ? 'active-menu' : '' }}">
                    <i class="fas fa-exchange-alt mr-2"></i> Stok Distribusi
                </a>
                <a href="{{ route('owner.transaksi.index') }}" class="block px-4 py-2 rounded hover:text-black {{ request()->routeIs('owner.transaksi*') ? 'active-menu' : '' }}">
                    <i class="fas fa-exchange-alt mr-2"></i> Riwayat Transaksi
                </a>
                <a href="{{ route('owner.keuangan.index') }}" class="block px-4 py-2 rounded hover:text-black {{ request()->routeIs('owner.keuangan*') ? 'active-menu' : '' }}">
                    <i class="fas fa-user-circle mr-2"></i> Keuangan
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
            @yield('content')
        </main>
    </div>
</body>
</html>
