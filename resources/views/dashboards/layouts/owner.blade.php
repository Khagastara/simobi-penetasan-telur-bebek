<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | SiMOBI Owner</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @stack('styles')
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-[#AFC97E] text-white shadow-lg">
            <div class="p-6">
                <h2 class="text-xl font-bold mb-8">
                    <i class="fas fa-egg mr-2"></i> SiMOBI Owner
                </h2>
                <nav class="space-y-2">
                    <!-- Dashboard -->
                    <a href="{{ route('owner.dashboard') }}" class="block px-4 py-2 rounded hover:bg-[#E2D686] hover:text-gray-800 transition flex items-center {{ request()->routeIs('owner.dashboard') ? 'bg-[#E2D686] text-gray-800 font-medium' : '' }}">
                        <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
                    </a>
                    
                    <!-- Pembiakan Telur -->
                    <a href="{{ route('owner.breeding.index') }}" class="block px-4 py-2 rounded hover:bg-[#E2D686] hover:text-gray-800 transition flex items-center {{ request()->routeIs('owner.breeding*') ? 'bg-[#E2D686] text-gray-800 font-medium' : '' }}">
                        <i class="fas fa-egg mr-3"></i> Pembiakan Telur
                    </a>
                    
                    <!-- Stok Distribusi -->
                    <a href="{{ route('owner.stock.index') }}" class="block px-4 py-2 rounded hover:bg-[#E2D686] hover:text-gray-800 transition flex items-center {{ request()->routeIs('owner.stock*') ? 'bg-[#E2D686] text-gray-800 font-medium' : '' }}">
                        <i class="fas fa-boxes mr-3"></i> Stok Distribusi
                    </a>
                    
                    <!-- Transaksi -->
                    <a href="{{ route('owner.transaction.index') }}" class="block px-4 py-2 rounded hover:bg-[#E2D686] hover:text-gray-800 transition flex items-center {{ request()->routeIs('owner.transaction*') ? 'bg-[#E2D686] text-gray-800 font-medium' : '' }}">
                        <i class="fas fa-exchange-alt mr-3"></i> Transaksi
                    </a>
                    
                    <!-- Keuangan -->
                    <a href="{{ route('owner.finance.index') }}" class="block px-4 py-2 rounded hover:bg-[#E2D686] hover:text-gray-800 transition flex items-center {{ request()->routeIs('owner.finance*') ? 'bg-[#E2D686] text-gray-800 font-medium' : '' }}">
                        <i class="fas fa-chart-line mr-3"></i> Keuangan
                    </a>
                    
                    <!-- Profil -->
                    <a href="{{ route('owner.profile') }}" class="block px-4 py-2 rounded hover:bg-[#E2D686] hover:text-gray-800 transition flex items-center {{ request()->routeIs('owner.profile') ? 'bg-[#E2D686] text-gray-800 font-medium' : '' }}">
                        <i class="fas fa-user-circle mr-3"></i> Profil
                    </a>
                    
                    <!-- Logout -->
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                       class="block px-4 py-2 rounded hover:bg-[#E2D686] hover:text-gray-800 transition flex items-center">
                        <i class="fas fa-sign-out-alt mr-3"></i> Logout
                    </a>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col">
            <!-- Topbar -->
            <header class="bg-[#FFDF64] p-4 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-xl font-semibold text-[#877B66]">@yield('page-title', 'Dashboard')</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-[#877B66]">
                            <i class="fas fa-user mr-1"></i> {{ Auth::user()->name }}
                        </span>
                        <a href="{{ route('owner.profile') }}" class="text-[#877B66] hover:text-[#AFC97E]">
                            <i class="fas fa-cog"></i>
                        </a>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="flex-1 p-6">
                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>