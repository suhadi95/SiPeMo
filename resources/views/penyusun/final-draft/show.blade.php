@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Detail Final Draft Modul
    </h2>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Card -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-gray-200 mb-6">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">{{ $finalDraft->judul_modul }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $finalDraft->mataKuliah->nama_mata_kuliah }}</p>
                        <p class="text-sm text-gray-500">Uploaded: {{ $finalDraft->uploaded_at->format('d M Y H:i') }}</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Status Badge -->
                        @if($finalDraft->status === 'pending')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Menunggu Validasi
                            </span>
                        @elseif($finalDraft->status === 'approved')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Disetujui
                            </span>
                        @elseif($finalDraft->status === 'rejected')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Ditolak
                            </span>
                        @endif

                        <!-- Download Button -->
                        <a href="{{ route('penyusun.final-draft.download', $finalDraft) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column: Details -->
            <div class="space-y-6">
                <!-- Description -->
                @if($finalDraft->deskripsi_modul)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-gray-200">
                        <div class="p-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-3">Deskripsi Modul</h4>
                            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $finalDraft->deskripsi_modul }}</p>
                        </div>
                    </div>
                @endif

                <!-- File Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-gray-200">
                    <div class="p-6">
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
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Right Column: Validation Status -->
            <div class="space-y-6">
                <!-- LPM Validation -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-gray-200">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-lg font-medium text-gray-900">Validasi LPM</h4>
                            </div>
                        </div>

                        @if($finalDraft->isLpmValidated())
                            <div class="flex items-center mb-3">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm font-medium text-green-700">Sudah divalidasi</span>
                            </div>
                            <div class="text-sm text-gray-600">
                                <p><strong>Oleh:</strong> {{ $finalDraft->lpmValidator->name ?? 'LPM' }}</p>
                                <p><strong>Tanggal:</strong> {{ $finalDraft->lpm_validated_at->format('d M Y H:i') }}</p>
                            </div>
                        @else
                            <div class="flex items-center mb-3">
                                <svg class="w-5 h-5 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm font-medium text-yellow-700">Menunggu validasi</span>
                            </div>
                        @endif

                        @if($finalDraft->catatan_lpm)
                            <div class="mt-4 p-3 bg-purple-50 border border-purple-200 rounded-lg">
                                <h5 class="text-sm font-medium text-purple-800 mb-1">Catatan LPM</h5>
                                <p class="text-sm text-purple-700">{{ $finalDraft->catatan_lpm }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <x-final-draft-activity-log :logs="$finalDraft->activityLogs" viewer="penyusun" />
        </div>

        <!-- Back Button -->
        <div class="mt-6 flex justify-end">
            <a href="{{ route('penyusun.final-draft.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>
</div>
@endsection
