@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                @if(!isset($email))
                    <div class="card-header">Lupa Password</div>
                @else
                    <div class="card-header">Verifikasi Kode OTP</div>
                @endif

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(!isset($email))
                        <!-- Form Lupa Password -->
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        {{-- <strong>{{ $message }}</strong> --}} ?error--
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-0">
                                <button type="submit" class="btn btn-primary">
                                    Kirim
                                </button>
                                <a href="{{ route('login') }}" class="btn btn-link">
                                    Kembali ke Login
                                </a>
                            </div>
                        </form>
                    @else
                        <!-- Form Verifikasi OTP -->
                        <p>Kode OTP telah dikirim ke email: <strong>{{ $email }}</strong></p>

                        <form method="POST" action="{{ route('password.otp.verify') }}">
                            @csrf
                            <input type="hidden" name="email" value="{{ $email }}">

                            <div class="mb-3">
                                <label for="otp" class="form-label">Kode OTP</label>
                                <input id="otp" type="text" class="form-control @error('otp') is-invalid @enderror" name="otp" value="{{ old('otp') }}" required autofocus>

                                @error('otp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-0">
                                <button type="submit" class="btn btn-primary">
                                    Kirim
                                </button>
                                <a href="{{ route('password.request') }}" class="btn btn-link">
                                    Kembali
                                </a>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection