<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiMOBI - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #D4E6B5;
        }

        .brand-logo {
            color: #877B66;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .login-btn {
            background-color: #FFDF64;
            color: #000000;
            transition: all 0.2s ease;
        }

        .login-btn:hover {
            background-color: #E2D686;
            transform: translateY(-1px);
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .input-field:focus {
            border-color: #E2D686;
            box-shadow: 0 0 0 3px rgba(226, 214, 134, 0.4);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md mx-4">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-[#AFC97E] py-4 px-6">
                <h1 class="text-2xl font-bold text-white text-center">
                    <span class="brand-logo">SiMOBI</span>
                </h1>
                <p class="text-white text-sm text-center mt-1">
                    Sistem Manajemen Operasional Penetasan Telur Bebek
                </p>
            </div>

            <!-- Login Form -->
            <div class="px-8 py-6">
                <h2 class="text-xl font-semibold text-[#877B66] text-center mb-6">Masuk ke Akun Anda</h2>

                @if($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded">
                        @foreach($errors->all() as $error)
                            <p class="text-red-700 text-sm">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('login.submit') }}" method="POST">
                    @csrf
                <!-- belum bisa karena belum dihubungkan dengan sources -->

                    <!-- Account Input -->
                    <div class="mb-4">
                        <label for="account" class="block text-sm font-medium text-gray-700 mb-1">
                            Akun
                        </label>
                        <input 
                            type="text" 
                            id="account" 
                            name="account" 
                            placeholder="Masukkan username atau email"
                            class="input-field w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-0 transition duration-200"
                            required
                        >
                    </div>

                    <!-- Password Input -->
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            Kata Sandi
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="Masukkan password"
                            class="input-field w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-0 transition duration-200"
                            required
                        >
                    </div>

                    <!-- Remember & Forgot -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <input 
                                type="checkbox" 
                                id="remember"
                                name="remember"
                                class="h-4 w-4 text-[#AFC97E] focus:ring-[#AFC97E] border-gray-300 rounded"
                            >
                            <label for="remember" class="ml-2 block text-sm text-gray-700">
                                Ingat saya
                            </label>
                        </div>
                        <a href="#" class="text-sm text-[#877B66] hover:text-[#AFC97E] forgot-link">
                            Lupa password?
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit"
                        class="w-full py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 login-btn"
                    >
                        Masuk
                    </button>
                </form>

                <!-- Register Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-[#877B66]">
                        Belum punya akun? 
                        <a href="{{ route('register') }}" class="text-[#AFC97E] font-medium hover:text-[#E2D686] forgot-link">
                            Daftar sebagai Pengepul
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
</body>
</html>

<!-- belum menggunakan file terpisah untuk styling karena tidak bisa terbaca 
 saat menggunakan css eksternal, hanya menggunakan tailwind saja -->