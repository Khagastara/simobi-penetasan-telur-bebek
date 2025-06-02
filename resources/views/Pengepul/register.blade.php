@extends('layouts.app')

@section('content')
<div class="min-h-screen flex">
    <!-- Left Side: Register Form -->
    <div class="w-1/2 flex items-center justify-center bg-[#D4E6B5] font-['Poppins']">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden p-8 w-full max-w-md">
            <!-- Header -->
            <div class="mb-4 text-center">
                <h1 class="text-2xl font-bold text-[#877B66] mb-1">Register</h1>
            </div>

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

                <!-- Nama -->
                <div class="mb-4">
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" placeholder="Masukkan Nama Lengkap" class="w-full px-4 py-2 border border-[#74A620] rounded-md focus:outline-none focus:border-[#E2D686] focus:shadow-[0_0_0_3px_rgba(226,214,134,0.4)]" required>
                </div>

                <!-- No HP -->
                <div class="mb-4">
                    <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                    <input type="text" id="no_hp" name="no_hp" placeholder="Contoh: 0852225641512" class="w-full px-4 py-2 border border-[#74A620] rounded-md focus:outline-none focus:border-[#E2D686] focus:shadow-[0_0_0_3px_rgba(226,214,134,0.4)]" required>
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="email" name="email" placeholder="Masukkan Email" class="w-full px-4 py-2 border border-[#74A620] rounded-md focus:outline-none focus:border-[#E2D686] focus:shadow-[0_0_0_3px_rgba(226,214,134,0.4)]" required>
                </div>

                <!-- Username -->
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" id="username" name="username" placeholder="Masukkan username unik" class="w-full px-4 py-2 border border-[#74A620] rounded-md focus:outline-none focus:border-[#E2D686] focus:shadow-[0_0_0_3px_rgba(226,214,134,0.4)]" required>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password" class="w-full px-4 py-2 border border-[#74A620] rounded-md focus:outline-none focus:border-[#E2D686] focus:shadow-[0_0_0_3px_rgba(226,214,134,0.4)]" required>
                </div>

                <!-- Konfirmasi Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi password" class="w-full px-4 py-2 border border-[#74A620] rounded-md focus:outline-none focus:border-[#E2D686] focus:shadow-[0_0_0_3px_rgba(226,214,134,0.4)]" required>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-2 px-4 rounded-md bg-[#5B5447] text-[#F9F8F8] hover:bg-[#A8956F] hover:translate-y-[-1px] transition-all duration-200">
                    Daftar
                </button>

                <!-- Login Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-[#877B66]">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="text-[#61920E] font-medium hover:text-[#A8956F] hover:underline">Login</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Side: Image -->
    <div class="w-1/2">
        <img src="{{ asset('images/icons/foto halaman login.jpg') }}" alt="Descriptive Alt Text" class="w-full h-screen object-cover">
    </div>
</div>
@endsection

