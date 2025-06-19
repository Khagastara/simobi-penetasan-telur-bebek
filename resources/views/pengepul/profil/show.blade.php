@extends('layouts.pengepul')

@section('content')
<div class="min-h-screen bg-gradient-to-br  py-8 px-4">
    <div class="max-w-4xl mx-auto">

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-[#877B66] to-[#A08B75] p-8 text-white relative">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white bg-opacity-10 rounded-full -translate-y-16 translate-x-16"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white bg-opacity-10 rounded-full translate-y-12 -translate-x-12"></div>

                <div class="relative z-10">
                    <h2 class="text-2xl font-bold text-center">{{ $pengepul->nama ?? 'Nama Pengepul' }}</h2>
                    <p class="text-center text-white text-opacity-90 mt-2">Pengepul</p>
                </div>
            </div>

            <div class="p-8">
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-6">
                        <div class="border-b border-gray-100 pb-4">
                            <h3 class="text-lg font-semibold text-[#877B66] mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zM8 6a2 2 0 114 0v1H8V6z" clip-rule="evenodd"></path>
                                </svg>
                                Informasi Personal
                            </h3>
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <div class="w-8 h-8 bg-[#E2D686] bg-opacity-20 rounded-lg flex items-center justify-center mr-3 mt-1">
                                        <svg class="w-4 h-4 text-[#877B66]" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zM8 6a2 2 0 114 0v1H8V6z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Nama Lengkap</p>
                                        <p class="text-[#877B66] font-medium">{{ $pengepul->nama ?? 'Belum diisi' }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="w-8 h-8 bg-[#E2D686] bg-opacity-20 rounded-lg flex items-center justify-center mr-3 mt-1">
                                        <svg class="w-4 h-4 text-[#877B66]" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Nomor Telepon</p>
                                        <p class="text-[#877B66] font-medium">{{ $pengepul->no_hp ?? 'Belum diisi' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="border-b border-gray-100 pb-4">
                            <h3 class="text-lg font-semibold text-[#877B66] mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Informasi Akun
                            </h3>
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <div class="w-8 h-8 bg-[#E2D686] bg-opacity-20 rounded-lg flex items-center justify-center mr-3 mt-1">
                                        <svg class="w-4 h-4 text-[#877B66]" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Email</p>
                                        <p class="text-[#877B66] font-medium">{{ $pengepul->akun->email ?? 'Belum diisi' }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="w-8 h-8 bg-[#E2D686] bg-opacity-20 rounded-lg flex items-center justify-center mr-3 mt-1">
                                        <svg class="w-4 h-4 text-[#877B66]" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Username</p>
                                        <p class="text-[#877B66] font-medium">{{ $pengepul->akun->username ?? 'Belum diisi' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 mt-8 pt-6 border-t border-gray-100">
                    <a href="{{ route('pengepul.profil.edit') }}"
                       class="flex-1 bg-gradient-to-r from-[#E2D686] to-[#FFDF64] hover:from-[#FFDF64] hover:to-[#E2D686] text-[#877B66] font-semibold px-6 py-3 rounded-xl shadow-lg transition-all duration-300 text-center flex items-center justify-center group">
                        <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                        </svg>
                        Ubah Data
                    </a>

                    <button type="button"
                            onclick="confirmLogout()"
                            class="flex-1 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg transition-all duration-300 flex items-center justify-center group">
                        <svg class="w-5 h-5 mr-2 group-hover:translate-x-1 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.586 9.414a2 2 0 11-2.828 2.828L8 12.414l2.758-2.758a2 2 0 112.828 2.828z" clip-rule="evenodd"></path>
                        </svg>
                        Logout
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
    @csrf
</form>

<script>
    function confirmLogout() {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
        modal.innerHTML = `
            <div class="bg-white rounded-2xl p-6 max-w-sm w-full mx-4 transform transition-all">
                <div class="text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Konfirmasi Logout</h3>
                    <p class="text-gray-600 mb-6">Apakah Anda yakin ingin keluar dari akun?</p>
                    <div class="flex gap-3">
                        <button onclick="closeLogoutModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button onclick="submitLogout()" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                            Ya, Logout
                        </button>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(modal);

        setTimeout(() => {
            modal.querySelector('div > div').style.transform = 'scale(1)';
        }, 10);
    }

    function closeLogoutModal() {
        const modal = document.querySelector('.fixed.inset-0');
        if (modal) {
            modal.querySelector('div > div').style.transform = 'scale(0.95)';
            setTimeout(() => {
                document.body.removeChild(modal);
            }, 150);
        }
    }

    function submitLogout() {
        document.getElementById('logout-form').submit();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const profileCard = document.querySelector('.bg-white.rounded-2xl');
        profileCard.style.opacity = '0';
        profileCard.style.transform = 'translateY(20px)';

        setTimeout(() => {
            profileCard.style.transition = 'all 0.6s ease-out';
            profileCard.style.opacity = '1';
            profileCard.style.transform = 'translateY(0)';
        }, 100);

        const statCards = document.querySelectorAll('.grid-cols-1.md\\:grid-cols-3 > div');
        statCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px) scale(1.02)';
                this.style.transition = 'transform 0.2s ease';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLogoutModal();
        }
    });
</script>

<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }
</style>
@endsection
