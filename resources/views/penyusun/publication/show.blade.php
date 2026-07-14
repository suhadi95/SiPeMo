@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Detail Publikasi Modul
    </h2>
@endsection

@section('content')
<div class="py-3 sm:py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Status Card -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4 sm:mb-6">
            <div class="p-3 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between">
                    <div class="flex-1">
                        <div class="flex items-start space-x-2 sm:space-x-3 mb-3">
                            <div class="flex-shrink-0 w-7 h-7 sm:w-10 sm:h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-base sm:text-xl font-semibold text-gray-900 break-words leading-tight">{{ $publicationModul->judul_modul }}</h3>
                                <p class="text-xs sm:text-sm text-gray-600 break-words mt-1">{{ $publicationModul->penyusunApplication->mataKuliah->nama_mata_kuliah }} ({{ $publicationModul->penyusunApplication->mataKuliah->kode_mata_kuliah }})</p>
                            </div>
                        </div>
                        
                        <!-- Progress Indicator -->
                        <div class="mb-4">
                            <div class="flex items-center space-x-2 mb-2">
                                <span class="text-xs sm:text-sm font-medium text-gray-700">Status Publikasi:</span>
                            </div>
                            <!-- Mobile: Vertical layout -->
                            <div class="block sm:hidden space-y-3">
                                <!-- Step 1: Final Draft -->
                                <div class="flex items-center space-x-2">
                                    <div class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-green-600 font-medium">Final Draft OK</span>
                                </div>
                                
                                <!-- Step 2: Upload Publication -->
                                <div class="flex items-center space-x-2">
                                    <div class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-green-600 font-medium">File Terupload</span>
                                </div>
                                
                                <!-- Step 3: Admin Validation -->
                                <div class="flex items-center space-x-2">
                                    @if($publicationModul->status == 'approved')
                                        <div class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <span class="text-xs text-green-600 font-medium">Disetujui Admin</span>
                                    @elseif($publicationModul->status == 'rejected')
                                        <div class="w-5 h-5 bg-red-500 rounded-full flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <span class="text-xs text-red-600 font-medium">Ditolak Admin</span>
                                    @else
                                        <div class="w-5 h-5 bg-yellow-500 rounded-full flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <span class="text-xs text-yellow-600 font-medium">Menunggu Validasi</span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Desktop: Horizontal layout -->
                            <div class="hidden sm:flex items-center space-x-2">
                                <!-- Step 1: Final Draft -->
                                <div class="flex items-center space-x-2">
                                    <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm text-green-600 font-medium">Final Draft OK</span>
                                </div>
                                
                                <div class="flex-1 h-1 bg-gray-200 rounded mx-2"></div>
                                
                                <!-- Step 2: Upload Publication -->
                                <div class="flex items-center space-x-2">
                                    <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm text-green-600 font-medium">File Terupload</span>
                                </div>
                                
                                <div class="flex-1 h-1 bg-gray-200 rounded mx-2"></div>
                                
                                <!-- Step 3: Admin Validation -->
                                <div class="flex items-center space-x-2">
                                    @if($publicationModul->status == 'approved')
                                        <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <span class="text-sm text-green-600 font-medium">Disetujui Admin</span>
                                    @elseif($publicationModul->status == 'rejected')
                                        <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <span class="text-sm text-red-600 font-medium">Ditolak Admin</span>
                                    @else
                                        <div class="w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <span class="text-sm text-yellow-600 font-medium">Menunggu Validasi</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex-shrink-0 mt-3 sm:mt-0 sm:ml-4">
                        @if($publicationModul->status == 'approved')
                            <div class="inline-flex items-center px-2 py-1.5 sm:px-4 sm:py-2 bg-green-100 text-green-800 rounded-lg">
                                <svg class="w-3 h-3 sm:w-5 sm:h-5 mr-1 sm:mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-xs sm:text-base font-medium">Publikasi Disetujui</span>
                            </div>
                        @elseif($publicationModul->status == 'rejected')
                            <div class="inline-flex items-center px-2 py-1.5 sm:px-4 sm:py-2 bg-red-100 text-red-800 rounded-lg">
                                <svg class="w-3 h-3 sm:w-5 sm:h-5 mr-1 sm:mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-xs sm:text-base font-medium">Publikasi Ditolak</span>
                            </div>
                        @else
                            <div class="inline-flex items-center px-2 py-1.5 sm:px-4 sm:py-2 bg-yellow-100 text-yellow-800 rounded-lg">
                                <svg class="w-3 h-3 sm:w-5 sm:h-5 mr-1 sm:mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-xs sm:text-base font-medium">Menunggu Validasi</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-6">
            <!-- Informasi Modul -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-3 sm:p-6">
                    <h3 class="text-sm sm:text-lg font-medium text-gray-900 mb-3 sm:mb-4">Informasi Modul</h3>
                    
                    <dl class="space-y-2 sm:space-y-3">
                        <div>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500">Judul Modul</dt>
                            <dd class="text-xs sm:text-sm text-gray-900 break-words">{{ $publicationModul->judul_modul }}</dd>
                        </div>
                        
                        @if($publicationModul->deskripsi_modul)
                        <div>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500">Deskripsi</dt>
                            <dd class="text-xs sm:text-sm text-gray-900 break-words">{{ $publicationModul->deskripsi_modul }}</dd>
                        </div>
                        @endif
                        
                        <div>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500">Mata Kuliah</dt>
                            <dd class="text-xs sm:text-sm text-gray-900 break-words">{{ $publicationModul->penyusunApplication->mataKuliah->nama_mata_kuliah }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500">Kode Mata Kuliah</dt>
                            <dd class="text-xs sm:text-sm text-gray-900 break-words">{{ $publicationModul->penyusunApplication->mataKuliah->kode_mata_kuliah }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500">Tanggal Upload</dt>
                            <dd class="text-xs sm:text-sm text-gray-900">{{ $publicationModul->uploaded_at->format('d M Y, H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Informasi Pribadi -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-3 sm:p-6">
                    <h3 class="text-sm sm:text-lg font-medium text-gray-900 mb-3 sm:mb-4">Informasi Pribadi</h3>
                    
                    <dl class="space-y-2 sm:space-y-3">
                        @if($publicationModul->nik)
                        <div>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500">NIK</dt>
                            <dd class="text-xs sm:text-sm text-gray-900 font-mono break-all">{{ $publicationModul->nik }}</dd>
                        </div>
                        @endif
                        
                        @if($publicationModul->npwp)
                        <div>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500">NPWP</dt>
                            <dd class="text-xs sm:text-sm text-gray-900 font-mono break-all">{{ $publicationModul->npwp }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-6 mt-3 sm:mt-6">
            <!-- Informasi Rekening -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-3 sm:p-6">
                    <h3 class="text-sm sm:text-lg font-medium text-gray-900 mb-3 sm:mb-4">Informasi Rekening</h3>
                    
                    <dl class="space-y-2 sm:space-y-3">
                        <div>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500">Nama Bank</dt>
                            <dd class="text-xs sm:text-sm text-gray-900 break-words">{{ $publicationModul->nama_bank }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500">Nomor Rekening</dt>
                            <dd class="text-xs sm:text-sm text-gray-900 break-words">{{ $publicationModul->nomor_rekening }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500">Nama Pemilik Rekening</dt>
                            <dd class="text-xs sm:text-sm text-gray-900 break-words">{{ $publicationModul->nama_pemilik_rekening }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- File Downloads -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-3 sm:mt-6">
            <div class="p-3 sm:p-6">
                <h3 class="text-sm sm:text-lg font-medium text-gray-900 mb-3 sm:mb-4 flex items-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    File Publikasi
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                    <!-- Final Modul -->
                    <div class="border border-blue-200 rounded-lg p-3 sm:p-4 hover:shadow-md transition-shadow">
                        <div class="space-y-2 sm:space-y-3">
                            <!-- Header dengan icon dan title -->
                            <div class="flex items-center space-x-2 sm:space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-xs sm:text-sm font-medium text-gray-900">Final Modul</h4>
                                </div>
                            </div>
                            
                            <!-- File name -->
                            <div class="pl-9 sm:pl-13">
                                <p class="text-xs sm:text-sm text-gray-500 break-words">{{ $publicationModul->final_modul_file_name }}</p>
                            </div>
                            
                            <!-- Download button -->
                            <div class="pl-9 sm:pl-13">
                                <a href="{{ route('penyusun.publication.download-final-modul', $publicationModul) }}" 
                                   class="inline-flex items-center justify-center w-full sm:w-auto px-3 py-2 sm:px-3 sm:py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-medium rounded-lg transition-colors touch-manipulation">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sertifikat HKI -->
                    <div class="border border-green-200 rounded-lg p-3 sm:p-4 hover:shadow-md transition-shadow">
                        <div class="space-y-2 sm:space-y-3">
                            <!-- Header dengan icon dan title -->
                            <div class="flex items-center space-x-2 sm:space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-xs sm:text-sm font-medium text-gray-900">Sertifikat HKI</h4>
                                </div>
                            </div>
                            
                            <!-- File name -->
                            <div class="pl-9 sm:pl-13">
                                <p class="text-xs sm:text-sm text-gray-500 break-words">{{ $publicationModul->sertifikat_hki_file_name }}</p>
                            </div>
                            
                            <!-- Download button -->
                            <div class="pl-9 sm:pl-13">
                                <a href="{{ route('penyusun.publication.download-sertifikat-hki', $publicationModul) }}" 
                                   class="inline-flex items-center justify-center w-full sm:w-auto px-3 py-2 sm:px-3 sm:py-2 bg-green-600 hover:bg-green-700 text-white text-xs sm:text-sm font-medium rounded-lg transition-colors touch-manipulation">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Validasi -->
        @if($publicationModul->isAdminValidated())
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-3 sm:mt-6">
            <div class="p-3 sm:p-6">
                <h3 class="text-sm sm:text-lg font-medium text-gray-900 mb-3 sm:mb-4">Status Validasi</h3>
                
                <dl class="space-y-2 sm:space-y-3">
                    <div>
                        <dt class="text-xs sm:text-sm font-medium text-gray-500">Status</dt>
                        <dd class="text-xs sm:text-sm text-gray-900">
                            @if($publicationModul->status == 'approved')
                                <span class="text-green-600 font-medium">Disetujui</span>
                            @elseif($publicationModul->status == 'rejected')
                                <span class="text-red-600 font-medium">Ditolak</span>
                            @else
                                <span class="text-yellow-600 font-medium">Menunggu Validasi</span>
                            @endif
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-xs sm:text-sm font-medium text-gray-500">Divalidasi Oleh</dt>
                        <dd class="text-xs sm:text-sm text-gray-900 break-words">{{ $publicationModul->validator->name ?? 'Admin' }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-xs sm:text-sm font-medium text-gray-500">Tanggal Validasi</dt>
                        <dd class="text-xs sm:text-sm text-gray-900">{{ $publicationModul->validated_at->format('d M Y, H:i') }}</dd>
                    </div>
                    
                    @if($publicationModul->catatan_admin)
                    <div>
                        <dt class="text-xs sm:text-sm font-medium text-gray-500">Catatan Admin</dt>
                        <dd class="text-xs sm:text-sm text-gray-900 break-words">{{ $publicationModul->catatan_admin }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mt-3 sm:mt-6 space-y-3 sm:space-y-0">
            <div class="text-xs sm:text-sm text-gray-500 order-2 sm:order-1">
                Terakhir diperbarui: {{ $publicationModul->updated_at->format('d M Y, H:i') }}
            </div>
            <a href="{{ route('penyusun.publication.index') }}" 
               class="inline-flex items-center justify-center px-4 py-2.5 sm:px-4 sm:py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm sm:text-base font-medium rounded-lg transition-colors order-1 sm:order-2 touch-manipulation">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar
            </a>
        </div>
    </div>
</div>
@endsection

