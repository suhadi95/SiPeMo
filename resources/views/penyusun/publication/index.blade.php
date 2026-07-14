@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Publikasi Modul
    </h2>
@endsection

@section('content')
<div class="py-4 sm:py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Panduan Langkah-langkah -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-blue-200 mb-4 sm:mb-6">
            <div class="p-4 sm:p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <h3 class="text-base sm:text-lg font-semibold text-blue-900 mb-3">
                            Panduan Publikasi Modul
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-semibold text-blue-600">1</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-blue-900">Final Draft</p>
                                    <p class="text-xs text-blue-700">Disetujui Admin & LPM</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-semibold text-green-600">2</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-green-900">Upload File</p>
                                    <p class="text-xs text-green-700">Modul & Sertifikat HKI</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-semibold text-yellow-600">3</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-yellow-900">Validasi Admin</p>
                                    <p class="text-xs text-yellow-700">Review & Persetujuan</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-semibold text-purple-600">4</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-purple-900">Publikasi</p>
                                    <p class="text-xs text-purple-700">Modul Dipublikasikan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-blue-100 mb-4 sm:mb-6">
            <div class="p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">
                            Informasi Penting
                        </h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Pastikan final draft sudah disetujui oleh admin dan LPM sebelum upload publikasi</li>
                                <li>Siapkan file final modul (PDF/DOC/DOCX) dan sertifikat HKI (PDF/JPG/PNG)</li>
                                <li>Informasi rekening harus sesuai dengan nama di buku tabungan</li>
                                <li>Proses validasi publikasi membutuhkan waktu 1-3 hari kerja</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($penyusunApplications->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2 sm:mb-0">Daftar Modul Anda</h3>
                        <div class="text-sm text-gray-500">
                            Total: {{ $penyusunApplications->count() }} modul
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        @foreach($penyusunApplications as $index => $application)
                            @php
                                $finalDraft = $application->finalDrafts->first();
                                $publication = $application->publicationModuls->first();
                                $canPublish = $finalDraft && 
                                    (($finalDraft->status == 'approved') || 
                                     ($finalDraft->status == 'pending' && $finalDraft->isLpmValidated()));
                            @endphp
                            
                            <div class="border border-gray-200 rounded-lg p-4 sm:p-6 hover:shadow-md transition-shadow">
                                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-3">
                                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <span class="text-sm font-semibold text-blue-600">{{ $index + 1 }}</span>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <h4 class="text-base sm:text-lg font-medium text-gray-900 break-words">{{ $application->judul_bahan_ajar }}</h4>
                                                <p class="text-sm text-gray-600 break-words">{{ $application->mataKuliah->nama_mata_kuliah }} ({{ $application->mataKuliah->kode_mata_kuliah }})</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Progress Indicator -->
                                        <div class="mb-4">
                                            <div class="flex items-center space-x-2 mb-2">
                                                <span class="text-sm font-medium text-gray-700">Progress Publikasi:</span>
                                            </div>
                                            <!-- Mobile: Stack vertically -->
                                            <div class="block sm:hidden space-y-3">
                                                <!-- Step 1: Final Draft -->
                                                <div class="flex items-center space-x-2">
                                                    @if($finalDraft && (($finalDraft->status == 'approved') || ($finalDraft->status == 'pending' && $finalDraft->isLpmValidated())))
                                                        <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>
                                                        <span class="text-sm text-green-600 font-medium">Final Draft OK</span>
                                                    @elseif($finalDraft && $finalDraft->status == 'rejected')
                                                        <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center">
                                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>
                                                        <span class="text-sm text-red-600 font-medium">Final Draft Ditolak</span>
                                                    @elseif($finalDraft)
                                                        <div class="w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center">
                                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>
                                                        <span class="text-sm text-yellow-600 font-medium">Menunggu Validasi</span>
                                                    @else
                                                        <div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center">
                                                            <span class="text-xs text-gray-600">1</span>
                                                        </div>
                                                        <span class="text-sm text-gray-500">Belum Upload Final Draft</span>
                                                    @endif
                                                </div>
                                                
                                                <!-- Step 2: Upload Publication -->
                                                <div class="flex items-center space-x-2">
                                                    @if($publication)
                                                        @if($publication->status == 'approved')
                                                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                            <span class="text-sm text-green-600 font-medium">Publikasi Disetujui</span>
                                                        @elseif($publication->status == 'rejected')
                                                            <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center">
                                                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                            <span class="text-sm text-red-600 font-medium">Publikasi Ditolak</span>
                                                        @else
                                                            <div class="w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center">
                                                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                            <span class="text-sm text-yellow-600 font-medium">Menunggu Validasi</span>
                                                        @endif
                                                    @elseif($canPublish)
                                                        <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                                                            <span class="text-xs text-white font-bold">2</span>
                                                        </div>
                                                        <span class="text-sm text-blue-600 font-medium">Siap Upload</span>
                                                    @else
                                                        <div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center">
                                                            <span class="text-xs text-gray-600">2</span>
                                                        </div>
                                                        <span class="text-sm text-gray-500">Belum Tersedia</span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <!-- Desktop: Horizontal layout -->
                                            <div class="hidden sm:flex items-center space-x-2">
                                                <!-- Step 1: Final Draft -->
                                                <div class="flex items-center space-x-2">
                                                    @if($finalDraft && (($finalDraft->status == 'approved') || ($finalDraft->status == 'pending' && $finalDraft->isLpmValidated())))
                                                        <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>
                                                        <span class="text-sm text-green-600 font-medium">Final Draft OK</span>
                                                    @elseif($finalDraft && $finalDraft->status == 'rejected')
                                                        <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center">
                                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>
                                                        <span class="text-sm text-red-600 font-medium">Final Draft Ditolak</span>
                                                    @elseif($finalDraft)
                                                        <div class="w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center">
                                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>
                                                        <span class="text-sm text-yellow-600 font-medium">Menunggu Validasi</span>
                                                    @else
                                                        <div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center">
                                                            <span class="text-xs text-gray-600">1</span>
                                                        </div>
                                                        <span class="text-sm text-gray-500">Belum Upload Final Draft</span>
                                                    @endif
                                                </div>
                                                
                                                <div class="flex-1 h-1 bg-gray-200 rounded mx-2"></div>
                                                
                                                <!-- Step 2: Upload Publication -->
                                                <div class="flex items-center space-x-2">
                                                    @if($publication)
                                                        @if($publication->status == 'approved')
                                                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                            <span class="text-sm text-green-600 font-medium">Publikasi Disetujui</span>
                                                        @elseif($publication->status == 'rejected')
                                                            <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center">
                                                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                            <span class="text-sm text-red-600 font-medium">Publikasi Ditolak</span>
                                                        @else
                                                            <div class="w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center">
                                                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                            <span class="text-sm text-yellow-600 font-medium">Menunggu Validasi</span>
                                                        @endif
                                                    @elseif($canPublish)
                                                        <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                                                            <span class="text-xs text-white font-bold">2</span>
                                                        </div>
                                                        <span class="text-sm text-blue-600 font-medium">Siap Upload</span>
                                                    @else
                                                        <div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center">
                                                            <span class="text-xs text-gray-600">2</span>
                                                        </div>
                                                        <span class="text-sm text-gray-500">Belum Tersedia</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Action Button -->
                                    <div class="flex-shrink-0 mt-4 sm:mt-0 sm:ml-4">
                                        @if($publication)
                                            <div class="flex flex-col sm:flex-col space-y-2">
                                                <a href="{{ route('penyusun.publication.show', $publication) }}" 
                                                   class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors w-full sm:w-auto">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    Lihat Detail
                                                </a>
                                                @if($publication->status === 'rejected')
                                                    <a href="{{ route('penyusun.publication.create', $application) }}" 
                                                       class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors w-full sm:w-auto">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                                        </svg>
                                                        Upload Ulang
                                                    </a>
                                                @endif
                                            </div>
                                        @elseif($canPublish)
                                            <a href="{{ route('penyusun.publication.create', $application) }}" 
                                               class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors w-full sm:w-auto">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                </svg>
                                                Upload Publikasi
                                            </a>
                                        @else
                                            <div class="text-center">
                                                <div class="text-sm text-gray-500 mb-1">Tidak tersedia</div>
                                                <div class="text-xs text-gray-400">Final draft belum disetujui</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-center">
                    <div class="text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada aplikasi</h3>
                        <p class="mt-1 text-sm text-gray-500">Belum ada aplikasi penyusunan yang disetujui.</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Bantuan dan FAQ -->
        <div class="mt-6 sm:mt-8 grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            <!-- Bantuan Cepat -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6">
                    <div class="flex items-center mb-4">
                        <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Bantuan Cepat</h3>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="border-l-4 border-blue-500 pl-4">
                            <h4 class="text-sm font-medium text-gray-900">Final Draft Belum Disetujui?</h4>
                            <p class="text-sm text-gray-600 mt-1">Pastikan Anda sudah mengupload final draft dan menunggu validasi dari admin dan LPM.</p>
                        </div>
                        
                        <div class="border-l-4 border-green-500 pl-4">
                            <h4 class="text-sm font-medium text-gray-900">File Upload Bermasalah?</h4>
                            <p class="text-sm text-gray-600 mt-1">Pastikan format file sesuai (PDF/DOC/DOCX untuk modul, PDF/JPG/PNG untuk HKI) dan ukuran maksimal 10MB.</p>
                        </div>
                        
                        <div class="border-l-4 border-yellow-500 pl-4">
                            <h4 class="text-sm font-medium text-gray-900">Status Menunggu Validasi?</h4>
                            <p class="text-sm text-gray-600 mt-1">Proses validasi membutuhkan waktu 1-3 hari kerja. Mohon bersabar dan tunggu notifikasi.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6">
                    <div class="flex items-center mb-4">
                        <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">FAQ</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Kapan saya bisa upload publikasi?</h4>
                            <p class="text-sm text-gray-600 mt-1">Setelah final draft Anda disetujui oleh admin dan LPM, tombol "Upload Publikasi" akan muncul.</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">File apa saja yang diperlukan?</h4>
                            <p class="text-sm text-gray-600 mt-1">Final modul (PDF/DOC/DOCX), sertifikat HKI (PDF/JPG/PNG), dan informasi rekening bank.</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Bagaimana jika publikasi ditolak?</h4>
                            <p class="text-sm text-gray-600 mt-1">Anda akan menerima catatan dari admin. Perbaiki sesuai saran dan upload ulang.</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Kapan modul akan dipublikasikan?</h4>
                            <p class="text-sm text-gray-600 mt-1">Setelah publikasi disetujui admin, modul akan segera dipublikasikan di platform.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
