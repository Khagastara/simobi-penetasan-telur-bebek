@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verifikasi Kode OTP') }}</div>

                <div class="card-body">
                    <p class="text-center">Kami telah mengirim kode OTP ke email <strong>{{ session('email') }}</strong>. Silakan periksa email Anda dan masukkan kode OTP di bawah ini.</p>

                    <form method="POST" action="{{ route('verify.otp') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="otp" class="col-md-4 col-form-label text-md-end">{{ __('Kode OTP') }}</label>

                            <div class="col-md-6">
                                <input id="otp" type="text" class="form-control @error('otp') is-invalid @enderror" name="otp" required autocomplete="off" autofocus maxlength="6">

                                @error('otp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Verifikasi') }}
                                </button>

                                <a href="{{ route('forgot.password.form') }}" class="btn btn-link">
                                    {{ __('Kirim Ulang OTP') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
