@extends('layouts.owner')

@section('content')
    <div class="min-h-screen bg-gradient-to-br to-[#E2D686] p-6">
        <div class="bg-white rounded-3xl p-8 mb-8 shadow-xl">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-[#877B66] mb-3">Selamat Datang di SiMOBI</h1>
                    <p class="text-gray-600 text-lg mb-2">Sistem menejemen operasional bebek.</p>
                    <p class="text-gray-600 mb-4">Pantau kemajuan bisnismu melalui dashboard berikut</p>
                    <div class="flex items-center text-gray-500 text-sm">
                        <span>Cek transaksi yang belum dikonfirmasi</span>
                        <span class="ml-auto text-2xl font-bold">{{ $transaksiPending }}</span>
                    </div>
                </div>
                <div class="ml-8">
                    <div class="w-32 h-32 relative">
                        <img src="{{ asset('images/icons/bebek wekwek.png') }}" alt="" >
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-white">
                    <span class="text-[#877B66]">Periode :</span>
                    <span class="ml-4 mr-4 bg-white px-4 py-2 rounded-full text-gray-700">
                        {{ $dashboardData['periode_start'] }} - {{ $dashboardData['periode_end'] }}
                    </span>
                    {{-- Form untuk navigasi bulan sebelumnya --}}
                    <form method="GET" action="{{ route('dashboard.change-month') }}" class="inline">
                        <input type="hidden" name="direction" value="prev">
                        <input type="hidden" name="current_date" value="{{ request('date', now()->format('Y-m-d')) }}">
                        <button type="submit" class="bg-white p-2 rounded-lg shadow hover:shadow-lg transition-all">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                    </form>
                    {{-- Form untuk navigasi bulan berikutnya --}}
                    <form method="GET" action="{{ route('dashboard.change-month') }}" class="inline ml-2">
                        <input type="hidden" name="direction" value="next">
                        <input type="hidden" name="current_date" value="{{ request('date', now()->format('Y-m-d')) }}">
                        <button type="submit" class="bg-white p-2 rounded-lg shadow hover:shadow-lg transition-all">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </form>
                </h2>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            {{-- Card Total Pemasukan --}}
            <div class="bg-white rounded-3xl p-8 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-cyan-100 rounded-2xl flex items-center justify-center mr-4 border-2 border-blue-300">
                        <svg class="w-8 h-8" fill="none" stroke="url(#gradient2)" viewBox="0 0 24 24">
                            <defs>
                                <linearGradient id="gradient2" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color:#3B82F6;stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:#06B6D4;stop-opacity:1" />
                                </linearGradient>
                            </defs>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-gray-600 text-sm mb-1">Total Pemasukan</h3>
                        <p class="text-2xl font-bold text-gray-800">Rp{{ number_format($dashboardData['total_pemasukan'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Card Total Pengeluaran --}}
            <div class="bg-white rounded-3xl p-8 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-red-100 to-pink-100 rounded-2xl flex items-center justify-center mr-4 border-2 border-red-300">
                        <svg class="w-8 h-8" fill="none" stroke="url(#gradient3)" viewBox="0 0 24 24">
                            <defs>
                                <linearGradient id="gradient3" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color:#EF4444;stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:#EC4899;stop-opacity:1" />
                                </linearGradient>
                            </defs>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-gray-600 text-sm mb-1">Total Pengeluaran</h3>
                        <p class="text-2xl font-bold text-gray-800">Rp{{ number_format($dashboardData['total_pengeluaran'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
                        {{-- Card Selisih Keuangan --}}
            <div class="bg-white rounded-3xl p-8 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-yellow-100 to-orange-100 rounded-2xl flex items-center justify-center mr-4 border-2 border-yellow-300">
                        <svg class="w-8 h-8" fill="none" stroke="url(#gradient1)" viewBox="0 0 24 24">
                            <defs>
                                <linearGradient id="gradient1" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color:#FCD34D;stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:#F97316;stop-opacity:1" />
                                </linearGradient>
                            </defs>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-gray-600 text-sm mb-1">Selisih Keuangan</h3>
                        <div class="flex items-center">
                            @if($dashboardData['selisih_keuangan'] >= 0)
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#10B981" class="bi bi-arrow-up-circle-fill mr-2" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 0 0 8a8 8 0 0 0 16 0m-7.5 3.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707z"/>
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#EF4444" class="bi bi-arrow-down-circle-fill mr-2" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293z"/>
                                </svg>
                            @endif
                            <p class="text-2xl font-bold text-gray-800">Rp{{ number_format($dashboardData['selisih_keuangan'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Selisih Keuangan --}}
            <div class="bg-white rounded-3xl p-8 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-yellow-100 to-orange-100 rounded-2xl flex items-center justify-center mr-4 border-2 border-yellow-300">
                        <svg class="w-8 h-8" fill="none" stroke="url(#gradient1)" viewBox="0 0 24 24">
                            <defs>
                                <linearGradient id="gradient1" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color:#FCD34D;stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:#F97316;stop-opacity:1" />
                                </linearGradient>
                            </defs>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-gray-600 text-sm mb-1">Selisih Keuangan</h3>
                        <div class="flex items-center">
                            @if($dashboardData['selisih_keuangan'] >= 0)
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#10B981" class="bi bi-arrow-up-circle-fill mr-2" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 0 0 8a8 8 0 0 0 16 0m-7.5 3.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707z"/>
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#EF4444" class="bi bi-arrow-down-circle-fill mr-2" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293z"/>
                                </svg>
                            @endif
                            <p class="text-2xl font-bold text-gray-800">Rp{{ number_format($dashboardData['selisih_keuangan'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Card Jadwal Gagal --}}
            <div class="bg-white rounded-3xl p-8 shadow-xl">
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-100 to-orange-100 rounded-2xl flex items-center justify-center mr-4 border-2 border-purple-300">
                        <svg class="w-8 h-8" fill="none" stroke="url(#gradient4)" viewBox="0 0 24 24">
                            <defs>
                                <linearGradient id="gradient4" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color:#8B5CF6;stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:#F97316;stop-opacity:1" />
                                </linearGradient>
                            </defs>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-gray-600 text-sm mb-1">Jadwal Gagal</h3>
                        <p class="text-3xl font-bold text-gray-800">{{ $dashboardData['jadwal_gagal'] }}</p>
                    </div>
                </div>
            </div>

            {{-- Navigation Cards --}}
            <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="{{ route('owner.profil.show') }}"
                   class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-[#AFC97E]">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-200 to-lime-200 rounded-lg flex items-center justify-center mr-3 border border-green-300">
                            <svg class="w-5 h-5" fill="none" stroke="url(#gradient5)" viewBox="0 0 24 24">
                                <defs>
                                    <linearGradient id="gradient5" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" style="stop-color:#10B981;stop-opacity:1" />
                                        <stop offset="100%" style="stop-color:#84CC16;stop-opacity:1" />
                                    </linearGradient>
                                </defs>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-[#877B66]">Profil</h3>
                    </div>
                    <p class="text-sm text-gray-600">Kelola informasi profil owner</p>
                </a>

                <a href="{{ route('owner.penjadwalan.index') }}"
                   class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-[#E2D686]">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-yellow-200 to-amber-200 rounded-lg flex items-center justify-center mr-3 border border-yellow-300">
                            <svg class="w-5 h-5" fill="none" stroke="url(#gradient6)" viewBox="0 0 24 24">
                                <defs>
                                    <linearGradient id="gradient6" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" style="stop-color:#F59E0B;stop-opacity:1" />
                                        <stop offset="100%" style="stop-color:#D97706;stop-opacity:1" />
                                    </linearGradient>
                                </defs>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-[#877B66]">Jadwal</h3>
                    </div>
                    <p class="text-sm text-gray-600">Kelola jadwal penetasan telur</p>
                </a>

                <a href="{{ route('owner.transaksi.index') }}"
                   class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-[#877B66]">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-gray-300 to-stone-300 rounded-lg flex items-center justify-center mr-3 border border-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="url(#gradient7)" viewBox="0 0 24 24">
                                <defs>
                                    <linearGradient id="gradient7" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" style="stop-color:#6B7280;stop-opacity:1" />
                                        <stop offset="100%" style="stop-color:#374151;stop-opacity:1" />
                                    </linearGradient>
                                </defs>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-[#877B66]">Transaksi</h3>
                    </div>
                    <p class="text-sm text-gray-600">Lihat riwayat transaksi</p>
                </a>
            </div>
        </div>
    </div>

    {{-- Success Notification --}}
    @if(session('success'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg z-50 animate-slide-in">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('success') }}
            </div>
        </div>
        <script>
            setTimeout(() => {
                const notification = document.querySelector('.animate-slide-in');
                if (notification) {
                    notification.classList.add('animate-slide-out');
                    setTimeout(() => notification.remove(), 300);
                }
            }, 3000);
        </script>
    @endif

    <style>
        @keyframes slide-in {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slide-out {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        .animate-slide-in {
            animation: slide-in 0.3s ease-out;
        }

        .animate-slide-out {
            animation: slide-out 0.3s ease-in;
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
        .active-menu {
            background-color: #E2D686;
            color: #000;
            font-weight: 500;
        }
    </style>
@endsection
