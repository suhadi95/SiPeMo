@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Monitoring Progres Penyusunan Modul
    </h2>
@endsection

@section('content')
<div class="py-3 sm:py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Info Card -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-purple-100 mb-4 sm:mb-6">
            <div class="p-4 sm:p-6">
                <div class="flex items-start sm:items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-purple-800">
                            Monitoring LPM
                        </h3>
                        <div class="mt-1 sm:mt-2 text-xs sm:text-sm text-purple-700">
                            <p class="hidden sm:block">Pantau perkembangan penyusunan modul dari berbagai jurusan. Fokus pada status final draft dan kesiapan publikasi.</p>
                            <p class="sm:hidden">Pantau progres penyusunan modul dari berbagai jurusan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
            <!-- Total Penyusun -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-blue-100">
                <div class="p-3 sm:p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-2 sm:ml-3">
                            <p class="text-xs sm:text-sm font-medium text-gray-500">Total Penyusun</p>
                            <p class="text-lg sm:text-xl font-semibold text-gray-900">{{ $totalPenyusun }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Selesai Penyusunan -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-green-100">
                <div class="p-3 sm:p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-2 sm:ml-3">
                            <p class="text-xs sm:text-sm font-medium text-gray-500">Selesai Penyusunan</p>
                            <p class="text-lg sm:text-xl font-semibold text-gray-900">{{ $selesaiPenyusunan }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Final Draft Disetujui -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-purple-100">
                <div class="p-3 sm:p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-2 sm:ml-3">
                            <p class="text-xs sm:text-sm font-medium text-gray-500">Final Draft Disetujui</p>
                            <p class="text-lg sm:text-xl font-semibold text-gray-900">{{ $finalDraftDisetujui }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Selesai Publikasi -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-orange-100">
                <div class="p-3 sm:p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-2 sm:ml-3">
                            <p class="text-xs sm:text-sm font-medium text-gray-500">Selesai Publikasi</p>
                            <p class="text-lg sm:text-xl font-semibold text-gray-900">{{ $selesaiPublikasi }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($penyusunsByJurusanWithAll->count() > 0)
            @foreach($penyusunsByJurusanWithAll as $namaJurusan => $penyusuns)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4 sm:mb-6">
                    <div class="p-4 sm:p-6">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">{{ $namaJurusan }}</h3>
                        
                        @if($penyusuns->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                No
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Nama Penyusun
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Judul Modul
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Mata Kuliah
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status Progres
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status Final Draft
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status Publikasi
                                            </th>
                                        </tr>
                                    </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($penyusuns as $index => $penyusun)
                                                    @php
                                                        // Cek apakah penyusun sudah menyelesaikan semua tahap dan publikasi disetujui
                                                        $publication = $penyusun->publicationModuls->first();
                                                        $isFullyCompleted = $publication && $publication->status == 'approved';
                                                        $rowClass = $isFullyCompleted ? 'bg-green-50 hover:bg-green-100' : 'hover:bg-gray-50';
                                                    @endphp
                                                    <tr class="{{ $rowClass }} transition-colors duration-150">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm {{ $isFullyCompleted ? 'text-green-900 font-semibold' : 'text-gray-900' }}">
                                                    {{ $index + 1 }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium {{ $isFullyCompleted ? 'text-green-900' : 'text-gray-900' }}">
                                                        {{ $penyusun->nama_penyusun }}
                                                    </div>
                                                    @if($isFullyCompleted)
                                                        <div class="text-xs text-green-600 font-medium mt-0.5">
                                                            ✓ Selesai Penyusunan Modul
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm {{ $isFullyCompleted ? 'text-green-900' : 'text-gray-900' }}">
                                                        {{ $penyusun->judul_bahan_ajar }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium {{ $isFullyCompleted ? 'text-green-900' : 'text-gray-900' }}">
                                                        {{ $penyusun->mataKuliah->nama_mata_kuliah }}
                                                    </div>
                                                    <div class="text-sm {{ $isFullyCompleted ? 'text-green-700' : 'text-gray-500' }}">
                                                        Semester {{ $penyusun->mataKuliah->semester }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($penyusun->moduls->count() > 0)
                                                        @php
                                                            // Cari modul dengan tahap tertinggi yang approved
                                                            $highestApprovedModul = $penyusun->moduls
                                                                ->where('status', 'approved')
                                                                ->sortByDesc(function($modul) {
                                                                    return $modul->tahapPenyusunan->tahap ?? 0;
                                                                })
                                                                ->first();
                                                            
                                                            // Cari modul pending terbaru
                                                            $latestPendingModul = $penyusun->moduls
                                                                ->where('status', 'pending')
                                                                ->sortByDesc('created_at')
                                                                ->first();
                                                            
                                                            // Cari modul rejected terbaru
                                                            $latestRejectedModul = $penyusun->moduls
                                                                ->where('status', 'rejected')
                                                                ->sortByDesc('created_at')
                                                                ->first();
                                                            
                                                            // Tentukan modul mana yang akan ditampilkan
                                                            if ($latestPendingModul) {
                                                                $displayModul = $latestPendingModul;
                                                                $displayStatus = 'pending';
                                                            } elseif ($latestRejectedModul) {
                                                                $displayModul = $latestRejectedModul;
                                                                $displayStatus = 'rejected';
                                                            } elseif ($highestApprovedModul) {
                                                                $displayModul = $highestApprovedModul;
                                                                $displayStatus = 'approved';
                                                            } else {
                                                                $displayModul = $penyusun->moduls->first();
                                                                $displayStatus = $displayModul->status;
                                                            }
                                                            
                                                            $tahap = $displayModul->tahapPenyusunan->tahap ?? 1;
                                                            $totalTahap = 4;
                                                        @endphp
                                                        
                                                        @if($displayStatus == 'pending')
                                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                                Menunggu Validasi Tahap {{ $tahap }}
                                                            </span>
                                                        @elseif($displayStatus == 'approved')
                                                            @if($tahap == $totalTahap)
                                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                                    Semua Tahap Selesai
                                                                </span>
                                                            @else
                                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                                    Tahap {{ $tahap }} Selesai
                                                                </span>
                                                            @endif
                                                        @elseif($displayStatus == 'rejected')
                                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                                Perlu Revisi Tahap {{ $tahap }}
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                            Belum Memulai
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @php
                                                        $finalDraft = $penyusun->finalDrafts->first();
                                                        $allTahapsValidated = $penyusun->moduls->where('status', 'approved')->count() >= 6;
                                                    @endphp
                                                    
                                                    @if($finalDraft)
                                                        @if($finalDraft->status == 'approved')
                                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                                Disetujui
                                                            </span>
                                                        @elseif($finalDraft->status == 'rejected')
                                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                                Ditolak
                                                            </span>
                                                        @elseif($finalDraft->status == 'pending')
                                                            @if($finalDraft->isLpmValidated())
                                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                                    Disetujui
                                                                </span>
                                                            @else
                                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                                    Menunggu Validasi LPM
                                                                </span>
                                                            @endif
                                                        @endif
                                                    @elseif($allTahapsValidated)
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                            Siap Upload
                                                        </span>
                                                    @else
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                            Belum Tersedia
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @php
                                                        $finalDraft = $penyusun->finalDrafts->first();
                                                        $publication = $penyusun->publicationModuls->first();
                                                        $isFullyValidated = $finalDraft && $finalDraft->isLpmValidated() && $finalDraft->status === 'approved';
                                                    @endphp
                                                    
                                                    @if($publication)
                                                        @if($publication->status == 'approved')
                                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                                Disetujui
                                                            </span>
                                                        @elseif($publication->status == 'rejected')
                                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                                Ditolak
                                                            </span>
                                                        @else
                                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                                Menunggu Validasi
                                                            </span>
                                                        @endif
                                                    @elseif($isFullyValidated)
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                            Siap Upload
                                                        </span>
                                                    @else
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                            Belum Tersedia
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="bg-gray-50 p-4 sm:p-6 rounded-lg text-center">
                                <div class="text-gray-500">
                                    <svg class="mx-auto h-8 w-8 sm:h-12 sm:w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada penyusun</h3>
                                    <p class="mt-1 text-xs sm:text-sm text-gray-500">Jurusan {{ $namaJurusan }} belum memiliki penyusun yang terdaftar.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-center">
                    <p class="text-sm sm:text-base text-gray-500">Belum ada jurusan yang terdaftar.</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
