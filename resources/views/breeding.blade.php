<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Pembiakan Telur - SiMOBI</title>
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
      <a href="{{ route('owner.dashboard') }}" class="block px-4 py-2 rounded hover:text-black">
        <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
      </a>
      <a href="{{ route('owner.breeding') }}" class="block px-4 py-2 rounded active-menu">
        <i class="fas fa-egg mr-2"></i> Pembiakan Telur
      </a>
      <a href="{{ route('owner.stock') }}" class="block px-4 py-2 rounded hover:text-black">
        <i class="fas fa-boxes mr-2"></i> Stok Distribusi
      </a>
      <a href="{{ route('owner.transaction') }}" class="block px-4 py-2 rounded hover:text-black">
        <i class="fas fa-exchange-alt mr-2"></i> Transaksi
      </a>
      <a href="{{ route('owner.finance') }}" class="block px-4 py-2 rounded hover:text-black">
        <i class="fas fa-chart-line mr-2"></i> Keuangan
      </a>
      <a href="{{ route('owner.profile') }}" class="block px-4 py-2 rounded hover:text-black">
        <i class="fas fa-user-circle mr-2"></i> Profil Akun
      </a>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
      <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block px-4 py-2 rounded hover:text-black">
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
        </div>
      </div>
    </header>

    <!-- Page Content -->
    <div class="p-8">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-[#877B66]">Manajemen Pembiakan Telur</h2>
        <button onclick="toggleModal(true)" class="bg-[#FFDF64] text-[#877B66] px-4 py-2 rounded hover:bg-[#E2D686] transition">
          <i class="fas fa-plus mr-1"></i> Tambah Jadwal
        </button>
      </div>

      <!-- Tabel Jadwal -->
      <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="min-w-full text-sm text-gray-700">
          <thead class="bg-[#AFC97E] text-white">
            <tr>
              <th class="text-left px-6 py-3">Tanggal</th>
              <th class="text-left px-6 py-3">Waktu</th>
              <th class="text-left px-6 py-3">Nama Kegiatan</th>
              <th class="text-left px-6 py-3">Status</th>
              <th class="text-left px-6 py-3">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($schedules as $schedule)
              <tr class="border-b">
                <td class="px-6 py-3">{{ $schedule->tanggal }}</td>
                <td class="px-6 py-3">{{ $schedule->jam }}</td>
                <td class="px-6 py-3">{{ $schedule->kegiatan }}</td>
                <td class="px-6 py-3">{{ $schedule->status }}</td>
                <td class="px-6 py-3">
                  <button onclick='editSchedule(@json($schedule))' class="text-blue-600 hover:underline mr-2">Edit</button>
                  <form action="{{ route('owner.breeding.delete', $schedule->id) }}" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" onclick="return confirm('Yakin hapus jadwal ini?')" class="text-red-600 hover:underline">Hapus</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="5" class="text-center py-6 text-gray-500">Belum ada jadwal</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal Form -->
    <div id="modalForm" class="fixed inset-0 z-50 hidden bg-black bg-opacity-30 flex justify-center items-center">
      <div class="bg-white rounded-lg w-full max-w-lg p-6 shadow-lg">
        <h3 class="text-xl font-bold mb-4 text-[#877B66]" id="modalTitle">Tambah Jadwal</h3>
        <form action="{{ route('owner.breeding.store') }}" method="POST">
          @csrf
          <input type="hidden" name="id" id="scheduleId">
          <div class="mb-4">
            <label class="block mb-1 text-sm">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" required class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-[#E2D686]">
          </div>
          <div class="mb-4">
            <label class="block mb-1 text-sm">Waktu</label>
            <input type="time" name="jam" id="jam" required class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-[#E2D686]">
          </div>
          <div class="mb-4">
            <label class="block mb-1 text-sm">Nama Kegiatan</label>
            <input type="text" name="kegiatan" id="kegiatan" required class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-[#E2D686]">
          </div>
          <div class="mb-4">
            <label class="block mb-1 text-sm">Status</label>
            <select name="status" id="status" required class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-[#E2D686]">
              <option value="Belum Dimulai">Belum Dimulai</option>
              <option value="Berlangsung">Berlangsung</option>
              <option value="Selesai">Selesai</option>
            </select>
          </div>
          <div class="flex justify-end space-x-2">
            <button type="button" onclick="toggleModal(false)" class="px-4 py-2 text-gray-600 hover:underline">Batal</button>
            <button type="submit" class="bg-[#FFDF64] px-4 py-2 rounded text-[#877B66] hover:bg-[#E2D686]">Simpan</button>
          </div>
        </form>
      </div>
    </div>

  </main>

  <!-- Scripts -->
  <script>
    function toggleModal(show) {
      document.getElementById('modalForm').classList.toggle('hidden', !show)
      if (!show) resetForm()
    }

    function resetForm() {
      document.getElementById('modalTitle').innerText = 'Tambah Jadwal'
      document.getElementById('scheduleId').value = ''
      document.getElementById('tanggal').value = ''
      document.getElementById('jam').value = ''
      document.getElementById('kegiatan').value = ''
      document.getElementById('status').value = 'Belum Dimulai'
    }

    function editSchedule(data) {
      toggleModal(true)
      document.getElementById('modalTitle').innerText = 'Edit Jadwal'
      document.getElementById('scheduleId').value = data.id
      document.getElementById('tanggal').value = data.tanggal
      document.getElementById('jam').value = data.jam
      document.getElementById('kegiatan').value = data.kegiatan
      document.getElementById('status').value = data.status
    }
  </script>

</body>
</html>
