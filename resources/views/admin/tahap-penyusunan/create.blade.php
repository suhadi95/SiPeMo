@extends('layouts.app')

@section('header')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Periode Tahap Penyusunan') }}
        </h2>
    </div>
</div>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <form method="POST" action="{{ route('admin.tahap-penyusunan.store') }}">
                    @csrf
                    
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Periode</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-3">
                                <label for="nama_periode" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Periode
                                </label>
                                <input type="text" 
                                       id="nama_periode" 
                                       name="nama_periode" 
                                       value="{{ old('nama_periode') }}"
                                       placeholder="Contoh: Periode Semester Ganjil 2024/2025"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                                @error('nama_periode')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Mulai
                                </label>
                                <input type="date" 
                                       id="tanggal_mulai" 
                                       name="tanggal_mulai" 
                                       value="{{ old('tanggal_mulai') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                                @error('tanggal_mulai')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Selesai
                                </label>
                                <input type="date" 
                                       id="tanggal_selesai" 
                                       name="tanggal_selesai" 
                                       value="{{ old('tanggal_selesai') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                                @error('tanggal_selesai')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Tahap</h3>
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="text-sm text-blue-800 mb-3">
                                <strong>Catatan:</strong> Sistem akan otomatis membuat tahap penyusunan berdasarkan ketentuan SKS mata kuliah:
                            </p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Ketentuan untuk 2 SKS -->
                                <div class="bg-white p-3 rounded border border-blue-200">
                                    <h4 class="text-sm font-semibold text-blue-800 mb-2">Mata Kuliah 2 SKS</h4>
                                    <p class="text-xs text-blue-700 mb-2"><strong>Total:</strong> 4 tahap (6 modul)</p>
                                    <p class="text-xs text-blue-700 mb-2"><strong>Pembagian:</strong> 1 modul per tahap</p>
                                    <ul class="text-xs text-blue-600 list-disc list-inside space-y-1">
                                        <li>Tahap 1: Modul 1 dan 2</li>
                                        <li>Tahap 2: Modul 3 dan 4</li>
                                        <li>Tahap 3: Modul 5</li>
                                        <li>Tahap 4: Modul 6</li>
                                    </ul>
                                </div>

                                <!-- Ketentuan untuk 3 SKS -->
                                <div class="bg-white p-3 rounded border border-blue-200">
                                    <h4 class="text-sm font-semibold text-blue-800 mb-2">Mata Kuliah 3 SKS</h4>
                                    <p class="text-xs text-blue-700 mb-2"><strong>Total:</strong> 4 tahap (9 modul)</p>
                                    <p class="text-xs text-blue-700 mb-2"><strong>Pembagian:</strong></p>
                                    <ul class="text-xs text-blue-600 list-disc list-inside space-y-1">
                                        <li>Tahap 1: Modul 1,2 dan 3</li>
                                        <li>Tahap 2: Modul 4 dan 5</li>
                                        <li>Tahap 3: Modul 6 dan 7</li>
                                        <li>Tahap 4: Modul 8 dan 9</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="mt-3 p-2 bg-yellow-50 border border-yellow-200 rounded">
                                <p class="text-xs text-yellow-800">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    <strong>Perhatian:</strong> Periode ini akan berlaku untuk semua penyusun yang telah disetujui. 
                                    Jumlah tahap yang dibuat akan disesuaikan dengan SKS mata kuliah masing-masing penyusun.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('admin.tahap-penyusunan.index') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Batal
                        </a>
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Buat Periode Tahap Penyusunan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
