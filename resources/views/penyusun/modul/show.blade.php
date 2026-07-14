@extends('layouts.app')

@section('header')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Modul') }}
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
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Detail Modul</h3>
                        </div>
                        <div class="flex space-x-2">
                            @if($modul->file_path)
                                <a href="{{ route('penyusun.modul.download', $modul) }}" 
                                   class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Download File
                                </a>
                            @endif
                            <a href="{{ route('penyusun.modul.index') }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Kembali
                            </a>
                        </div>
                    </div>

                    <!-- Main Information Card -->
                    <div class="bg-gray-50 rounded-lg overflow-hidden">
                        <div class="flex flex-col lg:flex-row">
                            <!-- Left Side - Modul Information -->
                            <div class="flex-1 p-4">
                                <div class="space-y-3">
                                    <div>
                                        <h4 class="text-base font-semibold text-gray-900">{{ $modul->judul_modul }}</h4>
                                        @if($modul->deskripsi_modul)
                                            <p class="text-sm text-gray-600 mt-1">{{ $modul->deskripsi_modul }}</p>
                                        @endif
                                    </div>
                                    
                                    <div class="flex items-center space-x-4">
                                        <div>
                                            <span class="text-xs text-gray-500">Status:</span>
                                            @if($modul->status == 'pending')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 ml-1">
                                                    Menunggu Validasi
                                                </span>
                                            @elseif($modul->status == 'approved')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 ml-1">
                                                    Disetujui
                                                </span>
                                            @else
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 ml-1">
                                                    Ditolak
                                                </span>
                                            @endif
                                        </div>
                                        
                                        @if($modul->tahapPenyusunan)
                                            <div>
                                                <span class="text-xs text-gray-500">Tahap:</span>
                                                <span class="text-sm font-medium text-gray-900 ml-1">{{ $modul->tahapPenyusunan->nama_tahap }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="text-xs text-gray-500">
                                        <span>Diupload: {{ $modul->uploaded_at ? $modul->uploaded_at->format('d M Y H:i') : '-' }}</span>
                                        @if($modul->validated_at)
                                            <span class="ml-4">Divalidasi: {{ $modul->validated_at->format('d M Y H:i') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Right Side - File Information -->
                            @if($modul->file_path)
                                <div class="lg:w-64 border-l border-gray-200 p-4 bg-white">
                                    <h4 class="text-sm font-semibold text-gray-900 mb-3">File Modul</h4>
                                    <div class="space-y-2">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-xs text-gray-600 truncate">{{ $modul->file_name }}</span>
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            File modul yang diupload
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>


                @if($modul->catatan_admin)
                    <div class="mb-6">
                        <h4 class="font-medium text-gray-900 mb-3">Catatan dari Admin</h4>
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="text-blue-800">{{ $modul->catatan_admin }}</p>
                        </div>
                    </div>
                @endif

                @if($modul->status == 'rejected')
                    <div class="mb-6">
                        <div class="bg-red-50 border border-red-200 p-4 rounded-lg">
                            <h4 class="font-medium text-red-900 mb-2">Modul Ditolak</h4>
                            <p class="text-red-800">
                                Modul Anda ditolak oleh admin. Silakan perbaiki sesuai dengan catatan yang diberikan dan upload ulang pada tahap yang sesuai.
                            </p>
                        </div>
                    </div>
                @endif

                @if($modul->status == 'approved')
                    <div class="mb-6">
                        <div class="bg-green-50 border border-green-200 p-4 rounded-lg">
                            <h4 class="font-medium text-green-900 mb-2">Modul Disetujui</h4>
                            <p class="text-green-800">
                                Selamat! Modul Anda telah disetujui oleh admin. Anda dapat melanjutkan ke tahap berikutnya.
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection