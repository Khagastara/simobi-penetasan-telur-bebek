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
                        Daftar Akun Pengepul
                    </p>
                </div>

                <!-- Register Form -->
                <div class="px-8 py-6">
                    <h2 class="text-xl font-semibold text-[#877B66] text-center mb-6">Buat Akun Baru</h2>

                    @if ($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li class="text-red-700 text-sm">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('register.submit') }}" method="POST">
                        @csrf
                        <!-- Nama Lengkap -->
                        <div class="mb-4">
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">
                                Nama
                            </label>
                            <input 
                                type="text" 
                                id="nama" 
                                name="nama" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-0 focus:border-[#E2D686] focus:shadow-[0_0_0_3px_rgba(226,214,134,0.4)] transition duration-200" 
                                required
                            >
                        </div>

                        <!-- Nomor HP -->
                        <div class="mb-4">
                            <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">
                                No HP
                            </label>
                            <input 
                                type="text" 
                                id="no_hp" 
                                name="no_hp" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-0 focus:border-[#E2D686] focus:shadow-[0_0_0_3px_rgba(226,214,134,0.4)] transition duration-200" 
                                required
                            >
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                E-mail
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-0 focus:border-[#E2D686] focus:shadow-[0_0_0_3px_rgba(226,214,134,0.4)] transition duration-200" 
                                required
                            >
                        </div>

                        <!-- Username -->
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

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                Password
                            </label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-0 focus:border-[#E2D686] focus:shadow-[0_0_0_3px_rgba(226,214,134,0.4)] transition duration-200" 
                                required
                            >
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="mb-6">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                Konfirmasi Password
                            </label>
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-0 focus:border-[#E2D686] focus:shadow-[0_0_0_3px_rgba(226,214,134,0.4)] transition duration-200" 
                                required
                            >
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit" 
                            class="w-full py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 bg-[#FFDF64] text-black hover:bg-[#E2D686] hover:translate-y-[-1px] transition-all duration-200"
                        >
                            Daftar
                        </button>
                    </form>

                    <!-- Login Link -->
                    <div class="mt-6 text-center">
                        <p class="text-sm text-[#877B66]">
                            Sudah punya akun? 
                            <a href="{{ route('login') }}" class="text-[#AFC97E] font-medium hover:text-[#E2D686] hover:underline">
                                Masuk di sini
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-6 text-center">
                <p class="text-xs text-gray-500">
                    &copy; 2024 SiMOBI - Tim PPL Agroindustri B4. All rights reserved.
                </p>
            </div>
        </div>
    </div>
@endsection