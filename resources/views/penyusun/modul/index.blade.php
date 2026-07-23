@extends('layouts.app')

@section('header')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Penyusunan Modul') }}
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

                @if(!empty($templateModulUrl))
                <div class="mb-4 bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Template Modul</h3>
                            <p class="mt-1 text-sm text-blue-700">
                                Susun modul sesuai template yang disediakan admin. Unduh template melalui tombol di bawah.
                            </p>
                            <a href="{{ $templateModulUrl }}" target="_blank" rel="noopener noreferrer"
                               class="mt-2 inline-flex items-center px-3 py-1.5 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                Buka Template Modul
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                @php
                $rejectedModuls = $moduls->where('status', 'rejected');
                @endphp
                @if($rejectedModuls->count() > 0)
                <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                Modul Ditolak
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p>Anda memiliki {{ $rejectedModuls->count() }} modul yang ditolak. Silakan periksa catatan dari admin dan upload ulang sesuai dengan perbaikan yang diminta.</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Modul</h3>
                    <div class="bg-gray-50 rounded-lg overflow-hidden">
                        <div class="flex flex-col lg:flex-row">
                            <!-- Left Side - Basic Information -->
                            <div class="flex-1 p-4">
                                <div class="space-y-2">
                                    <p class="text-sm"><strong>Nama:</strong> {{ $application->nama_penyusun }}</p>
                                    <p class="text-sm"><strong>Mata Kuliah:</strong> {{ $application->mata_kuliah }}</p>
                                    <p class="text-sm"><strong>SKS:</strong> {{ $application->mataKuliah->sks ?? 'N/A' }} SKS</p>
                                    <p class="text-sm"><strong>Semester:</strong> {{ $application->semester }}</p>
                                    <p class="text-sm"><strong>Jurusan:</strong> {{ $application->jurusan }}</p>
                                </div>
                            </div>

                            <!-- Right Side - Ketentuan Penyusunan Modul -->
                            <div class="lg:w-80 xl:w-96 border-l border-gray-200 p-4 bg-blue-50">
                                <h4 class="text-sm font-semibold text-blue-800 mb-3">Ketentuan Penyusunan Modul</h4>
                                <div class="text-sm text-blue-700 space-y-2">
                                    <div>
                                        <span><strong>Total Tahap:</strong></span>
                                        <span>{{ $tahaps->count() }} tahap</span>
                                    </div>
                                    @if($tahaps->count() > 0)
                                    <div class="mt-3">
                                        <p class="text-xs font-medium text-blue-800 mb-2">Yang harus dikumpulkan:</p>
                                        <div class="space-y-2 text-xs text-blue-600">
                                            @foreach($tahaps as $tahapInfo)
                                            <div>
                                                <span class="font-medium">{{ $tahapInfo->nama_tahap }}:</span>
                                                <span>{{ $tahapInfo->deskripsi }}</span>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @else
                                    <div class="p-2 bg-blue-100 rounded text-xs text-blue-600">
                                        Belum ada tahap penyusunan yang aktif. Menunggu admin membuat periode.
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Tahap Penyusunan</h3>

                        @if($tahaps->count() > 0)
                        <div class="space-y-4">
                            @foreach($tahaps as $tahap)
                            @php
                            $modulInTahap = $moduls->where('tahap_id', $tahap->id)->first();
                            $isCurrentlyActive = $tahap->isCurrentlyActive();
                            $isAccessible = $tahap->isAccessibleForUser($application->id);
                            $hasUnfinishedModuls = $tahap->hasUnfinishedModuls($application->id);
                            $canUpload = $isAccessible &&
                            (!$modulInTahap || $modulInTahap->status !== 'approved') &&
                            ($isCurrentlyActive || $hasUnfinishedModuls);

                            $cardColorClass = '';
                            $cardBgClass = '';
                            if ($modulInTahap && $modulInTahap->status === 'approved') {
                            $cardColorClass = 'border-green-300 shadow-green-100';
                            $cardBgClass = 'bg-green-50';
                            } elseif ($isCurrentlyActive) {
                            $cardColorClass = 'border-blue-300 shadow-blue-100';
                            $cardBgClass = 'bg-blue-50';
                            }
                            @endphp

                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm {{ $cardColorClass }}">
                                <!-- Horizontal Layout -->
                                <div class="flex flex-col lg:flex-row">
                                    <!-- Left Side - Tahap Information -->
                                    <div class="flex-1 p-4 {{ $cardBgClass ?: 'bg-gray-50' }}">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0">
                                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <span class="text-sm font-semibold text-blue-600">{{ $tahap->tahap }}</span>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-base font-semibold text-gray-900">{{ $tahap->nama_tahap }}</h4>
                                                @if($tahap->deskripsi)
                                                <p class="text-xs text-gray-600 mt-1">{{ $tahap->deskripsi }}</p>
                                                @endif
                                                <p class="text-xs text-gray-500 mt-1">
                                                    {{ \Carbon\Carbon::parse($tahap->tanggal_mulai)->format('d M') }} -
                                                    {{ \Carbon\Carbon::parse($tahap->tanggal_selesai)->format('d M Y') }}
                                                </p>

                                                <!-- Status Badge -->
                                                <div class="mt-2">
                                                    @if($isCurrentlyActive)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <div class="w-2 h-2 bg-green-400 rounded-full mr-1.5"></div>
                                                        Aktif
                                                    </span>
                                                    @elseif($hasUnfinishedModuls)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        <div class="w-2 h-2 bg-yellow-400 rounded-full mr-1.5"></div>
                                                        Belum Selesai
                                                    </span>
                                                    @elseif($tahap->isPast())
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        <div class="w-2 h-2 bg-gray-400 rounded-full mr-1.5"></div>
                                                        Selesai
                                                    </span>
                                                    @else
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        <div class="w-2 h-2 bg-blue-400 rounded-full mr-1.5"></div>
                                                        Belum Dimulai
                                                    </span>
                                                    @endif
                                                </div>

                                                <!-- Modul Status -->
                                                <div class="mt-3">
                                                    @if($modulInTahap)
                                                    <div class="p-2 bg-white rounded border {{ $modulInTahap->status == 'rejected' ? 'border-red-300' : '' }}">
                                                        <div class="flex items-center space-x-2">
                                                            <div class="flex-shrink-0">
                                                                @if($modulInTahap->status == 'pending')
                                                                <div class="w-5 h-5 bg-yellow-100 rounded-full flex items-center justify-center">
                                                                    <svg class="w-3 h-3 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                                    </svg>
                                                                </div>
                                                                @elseif($modulInTahap->status == 'approved')
                                                                <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center">
                                                                    <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                                    </svg>
                                                                </div>
                                                                @else
                                                                <div class="w-5 h-5 bg-red-100 rounded-full flex items-center justify-center">
                                                                    <svg class="w-3 h-3 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                                    </svg>
                                                                </div>
                                                                @endif
                                                            </div>
                                                            <div class="flex-1">
                                                                <p class="text-sm font-medium text-gray-800">Modul: {{ $modulInTahap->nama_modul }}</p>
                                                                <p class="text-xs text-gray-500">Status:
                                                                    @if($modulInTahap->status == 'pending')
                                                                    <span class="text-yellow-600">Menunggu Persetujuan</span>
                                                                    @elseif($modulInTahap->status == 'approved')
                                                                    <span class="text-green-600">Disetujui</span>
                                                                    @else
                                                                    <span class="text-red-600">Ditolak</span>
                                                                    @endif
                                                                </p>
                                                            </div>
                                                        </div>
                                                        @if($modulInTahap->status == 'rejected' && $modulInTahap->catatan_admin)
                                                        <div class="mt-2 p-2 bg-red-50 rounded border border-red-200">
                                                            <p class="text-xs font-semibold text-red-800 mb-1">Catatan dari Admin:</p>
                                                            <p class="text-xs text-red-700">{{ $modulInTahap->catatan_admin }}</p>
                                                        </div>
                                                        @endif
                                                    </div>
                                                    @else
                                                    <div class="flex items-center space-x-2 p-2 bg-gray-100 rounded border border-gray-200">
                                                        <div class="flex-shrink-0">
                                                            <div class="w-5 h-5 bg-gray-200 rounded-full flex items-center justify-center">
                                                                <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <div class="flex-1">
                                                            <p class="text-sm font-medium text-gray-600">Belum Diunggah</p>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Side - Upload Section -->
                                    <div class="lg:w-80 xl:w-96 border-l border-gray-200 p-3 lg:p-4 bg-white">
                                        @if($canUpload && (!$modulInTahap || $modulInTahap->status !== 'pending'))
                                        @if($hasUnfinishedModuls && !$isCurrentlyActive)
                                        <div class="mb-3 p-2 bg-yellow-50 border border-yellow-200 rounded-md">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                <div class="text-xs text-yellow-800">
                                                    <p class="font-medium">Periode tahap sudah berakhir</p>
                                                    <p>Anda masih dapat mengupload karena ada modul yang belum selesai.</p>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        <form id="uploadForm{{ $tahap->tahap }}" enctype="multipart/form-data" class="space-y-2">
                                            @csrf
                                            <div>
                                                <label for="judul_modul{{ $tahap->tahap }}" class="block text-xs font-medium text-gray-700 mb-1">
                                                    Judul Modul
                                                </label>
                                                <input type="text"
                                                    id="judul_modul{{ $tahap->tahap }}"
                                                    name="judul_modul"
                                                    class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-transparent"
                                                    placeholder="Masukkan judul modul"
                                                    required>
                                            </div>

                                            <div>
                                                <label for="deskripsi_modul{{ $tahap->tahap }}" class="block text-xs font-medium text-gray-700 mb-1">
                                                    Deskripsi (Opsional)
                                                </label>
                                                <textarea id="deskripsi_modul{{ $tahap->tahap }}"
                                                    name="deskripsi_modul"
                                                    rows="2"
                                                    class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-transparent"
                                                    placeholder="Masukkan deskripsi modul"></textarea>
                                            </div>

                                            <div>
                                                <label for="file_modul{{ $tahap->tahap }}" class="block text-xs font-medium text-gray-700 mb-1">
                                                    File Modul (.doc/.docx)
                                                </label>
                                                <div class="mt-1 flex justify-center px-2 pt-2 pb-2 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                                                    <div class="space-y-1 text-center">
                                                        <svg class="mx-auto h-5 w-5 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                        <div class="flex text-xs text-gray-600">
                                                            <label for="file_modul{{ $tahap->tahap }}" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-1 focus-within:ring-offset-1 focus-within:ring-blue-500">
                                                                <span>Upload file</span>
                                                                <input id="file_modul{{ $tahap->tahap }}"
                                                                    name="file_modul"
                                                                    type="file"
                                                                    accept=".doc,.docx"
                                                                    class="sr-only"
                                                                    required>
                                                            </label>
                                                            <p class="pl-1">atau drag and drop</p>
                                                        </div>
                                                        <p class="text-xs text-gray-500">DOC, DOCX hingga 10MB</p>
                                                    </div>
                                                </div>
                                                <div id="fileInfo{{ $tahap->tahap }}" class="mt-1 text-xs text-gray-600 hidden"></div>
                                            </div>

                                            <div class="flex justify-end">
                                                <button type="submit"
                                                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-1.5 px-3 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:ring-offset-1 transition-colors text-xs">
                                                    <span class="upload-text">Upload Modul</span>
                                                    <span class="upload-loading hidden">
                                                        <svg class="animate-spin -ml-1 mr-2 h-3 w-3 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                        </svg>
                                                        Mengupload...
                                                    </span>
                                                </button>
                                            </div>
                                        </form>
                                        @elseif($modulInTahap && $modulInTahap->status === 'pending')
                                        <div class="text-center py-4">
                                            <div class="flex items-center justify-center mb-2">
                                                <svg class="w-5 h-5 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="text-sm font-medium text-gray-600">Modul Menunggu Validasi</span>
                                            </div>
                                            <p class="text-xs text-gray-500 mb-3">{{ $modulInTahap->judul_modul }}</p>
                                            <div class="space-y-2">
                                                <a href="{{ route('penyusun.modul.show', $modulInTahap) }}"
                                                    class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-1.5 px-3 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors text-xs text-center">
                                                    Lihat Detail
                                                </a>
                                                @if($modulInTahap->file_path)
                                                <a href="{{ route('penyusun.modul.download', $modulInTahap) }}"
                                                    class="block w-full bg-green-600 hover:bg-green-700 text-white font-medium py-1.5 px-3 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors text-xs text-center">
                                                    Download
                                                </a>
                                                @endif
                                                <button data-modul-id="{{ $modulInTahap->id }}"
                                                    data-tahap="{{ $tahap->tahap }}"
                                                    class="delete-modul-btn w-full bg-red-600 hover:bg-red-700 text-white font-medium py-1.5 px-3 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors text-xs">
                                                    Hapus Modul
                                                </button>
                                            </div>
                                        </div>
                                        @elseif($modulInTahap)
                                        <div class="text-center py-4">
                                            <div class="flex items-center justify-center mb-2">
                                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="text-sm font-medium text-gray-600">Modul Sudah Diupload</span>
                                            </div>
                                            <p class="text-xs text-gray-500 mb-3">{{ $modulInTahap->judul_modul }}</p>
                                            <div class="space-y-2">
                                                <a href="{{ route('penyusun.modul.show', $modulInTahap) }}"
                                                    class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-1.5 px-3 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors text-xs text-center">
                                                    Lihat Detail
                                                </a>
                                                @if($modulInTahap->file_path)
                                                <a href="{{ route('penyusun.modul.download', $modulInTahap) }}"
                                                    class="block w-full bg-green-600 hover:bg-green-700 text-white font-medium py-1.5 px-3 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors text-xs text-center">
                                                    Download
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                        @elseif(!$isAccessible && $tahap->isFuture())
                                        <div class="text-center py-4">
                                            <div class="flex items-center justify-center mb-1">
                                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="text-xs font-medium text-gray-600">Belum Dapat Diakses</span>
                                            </div>
                                            <p class="text-xs text-gray-500">
                                                Tunggu hingga tanggal mulai tahap.
                                            </p>
                                        </div>
                                        @elseif(!$isAccessible && $tahap->isPast() && !$modulInTahap)
                                        <div class="text-center py-4">
                                            <div class="flex items-center justify-center mb-1">
                                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="text-xs font-medium text-gray-600">Periode Berakhir</span>
                                            </div>
                                            <p class="text-xs text-gray-500">
                                                Tidak ada modul yang diupload pada periode ini.
                                            </p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-8">
                            <p class="text-gray-500">Tahap penyusunan belum dibuat oleh admin.</p>
                        </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
    @endsection

    @section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle file input changes
            document.querySelectorAll('input[type="file"]').forEach(function(input) {
                input.addEventListener('change', function() {
                    const tahap = this.id.replace('file_modul', '');
                    const fileInfo = document.getElementById('fileInfo' + tahap);

                    if (this.files.length > 0) {
                        const file = this.files[0];
                        const fileSize = (file.size / 1024 / 1024).toFixed(2);
                        fileInfo.innerHTML = `
                    <div class="flex items-center text-green-600">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-xs">File dipilih: ${file.name} (${fileSize} MB)</span>
                    </div>
                `;
                        fileInfo.classList.remove('hidden');
                    } else {
                        fileInfo.classList.add('hidden');
                    }
                });
            });

            // Handle form submissions
            document.querySelectorAll('form[id^="uploadForm"]').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const tahap = this.id.replace('uploadForm', '');
                    const submitButton = this.querySelector('button[type="submit"]');
                    const uploadText = submitButton.querySelector('.upload-text');
                    const uploadLoading = submitButton.querySelector('.upload-loading');

                    uploadText.classList.add('hidden');
                    uploadLoading.classList.remove('hidden');
                    submitButton.disabled = true;

                    const formData = new FormData(this);


                    const url = '{{ route("penyusun.modul.store", ":tahap") }}'.replace(':tahap', tahap);
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


                    fetch(url, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => {
                            console.log('Response status:', response.status);
                            console.log('Response headers:', response.headers);

                            if (!response.ok) {
                                return response.text().then(text => {
                                    console.log('Error response:', text);
                                    throw new Error(`HTTP error! status: ${response.status}, response: ${text}`);
                                });
                            }

                            const contentType = response.headers.get('content-type');
                            if (contentType && contentType.includes('application/json')) {
                                return response.json();
                            } else {
                                return response.text().then(text => {
                                    console.log('Non-JSON response:', text);
                                    throw new Error('Server mengembalikan response yang tidak valid');
                                });
                            }
                        })
                        .then(data => {
                            console.log('Response data:', data);
                            if (data.success) {
                                // Show success message dengan nama file
                                let message = data.message;
                                if (data.file_name) {
                                    message += '\nNama file: ' + data.file_name;
                                }
                                showNotification('success', message);

                                // Reset form
                                this.reset();
                                document.getElementById('fileInfo' + tahap).classList.add('hidden');

                                // Reload page after a short delay
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
                            } else {
                                // Show error message
                                showNotification('error', data.message || 'Terjadi kesalahan saat mengupload modul');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showNotification('error', 'Terjadi kesalahan saat mengupload modul: ' + error.message);
                        })
                        .finally(() => {
                            uploadText.classList.remove('hidden');
                            uploadLoading.classList.add('hidden');
                            submitButton.disabled = false;
                        });
                });
            });

            // Handle delete modul buttons
            document.querySelectorAll('.delete-modul-btn').forEach(function(button) {
                button.addEventListener('click', function() {
                    const modulId = this.getAttribute('data-modul-id');
                    const tahap = this.getAttribute('data-tahap');
                    deleteModul(modulId, tahap);
                });
            });

            // Delete modul function
            function deleteModul(modulId, tahap) {
                if (!confirm('Apakah Anda yakin ingin menghapus modul ini? Tindakan ini tidak dapat dibatalkan.')) {
                    return;
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/penyusun/modul/${modulId}/delete`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => {
                                throw new Error(`HTTP error! status: ${response.status}, response: ${text}`);
                            });
                        }

                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            return response.json();
                        } else {
                            return response.text().then(text => {
                                throw new Error('Server mengembalikan response yang tidak valid');
                            });
                        }
                    })
                    .then(data => {
                        if (data.success) {
                            showNotification('success', data.message);
                            // Reload page after a short delay
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            showNotification('error', data.message || 'Terjadi kesalahan saat menghapus modul');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('error', 'Terjadi kesalahan saat menghapus modul: ' + error.message);
                    });
            }

            // Notification function
            function showNotification(type, message) {
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg max-w-sm ${
            type === 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-red-100 border border-red-400 text-red-700'
        }`;

                notification.innerHTML = `
            <div class="flex items-start">
                <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    ${type === 'success' 
                        ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>'
                        : '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>'
                    }
                </svg>
                <div class="flex-1">
                    <div class="text-sm font-medium">${message.split('\n')[0]}</div>
                    ${message.includes('\n') ? `<div class="text-xs mt-1 opacity-75">${message.split('\n').slice(1).join('\n')}</div>` : ''}
                </div>
            </div>
        `;

                document.body.appendChild(notification);

                // Remove notification after 5 seconds
                setTimeout(() => {
                    notification.remove();
                }, 5000);
            }
        });
    </script>
    @endsection