@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $existingPublication && $existingPublication->status === 'rejected' ? 'Upload Ulang Publikasi Modul' : 'Upload Publikasi Modul' }}
    </h2>
@endsection

@section('content')
<div class="py-4 sm:py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($existingPublication && $existingPublication->status === 'rejected')
            <!-- Upload Ulang Info -->
            <div class="bg-gradient-to-r from-red-50 to-pink-50 overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-red-200 mb-4 sm:mb-6">
                <div class="p-4 sm:p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-red-900 mb-3">
                                Publikasi Sebelumnya Ditolak - Upload Ulang
                            </h3>
                            @if($existingPublication->catatan_admin)
                                <div class="mb-4 p-4 bg-red-100 border border-red-200 rounded-lg">
                                    <h4 class="text-sm font-medium text-red-800 mb-2">Catatan Admin:</h4>
                                    <p class="text-sm text-red-700">{{ $existingPublication->catatan_admin }}</p>
                                </div>
                            @endif
                            <p class="text-sm text-red-700">Silakan perbaiki sesuai catatan admin dan upload ulang publikasi Anda.</p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Progress Indicator -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-green-200 mb-4 sm:mb-6">
                <div class="p-4 sm:p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-green-900 mb-3">
                                Final Draft Disetujui - Siap Publikasi!
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                            <div class="flex items-center space-x-2 sm:space-x-3">
                                <div class="flex-shrink-0 w-6 h-6 sm:w-8 sm:h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 sm:w-5 sm:h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs sm:text-sm font-medium text-green-900 truncate">Final Draft</p>
                                    <p class="text-xs text-green-700">✓ Disetujui</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 sm:space-x-3">
                                <div class="flex-shrink-0 w-6 h-6 sm:w-8 sm:h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <span class="text-xs sm:text-sm font-semibold text-white">2</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs sm:text-sm font-medium text-blue-900 truncate">Upload File</p>
                                    <p class="text-xs text-blue-700">Sedang Berlangsung</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 sm:space-x-3">
                                <div class="flex-shrink-0 w-6 h-6 sm:w-8 sm:h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                    <span class="text-xs sm:text-sm font-semibold text-gray-600">3</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Validasi Admin</p>
                                    <p class="text-xs text-gray-500">Menunggu</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 sm:space-x-3">
                                <div class="flex-shrink-0 w-6 h-6 sm:w-8 sm:h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                    <span class="text-xs sm:text-sm font-semibold text-gray-600">4</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Publikasi</p>
                                    <p class="text-xs text-gray-500">Menunggu</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 p-4 bg-green-100 rounded-lg">
                            <p class="text-sm text-green-800">
                                <strong>Modul:</strong> "{{ $finalDraft->judul_modul }}" sudah disetujui oleh LPM. 
                                Sekarang Anda dapat melanjutkan ke tahap publikasi dengan mengupload file yang diperlukan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Panduan Upload -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-blue-100 mb-4 sm:mb-6">
            <div class="p-4 sm:p-6">
                <div class="flex items-center mb-3 sm:mb-4">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900">Panduan Upload Publikasi</h3>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                    <div class="border border-blue-200 rounded-lg p-3 sm:p-4">
                        <div class="flex items-center mb-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h4 class="text-xs sm:text-sm font-medium text-gray-900">Final Modul</h4>
                        </div>
                        <p class="text-xs text-gray-600">Format: PDF, DOC, DOCX</p>
                        <p class="text-xs text-gray-600">Ukuran: Maksimal 25MB</p>
                    </div>
                    
                    <div class="border border-green-200 rounded-lg p-3 sm:p-4">
                        <div class="flex items-center mb-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h4 class="text-xs sm:text-sm font-medium text-gray-900">Sertifikat HKI</h4>
                        </div>
                        <p class="text-xs text-gray-600">Format: PDF, JPG, JPEG, PNG</p>
                        <p class="text-xs text-gray-600">Ukuran: Maksimal 10MB</p>
                        <p class="text-xs text-gray-600 mt-1 font-medium text-green-700">📄 Sertifikat HKI dan Bukti Pembayaran HKI dalam 1 file PDF</p>
                    </div>
                    
                    <div class="border border-purple-200 rounded-lg p-3 sm:p-4 sm:col-span-2 lg:col-span-1">
                        <div class="flex items-center mb-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            <h4 class="text-xs sm:text-sm font-medium text-gray-900">Informasi Rekening</h4>
                        </div>
                        <p class="text-xs text-gray-600">Nama bank, nomor rekening</p>
                        <p class="text-xs text-gray-600">Nama pemilik sesuai buku tabungan</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-4 sm:p-6">
                <form method="POST" action="{{ route('penyusun.publication.store', $penyusunApplication) }}" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Informasi Modul -->
                    <div class="mb-4 sm:mb-6">
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-3 sm:mb-4">Informasi Modul</h3>
                        
                        <div class="grid grid-cols-1 gap-4 sm:gap-6">
                            <div>
                                <label for="judul_modul" class="block text-sm font-medium text-gray-700">Judul Modul</label>
                                <input type="text" name="judul_modul" id="judul_modul" 
                                       value="{{ old('judul_modul', $existingPublication->judul_modul ?? $finalDraft->judul_modul) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       required>
                                @error('judul_modul')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="deskripsi_modul" class="block text-sm font-medium text-gray-700">Deskripsi Modul</label>
                                <textarea name="deskripsi_modul" id="deskripsi_modul" rows="3"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('deskripsi_modul', $existingPublication->deskripsi_modul ?? $finalDraft->deskripsi_modul) }}</textarea>
                                @error('deskripsi_modul')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- File Upload -->
                    <div class="mb-4 sm:mb-6">
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-3 sm:mb-4 flex items-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            Upload File Publikasi
                        </h3>
                        
                        <div class="grid grid-cols-1 gap-4 sm:gap-6">
                            <!-- Final Modul Upload -->
                            <div class="border-2 border-dashed border-blue-300 rounded-lg p-4 sm:p-6 hover:border-blue-400 transition-colors">
                                <div class="text-center">
                                    <svg class="mx-auto h-8 w-8 sm:h-12 sm:w-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <div class="mt-3 sm:mt-4">
                                        <label for="final_modul_file" class="cursor-pointer">
                                            <span class="mt-2 block text-xs sm:text-sm font-medium text-gray-900">Final Modul</span>
                                            <span class="mt-1 block text-xs sm:text-sm text-gray-500">Klik untuk memilih file atau drag & drop</span>
                                        </label>
                                        <input type="file" name="final_modul_file" id="final_modul_file" 
                                               accept=".pdf,.doc,.docx"
                                               class="sr-only"
                                               required>
                                    </div>
                                    <div class="mt-2 text-xs text-gray-500">
                                        Format: PDF, DOC, DOCX • Maksimal: 25MB
                                    </div>
                                </div>
                                @error('final_modul_file')
                                    <p class="mt-2 text-sm text-red-600 text-center">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Sertifikat HKI Upload -->
                            <div class="border-2 border-dashed border-green-300 rounded-lg p-4 sm:p-6 hover:border-green-400 transition-colors">
                                <div class="text-center">
                                    <svg class="mx-auto h-8 w-8 sm:h-12 sm:w-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div class="mt-3 sm:mt-4">
                                        <label for="sertifikat_hki_file" class="cursor-pointer">
                                            <span class="mt-2 block text-xs sm:text-sm font-medium text-gray-900">Sertifikat HKI</span>
                                            <span class="mt-1 block text-xs sm:text-sm text-gray-500">Klik untuk memilih file atau drag & drop</span>
                                        </label>
                                        <input type="file" name="sertifikat_hki_file" id="sertifikat_hki_file" 
                                               accept=".pdf,.jpg,.jpeg,.png"
                                               class="sr-only"
                                               required>
                                    </div>
                                    <div class="mt-2 text-xs text-gray-500">
                                        Format: PDF, JPG, JPEG, PNG • Maksimal: 10MB
                                    </div>
                                    <div class="mt-2 text-xs text-green-600 font-medium">
                                        📄 Sertifikat HKI dan Bukti Pembayaran HKI dalam 1 file PDF
                                    </div>
                                </div>
                                @error('sertifikat_hki_file')
                                    <p class="mt-2 text-sm text-red-600 text-center">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Pribadi -->
                    <div class="mb-4 sm:mb-6">
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-3 sm:mb-4 flex items-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-indigo-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Informasi Pribadi
                        </h3>
                        
                        <div class="grid grid-cols-1 gap-4 sm:gap-6">
                            <div>
                                <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">
                                    <span class="flex items-center">
                                        NIK (Nomor Induk Kependudukan)
                                        <span class="ml-1 text-red-500">*</span>
                                    </span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="nik" id="nik" 
                                           value="{{ old('nik', $existingPublication->nik ?? '') }}"
                                           placeholder="Masukkan NIK 16 digit"
                                           maxlength="16"
                                           pattern="[0-9]{16}"
                                           class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm pl-10"
                                           required>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('nik')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">NIK harus terdiri dari 16 digit angka</p>
                            </div>

                            <div>
                                <label for="npwp" class="block text-sm font-medium text-gray-700 mb-2">
                                    <span class="flex items-center">
                                        NPWP (Nomor Pokok Wajib Pajak)
                                        <span class="ml-1 text-red-500">*</span>
                                    </span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="npwp" id="npwp" 
                                           value="{{ old('npwp', $existingPublication->npwp ?? '') }}"
                                           placeholder="Masukkan NPWP 15 atau 16 digit angka"
                                           maxlength="16"
                                           pattern="[0-9]{15,16}"
                                           class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm pl-10"
                                           required>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('npwp')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">NPWP harus terdiri dari 15 atau 16 digit angka (tanpa titik atau strip)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Rekening -->
                    <div class="mb-4 sm:mb-6">
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-3 sm:mb-4 flex items-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            Informasi Rekening Bank
                        </h3>
                        
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-3 sm:p-4 mb-3 sm:mb-4">
                            <div class="flex items-start">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h4 class="text-xs sm:text-sm font-medium text-purple-900">Penting!</h4>
                                    <p class="text-xs sm:text-sm text-purple-700 mt-1">Pastikan nama pemilik rekening sesuai dengan nama di buku tabungan. Informasi ini akan digunakan untuk transfer royalti publikasi.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-4 sm:gap-6">
                            <div>
                                <label for="nama_bank" class="block text-sm font-medium text-gray-700 mb-2">
                                    <span class="flex items-center">
                                        Nama Bank
                                        <span class="ml-1 text-red-500">*</span>
                                    </span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="nama_bank" id="nama_bank" 
                                           value="{{ old('nama_bank', $existingPublication->nama_bank ?? '') }}"
                                           placeholder="Contoh: Bank Mandiri, BCA, BRI, dll"
                                           class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm pl-10"
                                           required>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('nama_bank')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="nomor_rekening" class="block text-sm font-medium text-gray-700 mb-2">
                                    <span class="flex items-center">
                                        Nomor Rekening
                                        <span class="ml-1 text-red-500">*</span>
                                    </span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="nomor_rekening" id="nomor_rekening" 
                                           value="{{ old('nomor_rekening', $existingPublication->nomor_rekening ?? '') }}"
                                           placeholder="Masukkan nomor rekening tanpa spasi"
                                           class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm pl-10"
                                           required>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('nomor_rekening')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="nama_pemilik_rekening" class="block text-sm font-medium text-gray-700 mb-2">
                                    <span class="flex items-center">
                                        Nama Pemilik Rekening
                                        <span class="ml-1 text-red-500">*</span>
                                    </span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="nama_pemilik_rekening" id="nama_pemilik_rekening" 
                                           value="{{ old('nama_pemilik_rekening', $existingPublication->nama_pemilik_rekening ?? '') }}"
                                           placeholder="Nama sesuai dengan buku tabungan"
                                           class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm pl-10"
                                           required>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('nama_pemilik_rekening')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Pernyataan Kebenaran Data -->
                    <div class="mb-4 sm:mb-6">
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-3 sm:mb-4 flex items-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-amber-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Pernyataan Kebenaran Data
                        </h3>

                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 sm:p-4">
                            <div class="flex items-start space-x-3">
                                <input id="setuju_data_benar"
                                       name="setuju_data_benar"
                                       type="checkbox"
                                       value="1"
                                       class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                       {{ old('setuju_data_benar') ? 'checked' : '' }}
                                       required>
                                <label for="setuju_data_benar" class="text-xs sm:text-sm text-gray-700 leading-relaxed">
                                    <span class="font-medium text-gray-900">
                                        Saya menyatakan bahwa seluruh data dan dokumen yang saya kirimkan pada pengajuan publikasi ini adalah benar dan lengkap.
                                    </span>
                                    <span class="block mt-1 text-gray-600">
                                        Segala kesalahan, kekurangan, atau ketidaksesuaian data menjadi tanggung jawab saya sepenuhnya.
                                    </span>
                                </label>
                            </div>
                            @error('setuju_data_benar')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="bg-gray-50 border-t border-gray-200 -mx-4 sm:-mx-6 px-4 sm:px-6 py-3 sm:py-4 mt-4 sm:mt-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                            <div class="text-xs sm:text-sm text-gray-600 order-2 sm:order-1">
                                <span class="text-red-500">*</span> Wajib diisi
                            </div>
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-3 order-1 sm:order-2">
                                <a href="{{ route('penyusun.publication.index') }}" 
                                   class="inline-flex items-center justify-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition-colors text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Batal
                                </a>
                                <button type="submit" 
                                        id="submitPublicationBtn"
                                        class="inline-flex items-center justify-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors shadow-sm text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    Upload Publikasi
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const finalModulInput = document.getElementById('final_modul_file');
    const sertifikatHkiInput = document.getElementById('sertifikat_hki_file');
    const nikInput = document.getElementById('nik');
    const npwpInput = document.getElementById('npwp');
    const setujuDataBenar = document.getElementById('setuju_data_benar');
    const submitBtn = document.getElementById('submitPublicationBtn');

    function updateSubmitButtonState() {
        if (!submitBtn || !setujuDataBenar) return;
        submitBtn.disabled = !setujuDataBenar.checked;
    }

    if (setujuDataBenar) {
        updateSubmitButtonState();
        setujuDataBenar.addEventListener('change', updateSubmitButtonState);
    }
    
    // NIK validation - only numbers, max 16 digits
    if (nikInput) {
        nikInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove non-digits
            if (value.length > 16) {
                value = value.substring(0, 16);
            }
            e.target.value = value;
        });
        
        nikInput.addEventListener('blur', function(e) {
            if (e.target.value.length !== 16 && e.target.value.length > 0) {
                e.target.setCustomValidity('NIK harus terdiri dari 16 digit angka');
            } else {
                e.target.setCustomValidity('');
            }
        });
    }
    
    // NPWP validation - only numbers, no formatting
    if (npwpInput) {
        npwpInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove non-digits
            if (value.length > 16) {
                value = value.substring(0, 16);
            }
            e.target.value = value; // Keep only digits, no formatting
        });
        
        npwpInput.addEventListener('blur', function(e) {
            const digitsOnly = e.target.value.replace(/\D/g, '');
            if (digitsOnly.length < 15 || digitsOnly.length > 16) {
                e.target.setCustomValidity('NPWP harus terdiri dari 15 atau 16 digit angka');
            } else {
                e.target.setCustomValidity('');
            }
        });
    }
    
    finalModulInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            updateFileDisplay('final_modul_file', file);
        }
    });
    
    sertifikatHkiInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            updateFileDisplay('sertifikat_hki_file', file);
        }
    });
    
    function updateFileDisplay(inputId, file) {
        const container = document.querySelector(`#${inputId}`).closest('.border-dashed');
        const icon = container.querySelector('svg');
        const title = container.querySelector('.text-gray-900');
        const subtitle = container.querySelector('.text-gray-500');
        const formatInfo = container.querySelector('.text-xs');
        
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        `;
        icon.classList.remove('text-blue-400', 'text-green-400');
        icon.classList.add('text-green-500');
        
        title.textContent = file.name;
        subtitle.textContent = `Ukuran: ${formatFileSize(file.size)}`;
        formatInfo.textContent = `Format: ${file.type || 'Unknown'} • Terupload`;
        
        container.classList.remove('border-blue-300', 'border-green-300');
        container.classList.add('border-green-400', 'bg-green-50');
    }
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const finalModulFile = finalModulInput.files[0];
        const sertifikatHkiFile = sertifikatHkiInput.files[0];
        
        if (!finalModulFile) {
            e.preventDefault();
            alert('Silakan pilih file Final Modul');
            return;
        }
        
        if (!sertifikatHkiFile) {
            e.preventDefault();
            alert('Silakan pilih file Sertifikat HKI');
            return;
        }
        
        // Validate NIK
        if (nikInput && nikInput.value.replace(/\D/g, '').length !== 16) {
            e.preventDefault();
            alert('NIK harus terdiri dari 16 digit angka');
            nikInput.focus();
            return;
        }
        
        // Validate NPWP
        if (npwpInput) {
            const npwpDigits = npwpInput.value.replace(/\D/g, '');
            if (npwpDigits.length < 15 || npwpDigits.length > 16) {
                e.preventDefault();
                alert('NPWP harus terdiri dari 15 atau 16 digit angka');
                npwpInput.focus();
                return;
            }
        }

        if (setujuDataBenar && !setujuDataBenar.checked) {
            e.preventDefault();
            alert('Silakan centang pernyataan kebenaran data sebelum mengajukan publikasi.');
            setujuDataBenar.focus();
            return;
        }
        
        // Check file size - Final Modul: 25MB, Sertifikat HKI: 10MB
        const maxSizeFinalModul = 25 * 1024 * 1024;
        const maxSizeSertifikatHki = 10 * 1024 * 1024;
        if (finalModulFile.size > maxSizeFinalModul) {
            e.preventDefault();
            alert('Ukuran file Final Modul terlalu besar. Maksimal 25MB');
            return;
        }
        
        if (sertifikatHkiFile.size > maxSizeSertifikatHki) {
            e.preventDefault();
            alert('Ukuran file Sertifikat HKI terlalu besar. Maksimal 10MB');
            return;
        }
        
        submitBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Mengupload...
        `;
        submitBtn.disabled = true;
    });
});
</script>
@endsection
