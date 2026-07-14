@extends('layouts.app')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Detail Pengajuan Penyusun
    </h2>
@endsection

@section('content')
<div class="py-4 sm:py-6">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Card -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-green-50 to-blue-50 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Detail Pengajuan Penyusun</h3>
                        <p class="text-sm text-gray-600 mt-1">Informasi lengkap pengajuan bahan ajar</p>
                    </div>
                    <div class="mt-3 sm:mt-0">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($application->status === 'approved') bg-green-100 text-green-800
                            @elseif($application->status === 'rejected') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($application->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Informasi Pribadi -->
            <div class="lg:col-span-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                        <h4 class="text-lg font-medium text-gray-900">Informasi Pribadi</h4>
                    </div>
                    <div class="p-4 sm:p-6">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                            <div class="space-y-1">
                                <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                                <dd class="text-sm text-gray-900 font-medium">{{ $application->nama_penyusun }}</dd>
                            </div>
                            <div class="space-y-1">
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="text-sm text-gray-900 font-medium">{{ $application->email }}</dd>
                            </div>
                            <div class="space-y-1">
                                <dt class="text-sm font-medium text-gray-500">No. WhatsApp</dt>
                                <dd class="text-sm text-gray-900 font-medium">{{ $application->no_wa ?: '-' }}</dd>
                            </div>
                            <div class="space-y-1">
                                <dt class="text-sm font-medium text-gray-500">NIP</dt>
                                <dd class="text-sm text-gray-900 font-medium">{{ $application->nip ?: '-' }}</dd>
                            </div>
                            <div class="space-y-1">
                                <dt class="text-sm font-medium text-gray-500">NIDN</dt>
                                <dd class="text-sm text-gray-900 font-medium">{{ $application->nidn ?: '-' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Informasi Akademik -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                    <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                        <h4 class="text-lg font-medium text-gray-900">Informasi Akademik</h4>
                    </div>
                    <div class="p-4 sm:p-6">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                            <div class="space-y-1">
                                <dt class="text-sm font-medium text-gray-500">Judul Bahan Ajar</dt>
                                <dd class="text-sm text-gray-900 font-medium">{{ $application->judul_bahan_ajar }}</dd>
                            </div>
                            <div class="space-y-1">
                                <dt class="text-sm font-medium text-gray-500">Jurusan</dt>
                                <dd class="text-sm text-gray-900 font-medium">{{ $application->jurusan }}</dd>
                            </div>
                            <div class="space-y-1">
                                <dt class="text-sm font-medium text-gray-500">Semester</dt>
                                <dd class="text-sm text-gray-900 font-medium">{{ $application->semester }}</dd>
                            </div>
                            <div class="space-y-1">
                                <dt class="text-sm font-medium text-gray-500">Mata Kuliah</dt>
                                <dd class="text-sm text-gray-900 font-medium">{{ $application->mata_kuliah }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- File Draft -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                        <h4 class="text-lg font-medium text-gray-900">File Draft</h4>
                    </div>
                    <div class="p-4 sm:p-6">
                        @if($application->draft_path && Storage::disk('public')->exists($application->draft_path) && Storage::disk('public')->size($application->draft_path) > 0)
                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                                <div class="flex items-center">
                                    <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-green-900">File tersedia</p>
                                        <p class="text-xs text-green-600">Klik untuk mengunduh</p>
                                    </div>
                                </div>
                                <a href="{{ route('admin.penyusun.download', $application) }}" 
                                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Unduh
                                </a>
                            </div>
                        @else
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <svg class="w-8 h-8 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Tidak ada dokumen</p>
                                    <p class="text-xs text-gray-500">Penyusun tidak mengunggah dokumen draft</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Aksi Admin -->
                @if ($application->status === 'pending')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                        <h4 class="text-lg font-medium text-gray-900">Aksi Admin</h4>
                    </div>
                    <div class="p-4 sm:p-6 space-y-4">
                        <!-- Tombol Setujui -->
                        <form method="POST" action="{{ route('admin.penyusun.approve', $application) }}">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Setujui & Buat Akun
                            </button>
                        </form>

                        <!-- Form Tolak -->
                        <form method="POST" action="{{ route('admin.penyusun.reject', $application) }}" class="space-y-3">
                            @csrf
                            <div>
                                <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">Alasan Penolakan</label>
                                <textarea name="reason" id="reason" rows="3" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                                    placeholder="Masukkan alasan penolakan (opsional)"></textarea>
                            </div>
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Tolak Pengajuan
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection


