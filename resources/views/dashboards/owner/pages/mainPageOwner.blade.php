<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- CSRF Token -->
  <title>Dashboard Owner - SiMOBI</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #D4E6B5;
    }
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
    .topbar h1 {
      color: #877B66;
    }
    .active-menu {
      background-color: #E2D686;
      color: #000;
      font-weight: 500;
    }
  </style>
</head>
<body class="flex">

  <!-- Sidebar -->
  <aside class="sidebar w-64 min-h-screen p-6 text-white shadow-lg">
    <h2 class="text-xl font-bold mb-8 text-white">SiMOBI Owner</h2>
    <nav class="space-y-3">
      <a href="{{ route('owner.dashboard') }}" class="block px-4 py-2 rounded hover:text-black {{ request()->routeIs('owner.dashboard') ? 'active-menu' : '' }}">
        <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
      </a>
      <a href="{{ route('owner.breeding') }}" class="block px-4 py-2 rounded hover:text-black {{ request()->routeIs('owner.breeding*') ? 'active-menu' : '' }}">
        <i class="fas fa-egg mr-2"></i> Pembiakan Telur
      </a>
      <a href="{{ route('owner.stock') }}" class="block px-4 py-2 rounded hover:text-black {{ request()->routeIs('owner.stock*') ? 'active-menu' : '' }}">
        <i class="fas fa-boxes mr-2"></i> Stok Distribusi
      </a>
      <a href="{{ route('owner.transaction') }}" class="block px-4 py-2 rounded hover:text-black {{ request()->routeIs('owner.transaction*') ? 'active-menu' : '' }}">
        <i class="fas fa-exchange-alt mr-2"></i> Transaksi
      </a>
      <a href="{{ route('owner.finance') }}" class="block px-4 py-2 rounded hover:text-black {{ request()->routeIs('owner.finance*') ? 'active-menu' : '' }}">
        <i class="fas fa-chart-line mr-2"></i> Keuangan
      </a>
      <a href="{{ route('owner.profile') }}" class="block px-4 py-2 rounded hover:text-black {{ request()->routeIs('owner.profile*') ? 'active-menu' : '' }}">
        <i class="fas fa-user-circle mr-2"></i> Profil Akun
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
          <h1 class="text-2xl font-semibold">SiMOBI</h1>
          <p class="text-sm text-gray-700">Sistem Manajemen Operasional Penetasan Telur Bebek - <span class="font-medium">Owner</span></p>
        </div>
        <div class="text-right text-gray-800">
          <p class="font-semibold">Halo, <span class="italic">{{ Auth::user()->name }}</span></p>
          <p class="text-sm">Terakhir login: {{ Auth::user()->last_login_at ? Auth::user()->last_login_at->format('d M Y H:i') : 'Baru' }}</p>
        </div>
      </div>
    </header>

    <!-- Menu Cards -->
    <section class="p-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <a href="{{ route('owner.breeding') }}" class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition border-l-4 border-[#AFC97E]">
        <h3 class="text-lg font-bold text-[#877B66] mb-2">Manajemen Pembiakan Telur</h3>
        <p class="text-sm text-gray-600">Kelola jadwal & status proses pembiakan telur.</p>
        <div class="mt-2 text-xs text-right text-[#AFC97E]">
          <span class="font-semibold">{{ $breedingCount ?? 0 }}</span> aktif
        </div>
      </a>
      <a href="{{ route('owner.stock') }}" class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition border-l-4 border-[#AFC97E]">
        <h3 class="text-lg font-bold text-[#877B66] mb-2">Stok Distribusi</h3>
        <p class="text-sm text-gray-600">Pantau & atur stok yang tersedia untuk didistribusikan.</p>
        <div class="mt-2 text-xs text-right text-[#AFC97E]">
          <span class="font-semibold">{{ $stockCount ?? 0 }}</span> jenis stok
        </div>
      </a>
      <a href="{{ route('owner.transaction') }}" class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition border-l-4 border-[#AFC97E]">
        <h3 class="text-lg font-bold text-[#877B66] mb-2">Transaksi</h3>
        <p class="text-sm text-gray-600">Lihat & konfirmasi transaksi dari mitra pengepul.</p>
        <div class="mt-2 text-xs text-right text-[#AFC97E]">
          <span class="font-semibold">{{ $pendingTransactions ?? 0 }}</span> menunggu konfirmasi
        </div>
      </a>
      <a href="{{ route('owner.finance') }}" class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition border-l-4 border-[#AFC97E]">
        <h3 class="text-lg font-bold text-[#877B66] mb-2">Keuangan</h3>
        <p class="text-sm text-gray-600">Catat pemasukan & pengeluaran serta grafik keuangan.</p>
        <div class="mt-2 text-xs text-right text-[#AFC97E]">
          <span class="font-semibold">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</span> pemasukan bulan ini
        </div>
      </a>
      <a href="{{ route('owner.profile') }}" class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition border-l-4 border-[#AFC97E]">
        <h3 class="text-lg font-bold text-[#877B66] mb-2">Profil Akun</h3>
        <p class="text-sm text-gray-600">Lihat & ubah informasi pemilik akun.</p>
        <div class="mt-2 text-xs text-right text-[#AFC97E]">
          <span class="font-semibold">{{ Auth::user()->email }}</span>
        </div>
      </a>
    </section>

    <!-- Notifikasi -->
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

  </main>

  <script>
    // AJAX Example untuk update notifikasi
    document.addEventListener('DOMContentLoaded', function() {
      // Contoh fungsi untuk update data secara realtime
      function updateDashboardData() {
        fetch('/api/owner/dashboard-data')
          .then(response => response.json())
          .then(data => {
            // Update data di dashboard
            console.log('Data updated:', data);
          });
      }
      
      // Update setiap 30 detik
      setInterval(updateDashboardData, 30000);
    });
  </script>
</body>
</html>