@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Tambah User
    </h2>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <!-- Form Card -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-gray-200">
            <div class="p-6">
                <form action="{{ route('admin.user.store') }}" method="POST">
                    @csrf
                    
                    <!-- Nama -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('name') border-red-300 @enderror"
                               placeholder="Masukkan nama lengkap"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('email') border-red-300 @enderror"
                               placeholder="Masukkan email"
                               required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" 
                               name="password" 
                               id="password"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('password') border-red-300 @enderror"
                               placeholder="Masukkan password"
                               required>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" 
                               name="password_confirmation" 
                               id="password_confirmation"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               placeholder="Konfirmasi password"
                               required>
                    </div>

                    <!-- Roles -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Role</label>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="is_admin" 
                                       value="1"
                                       {{ old('is_admin') ? 'checked' : '' }}
                                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">
                                    Admin
                                    <span class="text-gray-500">(Akses penuh ke sistem)</span>
                                </span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="is_penyusun" 
                                       value="1"
                                       {{ old('is_penyusun') ? 'checked' : '' }}
                                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">
                                    Penyusun
                                    <span class="text-gray-500">(Upload dan kelola modul)</span>
                                </span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="is_lpm" 
                                       value="1"
                                       {{ old('is_lpm') ? 'checked' : '' }}
                                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">
                                    LPM
                                    <span class="text-gray-500">(Validasi final draft)</span>
                                </span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="is_reviewer" 
                                       value="1"
                                       {{ old('is_reviewer') ? 'checked' : '' }}
                                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">
                                    Reviewer
                                    <span class="text-gray-500">(Review final draft mata kuliah)</span>
                                </span>
                            </label>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Pilih minimal satu role untuk user ini.</p>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end space-x-3">
                        <a href="{{ route('admin.user.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Batal
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
