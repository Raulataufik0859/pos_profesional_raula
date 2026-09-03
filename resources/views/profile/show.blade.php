@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
    <div class="p-6">

        {{-- Header --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Profil Saya</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola informasi akun Anda</p>
            </div>
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        @if (session('success'))
            <div class="mb-5 p-4 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 rounded-xl text-sm border border-emerald-200 dark:border-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-5 p-4 bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-xl text-sm border border-red-200 dark:border-red-800">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Kartu Info Profil --}}
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
                    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 h-24"></div>
                    <div class="px-6 pb-6 -mt-12 text-center">
                        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-600 text-white text-3xl font-bold shadow-lg ring-4 ring-white dark:ring-gray-800">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <h2 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $user->email }}</p>
                        <span class="inline-flex items-center mt-3 px-3 py-1 rounded-full text-xs font-medium
                            {{ optional($user->role)->name === 'admin'
                                ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300'
                                : 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' }}">
                            {{ ucfirst(optional($user->role)->name ?? 'User') }}
                        </span>
                    </div>

                    <div class="border-t border-gray-100 dark:border-gray-700 px-6 py-4 space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Bergabung</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $user->created_at?->translatedFormat('d M Y') ?? '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Terakhir Update</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $user->updated_at?->translatedFormat('d M Y H:i') ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Edit Profil --}}
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Informasi</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Perbarui data pribadi Anda di bawah ini</p>
                    </div>

                    <form action="{{ route('profile.update') }}" method="POST" class="p-6 space-y-5">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                placeholder="Masukkan nama lengkap">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                placeholder="email@contoh.com">
                        </div>

                        <div class="pt-2 border-t border-gray-100 dark:border-gray-700">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Ganti Password <span class="text-gray-400 font-normal">(opsional)</span></p>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                        Password Baru
                                    </label>
                                    <input type="password" name="password"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                        placeholder="Minimal 6 karakter">
                                    <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ingin mengganti password</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                        Konfirmasi Password Baru
                                    </label>
                                    <input type="password" name="password_confirmation"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                        placeholder="Ulangi password baru">
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                            <button type="submit"
                                class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition shadow-sm shadow-indigo-500/20">
                                Simpan Perubahan
                            </button>
                            <a href="{{ route('dashboard') }}"
                                class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-xl transition">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
