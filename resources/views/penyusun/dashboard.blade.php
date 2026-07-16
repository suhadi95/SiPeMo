@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dashboard Penyusun
    </h2>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Welcome Card -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-green-100 mb-6">
            <div class="p-6 text-gray-900">
                Selamat datang, {{ auth()->user()->name }}! Anda login sebagai <span class="font-semibold text-green-700">Penyusun</span>.
            </div>
        </div>

        @if(!empty($templateModulUrl))
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Template Modul</h3>
                    <p class="mt-1 text-sm text-blue-700">
                        Silakan unduh dan gunakan template modul yang disediakan admin sebelum menyusun modul.
                    </p>
                    <a href="{{ $templateModulUrl }}" target="_blank" rel="noopener noreferrer"
                       class="mt-2 inline-flex items-center px-3 py-1.5 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                        Buka Template Modul
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Notification Summary -->
        <x-notification-summary :penyusun-applications="$penyusunApplications" />

        <!-- Judul Aksi dengan teks besar dan garis bawah -->
        <div class="mt-6 mb-2 px-4 sm:px-0">
            <div>
                <span class="text-lg font-bold text-gray-700 uppercase block mb-1">Aksi</span>
                <div class="w-full border-b border-gray-300"></div>
            </div>
        </div>

        <!-- Progress Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 px-4 sm:px-0">
            <!-- Card 1: Penyusunan Modul -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-blue-100">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Penyusunan Modul</h3>
                            <p class="text-sm text-gray-500">Upload modul untuk setiap tahap</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('penyusun.modul.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Penyusunan Modul
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 2: Final Draft dan Validasi Modul -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-purple-100">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Final Draft dan Validasi</h3>
                            <p class="text-sm text-gray-500">Upload final draft untuk penilaian Reviewer & LPM</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        @php
                            $application = \App\Models\PenyusunApplication::where('email', auth()->user()->email)
                                ->where('status', 'approved')
                                ->first();
                            $allTahapsValidated = false;
                            if ($application) {
                                $moduls = \App\Models\Modul::where('penyusun_application_id', $application->id)
                                    ->where('status', 'approved')
                                    ->count();
                                $allTahapsValidated = $moduls >= 4;
                            }
                        @endphp
                        
                        @if($allTahapsValidated)
                            <a href="{{ route('penyusun.final-draft.index') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Ajukan Final Draft
                            </a>
                        @else
                            <span class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-500 uppercase tracking-widest cursor-not-allowed">
                                Belum Tersedia
                            </span>
                            <p class="text-xs text-gray-400 mt-1">Semua tahap harus divalidasi terlebih dahulu</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Card 3: Publikasi Modul -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-gray-100">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Publikasi Modul</h3>
                            <p class="text-sm text-gray-500">Modul yang sudah divalidasi</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        @php
                            $canPublish = false;
                            $publicationStatus = 'Belum Tersedia';
                            
                            foreach($penyusunApplications as $application) {
                                $finalDraft = $application->finalDrafts->first();
                                $publication = $application->publicationModuls->first();
                                
                                if ($finalDraft && $finalDraft->status === 'approved') {
                                    $canPublish = true;
                                    if ($publication) {
                                        if ($publication->status == 'approved') {
                                            $publicationStatus = 'Disetujui';
                                        } elseif ($publication->status == 'rejected') {
                                            $publicationStatus = 'Ditolak';
                                        } else {
                                            $publicationStatus = 'Menunggu Validasi';
                                        }
                                    } else {
                                        $publicationStatus = 'Siap Upload';
                                    }
                                    break;
                                }
                            }
                        @endphp
                        
                        <div class="items-center justify-between mb-2">
                            <span class="text-sm text-gray-500">Status:</span>
                            <span class="text-sm font-medium 
                                @if($publicationStatus == 'Disetujui') text-green-600
                                @elseif($publicationStatus == 'Ditolak') text-red-600
                                @elseif($publicationStatus == 'Siap Upload') text-blue-600
                                @elseif($publicationStatus == 'Menunggu Validasi') text-yellow-600
                                @else text-gray-500 @endif">
                                {{ $publicationStatus }}
                            </span>
                        </div>
                        
                        @if($canPublish)
                            <a href="{{ route('penyusun.publication.index') }}" 
                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Kirim Bukti Publikasi
                            </a>
                        @else
                            <span class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-500 bg-gray-100 cursor-not-allowed">
                                Belum Tersedia
                            </span>
                            <p class="text-xs text-gray-400 mt-1">Final draft harus lolos Reviewer dan disetujui LPM</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


