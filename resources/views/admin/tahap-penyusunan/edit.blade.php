@extends('layouts.app')

@section('header')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Periode Penyusunan') }}
        </h2>
    </div>
</div>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Periode</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p><strong>Tahap:</strong> {{ $tahap->nama_tahap }}</p>
                        <p><strong>Deskripsi:</strong> {{ $tahap->deskripsi }}</p>
                        <p><strong>Status:</strong> 
                            <span class="{{ $tahap->is_active ? 'text-green-600' : 'text-gray-600' }}">
                                {{ $tahap->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </p>
                        <p class="text-sm text-gray-600 mt-2">
                            <strong>Catatan:</strong> Status aktif ditentukan berdasarkan tanggal mulai dan selesai. 
                            Untuk mengaktifkan tahap, gunakan tombol "Aktifkan" di halaman utama atau ubah tanggal mulai ke hari ini atau sebelumnya.
                        </p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.tahap-penyusunan.update', $tahap) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Periode Penyusunan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Mulai
                                </label>
                                <input type="date" 
                                       id="tanggal_mulai" 
                                       name="tanggal_mulai" 
                                       value="{{ old('tanggal_mulai', $tahap->tanggal_mulai->format('Y-m-d')) }}"
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
                                       value="{{ old('tanggal_selesai', $tahap->tanggal_selesai->format('Y-m-d')) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                                @error('tanggal_selesai')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
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
                            Update Tahap
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
