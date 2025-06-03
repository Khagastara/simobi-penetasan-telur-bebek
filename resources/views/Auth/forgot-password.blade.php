@extends('layouts.app')

@section('content')
<div class="min-h-screen flex">
    <div class="w-1/2">
        <img src="{{ asset('images/icons/foto halaman login.jpg') }}" alt="Descriptive Alt Text" class="w-full h-screen object-cover">
    </div>

    <div class="w-1/2 flex items-center justify-center bg-[#D4E6B5]">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden p-8 w-full max-w-md">
            @if(!isset($step) || $step == 'email')
                <h1 class="text-2xl font-bold text-[#877B66] text-center mb-4">Lupa Password</h1>
            @elseif($step == 'otp')
                <h1 class="text-2xl font-bold text-[#877B66] text-center mb-4">Verifikasi Kode OTP</h1>
            @else
                <h1 class="text-2xl font-bold text-[#877B66] text-center mb-4">Ubah Password</h1>
            @endif

            <div class="mb-4">
                @if (session('success'))
                    <div class="alert alert-success mb-4 text-green-600 bg-green-50 border border-green-200 rounded p-3">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger mb-4 text-red-600 bg-red-50 border border-red-200 rounded p-3">
                        {{ session('error') }}
                    </div>
                @endif
            </div>

            @if(!isset($step) || $step == 'email')
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

            @elseif($step == 'otp')
                <p class="text-center mb-4 text-sm text-gray-600">Kode OTP telah dikirim ke email: <strong>{{ $email }}</strong></p>

                <form method="POST" action="{{ route('password.otp.verify') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">

                    <div class="mb-4">
                        <label for="otp" class="block text-sm font-medium text-gray-700 mb-1">Kode OTP</label>
                        <input id="otp" type="text" class="w-full px-4 py-2 border border-[#74A620] rounded-md focus:outline-none focus:border-[#E2D686] focus:shadow-[0_0_0_3px_rgba(226,214,134,0.4)]" name="otp" value="{{ old('otp') }}" required autofocus maxlength="6" pattern="[0-9]{6}">

                        @error('otp')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <button type="submit" class="w-full py-2 px-4 rounded-md bg-[#5B5447] text-[#F9F8F8] hover:bg-[#A8956F] transition-all duration-200">
                            Verifikasi
                        </button>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-600 mb-2">Tidak menerima kode?</p>
                        <button type="button" id="resendBtn" onclick="resendOtp()" class="text-[#5B5447] text-sm font-medium hover:text-[#A8956F] hover:underline disabled:text-gray-400 disabled:no-underline" disabled>
                            <span id="resendText">Kirim Ulang</span>
                            <span id="countdown">(60s)</span>
                        </button>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('password.request') }}" class="block text-center text-[#5B5447] text-sm font-medium hover:text-[#A8956F] hover:underline">
                            Kembali
                        </a>
                    </div>
                </form>

            @else
                <!-- Form Reset Password -->
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                        <input id="password" type="password" class="w-full px-4 py-2 border border-[#74A620] rounded-md focus:outline-none focus:border-[#E2D686] focus:shadow-[0_0_0_3px_rgba(226,214,134,0.4)] @error('password') border-red-500 @enderror" name="password" required autocomplete="new-password">

                        @error('password')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password-confirm" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                        <input id="password-confirm" type="password" class="w-full px-4 py-2 border border-[#74A620] rounded-md focus:outline-none focus:border-[#E2D686] focus:shadow-[0_0_0_3px_rgba(226,214,134,0.4)] @error('password_confirmation') border-red-500 @enderror" name="password_confirmation" required autocomplete="new-password">

                        @error('password_confirmation')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-0">
                        <button type="submit" class="w-full py-2 px-4 rounded-md bg-[#5B5447] text-[#F9F8F8] hover:bg-[#A8956F] transition-all duration-200">
                            Simpan
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>

@if(isset($step) && $step == 'otp')
<script>
let countdown = 60;
let timer;

function startCountdown() {
    const resendBtn = document.getElementById('resendBtn');
    const countdownSpan = document.getElementById('countdown');

    resendBtn.disabled = true;

    timer = setInterval(() => {
        countdown--;
        countdownSpan.textContent = `(${countdown}s)`;

        if (countdown <= 0) {
            clearInterval(timer);
            resendBtn.disabled = false;
            countdownSpan.textContent = '';
            countdown = 60;
        }
    }, 1000);
}

function resendOtp() {
    fetch('{{ route("password.email") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            email: '{{ $email ?? "" }}'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success mb-4 text-green-600 bg-green-50 border border-green-200 rounded p-3';
            alertDiv.textContent = 'Kode OTP baru telah dikirim ke email Anda';

            const existingAlert = document.querySelector('.alert');
            if (existingAlert) {
                existingAlert.replaceWith(alertDiv);
            } else {
                document.querySelector('h1').after(alertDiv);
            }

            startCountdown();
        } else {
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger mb-4 text-red-600 bg-red-50 border border-red-200 rounded p-3';
            alertDiv.textContent = data.message || 'Terjadi kesalahan saat mengirim kode OTP';

            const existingAlert = document.querySelector('.alert');
            if (existingAlert) {
                existingAlert.replaceWith(alertDiv);
            } else {
                document.querySelector('h1').after(alertDiv);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Start countdown when page loads
document.addEventListener('DOMContentLoaded', function() {
    startCountdown();
});
</script>
@endif
@endsection
