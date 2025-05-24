@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-[#D4E6B5] font-['Poppins']">
    <div class="w-full max-w-md mx-4">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-[#AFC97E] py-4 px-6">
                <h1 class="text-2xl font-bold text-white text-center">
                    <span class="text-[#877B66] font-bold">SiMOBI</span>
                </h1>
                <p class="text-white text-sm text-center mt-1">
                    Sistem Manajemen Operasional Penetasan Telur Bebek
                </p>
            </div>

            <!-- Login Form -->
            <div class="px-8 py-6">
                <h2 class="text-xl font-semibold text-[#877B66] text-center mb-6">Masuk ke Akun Anda</h2>
                    <form action="{{ route('login.submit') }}" method="POST">
                        @csrf
                        <!-- Username Input -->
                        <div class="mb-4">
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
                                Username
                            </label>
                            <input
                                type="text"
                                id="username"
                                name="username"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-0 focus:border-[#E2D686] focus:shadow-[0_0_0_3px_rgba(226,214,134,0.4)] transition duration-200"
                                required
                            >
                        </div>
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                Password
                            </label>
                            <div class="relative">
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-0 focus:border-[#E2D686] focus:shadow-[0_0_0_3px_rgba(226,214,134,0.4)] transition duration-200"
                                    required
                                >
                                <button
                                    type="button"
                                    id="togglePassword"
                                    class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none"
                                >
                                    <!-- Open Eye Icon -->
                                    <svg id="openEye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-.274 1.057-.732 2.057-1.342 3.002M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <!-- Closed Eye Icon -->
                                    <svg id="closedEye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.05 10.05 0 012.133-3.592m3.25-2.5A10.05 10.05 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.05 10.05 0 01-2.133 3.592m-3.25 2.5L4.5 4.5m15 15L4.5 4.5" />
                                    </svg>
                                </button>
                            </div>
                            <div class="text-right mt-2">
                                <a href="{{ route('password.request') }}" class="text-[#AFC97E] text-sm font-medium hover:text-[#E2D686] hover:underline">
                                    Lupa Password?
                                </a>
                            </div>
                        </div>
                        <button
                            type="submit"
                            class="w-full py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 bg-[#FFDF64] text-black hover:bg-[#E2D686] hover:translate-y-[-1px] transition-all duration-200"> Masuk
                        </button>
                        <div class="mt-6 text-center">
                            <p class="text-sm text-[#877B66]">
                                Belum punya akun?
                                <a href="{{ route('register') }}" class="text-[#AFC97E] font-medium hover:text-[#E2D686] hover:underline">
                                    Daftar sebagai Pengepul
                                </a>
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        </form>
    </div>
</div>

<script>
    src="{{ asset('js/password-toggle.js') }}">
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('password');
        const openEye = document.getElementById('openEye');
        const closedEye = document.getElementById('closedEye');

        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        openEye.classList.toggle('hidden');
        closedEye.classList.toggle('hidden');
    });
</script>
@endsection
