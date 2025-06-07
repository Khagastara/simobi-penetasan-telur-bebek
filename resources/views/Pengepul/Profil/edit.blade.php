@extends('layouts.pengepul')

@section('content')
<main class="flex-1 flex flex-col font-['Poppins'] bg-[#D4E6B5] min-h-screen">
    <section class="p-8">
        <div class="bg-white p-6 rounded-xl shadow max-w-3xl mx-auto">
            <h1 class="text-2xl font-bold text-[#877B66] mb-4">Ubah Profil Pengepul</h1>

            @if ($errors->any())
                <div class="bg-red-100 text-red-800 p-4 rounded-lg mb-4">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pengepul.profil.update') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="nama" class="block text-sm font-medium text-[#877B66] mb-1">Nama</label>
                    <input type="text" name="nama" class="form-input w-full border-gray-300 rounded-lg shadow-sm focus:ring-[#AFC97E] focus:border-[#AFC97E]" value="{{ $pengepul->nama }}" required>
                </div>
                <div>
                    <label for="no_hp" class="block text-sm font-medium text-[#877B66] mb-1">No HP</label>
                    <input type="text" name="no_hp" class="form-input w-full border-gray-300 rounded-lg shadow-sm focus:ring-[#AFC97E] focus:border-[#AFC97E]" value="{{ $pengepul->no_hp }}" required>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-[#877B66] mb-1">Email</label>
                    <input type="email" name="email" class="form-input w-full border-gray-300 rounded-lg shadow-sm focus:ring-[#AFC97E] focus:border-[#AFC97E]" value="{{ $pengepul->akun->email }}" required>
                </div>
                <div>
                    <label for="username" class="block text-sm font-medium text-[#877B66] mb-1">Username</label>
                    <input type="text" name="username" class="form-input w-full border-gray-300 rounded-lg shadow-sm focus:ring-[#AFC97E] focus:border-[#AFC97E]" value="{{ $pengepul->akun->username }}" required>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-[#877B66] mb-1">Password (Kosongkan jika tidak ingin mengubah)</label>
                    <input type="password" name="password" class="form-input w-full border-gray-300 rounded-lg shadow-sm focus:ring-[#AFC97E] focus:border-[#AFC97E]">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-[#877B66] mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-input w-full border-gray-300 rounded-lg shadow-sm focus:ring-[#AFC97E] focus:border-[#AFC97E]">
                </div>

                <button type="submit" class="bg-[#AFC97E] hover:bg-[#8fa866] text-white font-semibold px-6 py-2 rounded-lg shadow transition">
                    Simpan
                </button>
            </form>
        </div>
    </section>
</main>
@endsection