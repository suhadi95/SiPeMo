@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Final Draft Modul
    </h2>
@endsection

@section('content')
<div class="py-4 sm:py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Info Card -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-purple-100 mb-4 sm:mb-6">
            <div class="p-4 sm:p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-purple-800">
                            Informasi Final Draft
                        </h3>
                        <div class="mt-2 text-xs sm:text-sm text-purple-700">
                            <p>Final draft akan divalidasi oleh LPM. Pastikan semua 6 tahap penyusunan sudah divalidasi sebelum mengupload final draft.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($finalDraft)
            <!-- Final Draft Status Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-gray-200">
                <div class="p-4 sm:p-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                        <div class="flex-1">
                            <h3 class="text-base sm:text-lg font-medium text-gray-900">{{ $finalDraft->judul_modul }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $finalDraft->mataKuliah->nama_mata_kuliah }}</p>
                            <p class="text-xs sm:text-sm text-gray-500">Uploaded: {{ $finalDraft->uploaded_at->format('d M Y H:i') }}</p>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center space-y-3 sm:space-y-0 sm:space-x-4">
                            <!-- Status Badge -->
                            @if($finalDraft->status === 'pending_review')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 self-start">
                                    Menunggu Reviewer
                                </span>
                            @elseif($finalDraft->status === 'rejected_by_reviewer')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 self-start">
                                    Ditolak Reviewer
                                </span>
                            @elseif($finalDraft->status === 'approved_by_reviewer')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 self-start">
                                    Lolos Reviewer (Menunggu LPM)
                                </span>
                            @elseif($finalDraft->status === 'approved')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 self-start">
                                    Disetujui
                                </span>
                            @elseif($finalDraft->status === 'rejected')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 self-start">
                                    Ditolak LPM
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 self-start">
                                    {{ $finalDraft->status }}
                                </span>
                            @endif

                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                                <a href="{{ route('penyusun.final-draft.show', $finalDraft) }}" class="inline-flex items-center justify-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Lihat Detail
                                </a>
                                <a href="{{ route('penyusun.final-draft.download', $finalDraft) }}" class="inline-flex items-center justify-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download
                                </a>
                                @if($finalDraft->status === 'rejected' || $finalDraft->status === 'rejected_by_reviewer')
                                    <a href="{{ route('penyusun.final-draft.create') }}" class="inline-flex items-center justify-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                        </svg>
                                        Upload Ulang
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Validation Status -->
                    <div class="mt-4 sm:mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Reviewer Validation -->
                        <div class="border rounded-lg p-3 sm:p-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Validasi Reviewer</h4>
                            @if(in_array($finalDraft->status, ['approved_by_reviewer', 'approved', 'rejected']))
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-xs sm:text-sm text-green-700">Sudah divalidasi</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Oleh: {{ $finalDraft->reviewerValidator->name ?? 'Reviewer' }}</p>
                                @if($finalDraft->reviewer_validated_at)
                                    <p class="text-xs text-gray-500">{{ $finalDraft->reviewer_validated_at->format('d M Y H:i') }}</p>
                                @endif
                            @elseif($finalDraft->status === 'rejected_by_reviewer')
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-xs sm:text-sm text-red-700">Ditolak oleh Reviewer</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Oleh: {{ $finalDraft->reviewerValidator->name ?? 'Reviewer' }}</p>
                                @if($finalDraft->reviewer_validated_at)
                                    <p class="text-xs text-gray-500">{{ $finalDraft->reviewer_validated_at->format('d M Y H:i') }}</p>
                                @endif
                            @else
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-xs sm:text-sm text-yellow-700">Menunggu validasi Reviewer</span>
                                </div>
                            @endif
                        </div>

                        <!-- LPM Validation -->
                        <div class="border rounded-lg p-3 sm:p-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Validasi LPM</h4>
                            @if($finalDraft->isLpmValidated() && $finalDraft->status === 'approved')
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-xs sm:text-sm text-green-700">Sudah disetujui LPM</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Oleh: {{ $finalDraft->lpmValidator->name ?? 'LPM' }}</p>
                                <p class="text-xs text-gray-500">{{ $finalDraft->lpm_validated_at->format('d M Y H:i') }}</p>
                            @elseif($finalDraft->isLpmValidated() && $finalDraft->status === 'rejected')
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-xs sm:text-sm text-red-700">Ditolak LPM</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Oleh: {{ $finalDraft->lpmValidator->name ?? 'LPM' }}</p>
                                <p class="text-xs text-gray-500">{{ $finalDraft->lpm_validated_at->format('d M Y H:i') }}</p>
                            @else
                                <div class="flex items-center">
                                    @if(in_array($finalDraft->status, ['approved_by_reviewer']))
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-xs sm:text-sm text-yellow-700">Menunggu validasi LPM</span>
                                    @else
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-xs sm:text-sm text-gray-500">Terkunci (Menunggu Reviewer)</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($finalDraft->catatan_reviewer || $finalDraft->catatan_lpm)
                        <div class="mt-4 sm:mt-6 space-y-3">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Catatan Validasi</h4>
                            @if($finalDraft->catatan_reviewer)
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                    <p class="text-xs sm:text-sm text-blue-800"><strong>Reviewer:</strong> {{ $finalDraft->catatan_reviewer }}</p>
                                </div>
                            @endif
                            @if($finalDraft->catatan_lpm)
                                <div class="bg-purple-50 border border-purple-200 rounded-lg p-3">
                                    <p class="text-xs sm:text-sm text-purple-800"><strong>LPM:</strong> {{ $finalDraft->catatan_lpm }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- No Final Draft Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-gray-200">
                <div class="p-4 sm:p-6 text-center">
                    <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada final draft</h3>
                    <p class="mt-1 text-xs sm:text-sm text-gray-500 px-4">Upload final draft modul Anda untuk memulai proses validasi.</p>
                    <div class="mt-4 sm:mt-6">
                        <a href="{{ route('penyusun.final-draft.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 w-full sm:w-auto">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Upload Final Draft
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
