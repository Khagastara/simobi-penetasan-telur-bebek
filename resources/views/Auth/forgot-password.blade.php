@extends('layouts.app')

@section('content')
<div class="min-h-screen flex">
    <!-- Left Side: Image -->
    <div class="w-1/2">
        <img src="{{ asset('images/icons/foto halaman login.jpg') }}" alt="Descriptive Alt Text" class="w-full h-screen object-cover">
    </div>

    <!-- Right Side: Password Recovery Form -->
    <div class="w-1/2 flex items-center justify-center bg-[#D4E6B5]">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden p-8 w-full max-w-md">
            @if(!isset($email))
                <h1 class="text-2xl font-bold text-[#877B66] text-center mb-4">Lupa Password</h1>
            @else
                <h1 class="text-2xl font-bold text-[#877B66] text-center mb-4">Verifikasi Kode OTP</h1>
            @endif

            <div class="mb-4">
                @if (session('success'))
                    <div class="alert alert-success mb-4 text-green-600">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger mb-4 text-red-600">
                        {{ session('error') }}
                    </div>
                @endif
            </div>

            @if(!isset($email))
                <!-- Form Lupa Password -->
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                        <input id="email" type="email" class="w-full px-4 py-2 border border-[#74A620] rounded-md focus:outline-none focus:border-[#E2D686] focus:shadow-[0_0_0_3px_rgba(226,214,134,0.4)]" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-0">
                        <button type="submit" class="w-full py-2 px-4 rounded-md bg-[#5B5447] text-[#F9F8F8] hover:bg-[#A8956F] transition-all duration-200">
                            Kirim
                        </button>
                        <a href="{{ route('login') }}" class="block text-center text-[#5B5447] text-sm font-medium hover:text-[#A8956F] hover:underline mt-2">
                            Kembali ke Login
                        </a>
                    </div>
                </form>
            @else
                <!-- Form Verifikasi OTP -->
                <p class="text-center mb-4">Kode OTP telah dikirim ke email: <strong>{{ $email }}</strong></p>

                <form method="POST" action="{{ route('password.otp.verify') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">

                    <div class="mb-4">
                        <label for="otp" class="block text-sm font-medium text-gray-700 mb-1">Kode OTP</label>
                        <input id="otp" type="text" class="w-full px-4 py-2 border border-[#74A620] rounded-md focus:outline-none focus:border-[#E2D686] focus:shadow-[0_0_0_3px_rgba(226,214,134,0.4)]" name="otp" value="{{ old('otp') }}" required autofocus>

                        @error('otp')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-0">
                        <button type="submit" class="w-full py-2 px-4 rounded-md bg-[#5B5447] text-[#F9F8F8] hover:bg-[#A8956F] transition-all duration-200">
                            Kirim
                        </button>
                        <a href="{{ route('password.request') }}" class="block text-center text-[#5B5447] text-sm font-medium hover:text-[#A8956F] hover:underline mt-2">
                            Kembali
                        </a>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection