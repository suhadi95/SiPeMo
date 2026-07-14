@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Admin Dashboard - SiPeMo
    </h2>
@endsection

@section('content')
<div class="py-4 sm:py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 overflow-hidden shadow-sm sm:rounded-lg text-white">
            <div class="p-4 sm:p-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h1 class="text-lg sm:text-2xl font-bold">Selamat datang, {{ auth()->user()->name }}!</h1>
                        <p class="mt-2 text-green-100 text-sm sm:text-base">Anda login sebagai <span class="font-semibold">Administrator</span> SiPeMo</p>
                        <p class="text-xs sm:text-sm text-green-200 mt-1">{{ getTanggalWaktuIndonesia() }}</p>
                    </div>
                    <div class="hidden md:block ml-8">
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8">
                                <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 0 0-5.25 5.25v3a3 3 0 0 0-3 3v6.75a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3v-6.75a3 3 0 0 0-3-3v-3c0-2.9-2.35-5.25-5.25-5.25Zm3.75 8.25v-3a3.75 3.75 0 1 0-7.5 0v3h7.5Z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Statistics -->
        <div class="mt-6 grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
            @php
                $totalJurusan = \App\Models\Jurusan::count();
                $totalMataKuliah = \App\Models\MataKuliah::count();
                $totalPenyusun = \App\Models\User::where('is_penyusun', true)->count();
                
                $totalModulSelesai = \App\Models\Modul::where('status', 'approved')->count();
                $modulDalamProses = \App\Models\Modul::where('status', 'pending')->count();
                
                $totalFinalModul = \App\Models\FinalDraft::where('status', 'approved')->whereNotNull('lpm_validated_at')->count();
                $finalModulPending = \App\Models\FinalDraft::where('status', 'pending')->orWhereNull('lpm_validated_at')->count();
                
                $totalPublikasiModul = \App\Models\PublicationModul::where('status', 'approved')->whereNotNull('validated_at')->count();
                $publikasiModulPending = \App\Models\PublicationModul::where('status', 'pending')->orWhereNull('validated_at')->count();
                
                $mataKuliahDalamProses = \App\Models\MataKuliah::whereHas('moduls')->count();
                $mataKuliahBelumDibuat = $totalMataKuliah - $mataKuliahDalamProses;
            @endphp

            <!-- Total Jurusan -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-gray-200">
                <div class="p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-6 h-6 md:w-8 md:h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 md:w-5 md:h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4">
                            <p class="text-xs md:text-sm font-medium text-gray-500">Total Jurusan</p>
                            <p class="text-lg md:text-2xl font-semibold text-gray-900">{{ number_format($totalJurusan) }}</p>
                            <p class="text-xs text-gray-500">Program studi aktif</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Mata Kuliah -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-gray-200">
                <div class="p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-6 h-6 md:w-8 md:h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 md:w-5 md:h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4">
                            <p class="text-xs md:text-sm font-medium text-gray-500">Total Mata Kuliah</p>
                            <p class="text-lg md:text-2xl font-semibold text-gray-900">{{ number_format($totalMataKuliah) }}</p>
                            <p class="text-xs text-gray-500">{{ $mataKuliahDalamProses }} MK dalam proses, {{ $mataKuliahBelumDibuat }} MK belum dibuat</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Penyusun -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-gray-200">
                <div class="p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-6 h-6 md:w-8 md:h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 md:w-5 md:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4">
                            <p class="text-xs md:text-sm font-medium text-gray-500">Total Penyusun</p>
                            <p class="text-lg md:text-2xl font-semibold text-gray-900">{{ number_format($totalPenyusun) }}</p>
                            <p class="text-xs text-gray-500">Dosen penyusun aktif</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Modul Selesai -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-gray-200">
                <div class="p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-6 h-6 md:w-8 md:h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 md:w-5 md:h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4">
                            <p class="text-xs md:text-sm font-medium text-gray-500">Total Modul Selesai</p>
                            <p class="text-lg md:text-2xl font-semibold text-gray-900">{{ number_format($totalModulSelesai) }}</p>
                            <p class="text-xs text-gray-500">{{ $modulDalamProses }} dalam proses</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Final Modul -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-gray-200">
                <div class="p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-6 h-6 md:w-8 md:h-8 bg-teal-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 md:w-5 md:h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4">
                            <p class="text-xs md:text-sm font-medium text-gray-500">Total Final Modul</p>
                            <p class="text-lg md:text-2xl font-semibold text-gray-900">{{ number_format($totalFinalModul) }}</p>
                            <p class="text-xs text-gray-500">{{ $finalModulPending }} pending</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Publikasi Modul -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-gray-200">
                <div class="p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-6 h-6 md:w-8 md:h-8 bg-pink-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 md:w-5 md:h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4">
                            <p class="text-xs md:text-sm font-medium text-gray-500">Total Publikasi Modul</p>
                            <p class="text-lg md:text-2xl font-semibold text-gray-900">{{ number_format($totalPublikasiModul) }}</p>
                            <p class="text-xs text-gray-500">{{ $publikasiModulPending }} pending</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unduh Laporan Akhir Penyusunan Modul -->
        <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-gray-200">
            <div class="p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-900">Unduh Laporan Akhir Penyusunan Modul</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Download laporan final draft modul sebelum memulai progres periode berikutnya.</p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('admin.final-draft.report.pdf') }}"
                           class="inline-flex items-center px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-medium shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download PDF
                        </a>
                        <a href="{{ route('admin.final-draft.report.excel') }}"
                           class="inline-flex items-center px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium shadow hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Download CSV
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-6 sm:mt-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>

            <!-- Baris tunggal: Validasi Pendaftaran Penyusun -->
            <div class="mb-4 grid grid-cols-1">
                <a href="{{ route('admin.penyusun.index') }}" class="block rounded-lg ring-1 ring-green-100 bg-white shadow-sm hover:shadow-md transition-all duration-200 group">
                    <div class="p-4 sm:p-5">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-green-600 text-white flex items-center justify-center font-semibold group-hover:bg-green-700 transition-colors flex-shrink-0">V</div>
                            <div class="ml-3 sm:ml-4 flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 group-hover:text-green-700 transition-colors text-sm sm:text-base">Validasi Pendaftaran Penyusun</h3>
                                <p class="text-xs sm:text-sm text-gray-600 mt-1">Lihat dan validasi pengajuan menjadi penyusun modul</p>
                                @php
                                    $pendingApplications = \App\Models\PenyusunApplication::where('status', 'pending')->count();
                                @endphp
                                @if($pendingApplications > 0)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 mt-2">
                                        {{ $pendingApplications }} Menunggu
                                    </span>
                                @endif
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-green-600 group-hover:text-green-700 transition-colors flex-shrink-0">
                                <path fill-rule="evenodd" d="M4.5 12a.75.75 0 0 1 .75-.75h12.19l-3.72-3.72a.75.75 0 0 1 1.06-1.06l5 5a.75.75 0 0 1 0 1.06l-5 5a.75.75 0 1 1-1.06-1.06l3.72-3.72H5.25A.75.75 0 0 1 4.5 12Z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </a>
            </div>


            <!-- Baris: Proses 1-3 Penyusunan Modul, Final Draft, Publikasi -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                <!-- Proses 1 -->
                <a href="{{ route('admin.modul.index') }}" class="block rounded-lg ring-1 ring-red-100 bg-white shadow-sm hover:shadow-md transition-all duration-200 group">
                    <div class="p-4 sm:p-5">
                        <div class="flex items-center">
                            <div class="flex flex-col items-center mr-3 sm:mr-4">
                                <div class="w-10 h-10 rounded-full bg-red-600 text-white flex items-center justify-center font-semibold group-hover:bg-red-700 transition-colors">1</div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 group-hover:text-red-700 transition-colors text-sm sm:text-base">Penyusunan Modul</h3>
                                <p class="text-xs sm:text-sm text-gray-600 mt-1">Validasi dan kelola modul yang diupload penyusun</p>
                                @if($modulDalamProses > 0)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 mt-2">
                                        {{ $modulDalamProses }} Dalam Proses
                                    </span>
                                @endif
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-red-600 group-hover:text-red-700 transition-colors flex-shrink-0">
                                <path fill-rule="evenodd" d="M4.5 12a.75.75 0 0 1 .75-.75h12.19l-3.72-3.72a.75.75 0 0 1 1.06-1.06l5 5a.75.75 0 0 1 0 1.06l-5 5a.75.75 0 1 1-1.06-1.06l3.72-3.72H5.25A.75.75 0 0 1 4.5 12Z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </a>
                <!-- Proses 2 -->
                <a href="{{ route('admin.final-draft.index') }}" class="block rounded-lg ring-1 ring-teal-100 bg-white shadow-sm hover:shadow-md transition-all duration-200 group">
                    <div class="p-4 sm:p-5">
                        <div class="flex items-center">
                            <div class="flex flex-col items-center mr-3 sm:mr-4">
                                <div class="w-10 h-10 rounded-full bg-teal-600 text-white flex items-center justify-center font-semibold group-hover:bg-teal-700 transition-colors">2</div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 group-hover:text-teal-700 transition-colors text-sm sm:text-base">Final Draft</h3>
                                <p class="text-xs sm:text-sm text-gray-600 mt-1">Kelola final draft modul yang telah diselesaikan</p>
                                @if($finalModulPending > 0)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 mt-2">
                                        {{ $finalModulPending }} Pending
                                    </span>
                                @endif
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-teal-600 group-hover:text-teal-700 transition-colors flex-shrink-0">
                                <path fill-rule="evenodd" d="M4.5 12a.75.75 0 0 1 .75-.75h12.19l-3.72-3.72a.75.75 0 0 1 1.06-1.06l5 5a.75.75 0 0 1 0 1.06l-5 5a.75.75 0 1 1-1.06-1.06l3.72-3.72H5.25A.75.75 0 0 1 4.5 12Z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </a>
                <!-- Proses 3 -->
                <a href="{{ route('admin.publication.index') }}" class="block rounded-lg ring-1 ring-pink-100 bg-white shadow-sm hover:shadow-md transition-all duration-200 group">
                    <div class="p-4 sm:p-5">
                        <div class="flex items-center">
                            <div class="flex flex-col items-center mr-3 sm:mr-4">
                                <div class="w-10 h-10 rounded-full bg-pink-600 text-white flex items-center justify-center font-semibold group-hover:bg-pink-700 transition-colors">3</div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 group-hover:text-pink-700 transition-colors text-sm sm:text-base">Publikasi</h3>
                                <p class="text-xs sm:text-sm text-gray-600 mt-1">Kelola publikasi modul dan transfer honor</p>
                                @if($publikasiModulPending > 0)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 mt-2">
                                        {{ $publikasiModulPending }} Pending
                                    </span>
                                @endif
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-pink-600 group-hover:text-pink-700 transition-colors flex-shrink-0">
                                <path fill-rule="evenodd" d="M4.5 12a.75.75 0 0 1 .75-.75h12.19l-3.72-3.72a.75.75 0 0 1 1.06-1.06l5 5a.75.75 0 0 1 0 1.06l-5 5a.75.75 0 1 1-1.06-1.06l3.72-3.72H5.25A.75.75 0 0 1 4.5 12Z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- System Information -->
        <div class="mt-6 sm:mt-8 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-4 sm:p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Sistem SiPeMo</h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                <div>
                    <h4 class="font-medium text-gray-900 mb-2 text-sm sm:text-base">Tentang SiPeMo</h4>
                    <p class="text-xs sm:text-sm text-gray-600 mb-3">
                        <strong>Sistem Penyusunan Modul (SiPeMo)</strong> adalah platform digital untuk mengelola proses penyusunan modul pembelajaran secara terstruktur dan efisien.
                    </p>
                    <ul class="text-xs sm:text-sm text-gray-600 space-y-1">
                        <li>• <strong>Pendaftaran Penyusun:</strong> Dosen dapat mendaftar sebagai penyusun modul</li>
                        <li>• <strong>Proses Bertahap:</strong> Penyusunan modul dilakukan dalam tahap-tahap yang terstruktur</li>
                        <li>• <strong>Validasi Admin:</strong> Setiap modul divalidasi oleh admin sebelum disetujui</li>
                        <li>• <strong>Publikasi:</strong> Modul yang telah disetujui dapat dipublikasikan</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 mb-2 text-sm sm:text-base">Fitur Utama</h4>
                    <ul class="text-xs sm:text-sm text-gray-600 space-y-1">
                        <li>• <strong>Sistem Tahap Otomatis:</strong> Tahap penyusunan berjalan otomatis berdasarkan tanggal</li>
                        <li>• <strong>Multi-Role:</strong> Admin, Penyusun, dan LPM dengan akses berbeda</li>
                        <li>• <strong>Manajemen File:</strong> Upload dan download dokumen modul</li>
                        <li>• <strong>Monitoring:</strong> Pelacakan progres penyusunan secara real-time</li>
                        <li>• <strong>Validasi Bertingkat:</strong> Admin dan LPM dapat memvalidasi modul</li>
                        <li>• <strong>Bahasa Indonesia:</strong> Interface dalam bahasa Indonesia dengan zona waktu WIB</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


