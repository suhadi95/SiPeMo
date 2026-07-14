@extends('layouts.app')

@section('header')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upload Modul') }}
        </h2>
    </div>
</div>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Tahap</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p><strong>Tahap:</strong> {{ $tahapPenyusunan->nama_tahap }}</p>
                        <p><strong>Deskripsi:</strong> {{ $tahapPenyusunan->deskripsi }}</p>
                        <p><strong>Periode:</strong> 
                            {{ \Carbon\Carbon::parse($tahapPenyusunan->tanggal_mulai)->format('d M Y') }} - 
                            {{ \Carbon\Carbon::parse($tahapPenyusunan->tanggal_selesai)->format('d M Y') }}
                        </p>
                        <p><strong>Status:</strong> 
                            <span class="text-green-600 font-medium">Aktif</span>
                        </p>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Modul</h3>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="text-sm text-blue-800 mb-2">
                            <strong>Catatan:</strong> Pada tahap ini, Anda akan mengupload modul untuk:
                        </p>
                        <ul class="text-sm text-blue-800 list-disc list-inside space-y-1">
                            @php
                                $modulStart = $tahapPenyusunan->getModulStart();
                                $modulEnd = $tahapPenyusunan->getModulEnd();
                            @endphp
                            @for($i = $modulStart; $i <= $modulEnd; $i++)
                                <li>Modul {{ $i }}</li>
                            @endfor
                        </ul>
                        <p class="text-sm text-blue-800 mt-2">
                            File yang sama akan digunakan untuk semua modul dalam tahap ini.
                        </p>
                    </div>
                </div>

                <form method="POST" action="{{ route('penyusun.modul.store', $tahapPenyusunan->tahap) }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="judul_modul" class="block text-sm font-medium text-gray-700 mb-2">
                            Judul Modul *
                        </label>
                        <input type="text" 
                               id="judul_modul" 
                               name="judul_modul" 
                               value="{{ old('judul_modul') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Masukkan judul modul"
                               required>
                        @error('judul_modul')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="deskripsi_modul" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi Modul
                        </label>
                        <textarea id="deskripsi_modul" 
                                  name="deskripsi_modul" 
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Masukkan deskripsi modul (opsional)">{{ old('deskripsi_modul') }}</textarea>
                        @error('deskripsi_modul')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="file_modul" class="block text-sm font-medium text-gray-700 mb-2">
                            File Modul *
                        </label>
                        <input type="file" 
                               id="file_modul" 
                               name="file_modul" 
                               accept=".doc,.docx"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               required>
                        <p class="mt-1 text-sm text-gray-500">
                            Format yang diperbolehkan: .doc, .docx (Maksimal 10MB)
                        </p>
                        @error('file_modul')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('penyusun.modul.index') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Batal
                        </a>
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Upload Modul
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection