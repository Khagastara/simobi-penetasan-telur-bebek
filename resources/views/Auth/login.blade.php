@extends('layouts.app')

@section('content')
<div class="min-h-screen flex">
    <!-- Left Side: Image -->
    <div class="w-1/2">
        <img src="{{ asset('images/icons/foto halaman login.jpg') }}" alt="Descriptive Alt Text" class="w-full h-screen object-cover">
    </div>

    <!-- Right Side: Login Form -->
    <div class="w-1/2 flex items-center justify-center bg-[#D4E6B5]">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden p-8 w-full max-w-md">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-[#877B66] text-center mb-4">
                    Login
                </h1>
            </div>

            <form action="{{ route('login.submit') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Masukkan Email"
                        class="w-full px-4 py-2 border border-[#74A620] rounded-xl focus:outline-none focus:ring-0 focus:border-[#E2D686] focus:shadow-[0_0_0_3px_rgba(226,214,134,0.4)] transition duration-200"
                        value="{{ old('username') }}"
                        required
                    >
                </div>
                <div class="mb-4">
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Masukkan Password"
                            class="w-full px-4 py-2 border border-[#74A620] rounded-xl focus:outline-none focus:ring-0 focus:border-[#E2D686] focus:shadow-[0_0_0_3px_rgba(226,214,134,0.4)] transition duration-200"
                            required
                        >
                        <button
                            type="button"
                            id="togglePassword"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none"
                        >
                            <svg id="openEye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-.274 1.057-.732 2.057-1.342 3.002M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <svg id="closedEye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.05 10.05 0 012.133-3.592m3.25-2.5A10.05 10.05 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.05 10.05 0 01-2.133 3.592m-3.25 2.5L4.5 4.5m15 15L4.5 4.5" />
                            </svg>
                        </button>
                    </div>
                    <div class="text-right mt-2">
                        <a href="{{ route('password.request') }}" class="text-[#5B5447] text-sm font-medium hover:text-[#A8956F] hover:underline">Lupa Password?</a>
                    </div>
                </div>
                <button
                    type="submit"
                    class="w-full py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 bg-[#5B5447] text-[#F9F8F8] hover:bg-[#A8956F] hover:translate-y-[-1px] transition-all duration-200"> Login
                </button>

                <!-- Error messages container - moved below the login button -->
                <div class="mt-4 text-center">
                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif
                    @error('username')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6 text-center">
                    <p class="text-sm text-[#877B66]">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-[#61920E] font-medium hover:text-[#E2D686] hover:underline">Register</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
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
