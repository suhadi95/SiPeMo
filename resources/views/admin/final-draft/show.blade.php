@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Monitoring Final Draft Modul
    </h2>
@endsection

@section('content')
<div class="py-4 sm:py-6">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Card -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-gray-200 mb-4 sm:mb-6">
            <div class="p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                    <div class="flex-1">
                        <h3 class="text-lg font-medium text-gray-900">{{ $finalDraft->judul_modul }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $finalDraft->penyusunApplication->nama_penyusun }} - {{ $finalDraft->mataKuliah->nama_mata_kuliah }}</p>
                        <p class="text-sm text-gray-500">Uploaded: {{ $finalDraft->uploaded_at->format('d M Y H:i') }}</p>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center space-y-3 sm:space-y-0 sm:space-x-4">
                        <!-- Status Badge -->
                        @if($finalDraft->status === 'pending')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 self-start">
                                Menunggu Validasi
                            </span>
                        @elseif($finalDraft->status === 'approved')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 self-start">
                                Disetujui
                            </span>
                        @elseif($finalDraft->status === 'rejected')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 self-start">
                                Ditolak
                            </span>
                        @endif

                        <!-- Download Button -->
                        <a href="{{ route('admin.final-draft.download', $finalDraft) }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full sm:w-auto">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download File
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            <!-- Left Column: Details -->
            <div class="lg:col-span-2 space-y-4 sm:space-y-6">
                <!-- Description -->
                @if($finalDraft->deskripsi_modul)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-gray-200">
                        <div class="p-4 sm:p-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-3">Deskripsi Modul</h4>
                            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $finalDraft->deskripsi_modul }}</p>
                        </div>
                    </div>
                @endif

                <!-- File Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-gray-200">
                    <div class="p-4 sm:p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-3">Informasi File</h4>
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-3 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nama File</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $finalDraft->file_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tanggal Upload</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $finalDraft->uploaded_at->format('d M Y H:i') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email Penyusun</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $finalDraft->penyusunApplication->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Jurusan</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $finalDraft->mataKuliah->jurusan->nama_jurusan }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Right Column: Monitoring Information -->
            <div class="space-y-4 sm:space-y-6">
                <!-- LPM Validation Status -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-gray-200">
                    <div class="p-4 sm:p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Status Validasi LPM</h4>
                        
                        @if($finalDraft->isLpmValidated())
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm font-medium text-green-700">Sudah divalidasi</span>
                            </div>
                            <div class="text-sm text-gray-600">
                                <p><strong>Oleh:</strong> {{ $finalDraft->lpmValidator->name ?? 'LPM' }}</p>
                                <p><strong>Tanggal:</strong> {{ $finalDraft->lpm_validated_at->format('d M Y H:i') }}</p>
                            </div>
                            
                            @if($finalDraft->catatan_lpm)
                                <div class="mt-4 p-3 bg-purple-50 border border-purple-200 rounded-lg">
                                    <h5 class="text-sm font-medium text-purple-800 mb-1">Catatan LPM</h5>
                                    <p class="text-sm text-purple-700">{{ $finalDraft->catatan_lpm }}</p>
                                </div>
                            @endif
                        @else
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm font-medium text-yellow-700">Menunggu validasi LPM</span>
                            </div>
                            <p class="text-sm text-gray-500">Final draft sedang menunggu validasi dari LPM.</p>
                        @endif
                    </div>
                </div>

                <!-- Progress Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-gray-200">
                    <div class="p-4 sm:p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Informasi Progres</h4>
                        
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Upload Final Draft</span>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-sm text-green-600">Selesai</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Validasi LPM</span>
                                <div class="flex items-center">
                                    @if($finalDraft->isLpmValidated())
                                        <svg class="w-4 h-4 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-sm text-green-600">Selesai</span>
                                    @else
                                        <svg class="w-4 h-4 text-yellow-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-sm text-yellow-600">Menunggu</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 sm:mt-6">
            <x-final-draft-activity-log :logs="$finalDraft->activityLogs" viewer="admin" />
        </div>

        <!-- Back Button -->
        <div class="mt-4 sm:mt-6 flex justify-center sm:justify-end">
            <a href="{{ route('admin.final-draft.index') }}" 
               class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full sm:w-auto">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar
            </a>
        </div>
    </div>
</div>
@endsection
