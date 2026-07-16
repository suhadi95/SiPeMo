@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Kelola Final Draft Modul
    </h2>
@endsection

@section('content')
<div class="py-4 sm:py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-4 sm:mb-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-gray-200">
                <div class="p-4 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 sm:ml-4">
                            <p class="text-xs sm:text-sm font-medium text-gray-500">Total Penyusun</p>
                            <p class="text-xl sm:text-2xl font-semibold text-gray-900">{{ $penyusunsByJurusanWithAll->flatten()->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-gray-200">
                <div class="p-4 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 sm:ml-4">
                            <p class="text-xs sm:text-sm font-medium text-gray-500">Menunggu Validasi</p>
                            <p class="text-xl sm:text-2xl font-semibold text-gray-900">{{ $finalDrafts->where('status', 'pending')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-gray-200">
                <div class="p-4 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 sm:ml-4">
                            <p class="text-xs sm:text-sm font-medium text-gray-500">Disetujui</p>
                            <p class="text-xl sm:text-2xl font-semibold text-gray-900">{{ $finalDrafts->where('status', 'approved')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-gray-200">
                <div class="p-4 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 sm:ml-4">
                            <p class="text-xs sm:text-sm font-medium text-gray-500">Ditolak</p>
                            <p class="text-xl sm:text-2xl font-semibold text-gray-900">{{ $finalDrafts->where('status', 'rejected')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Info Card -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-purple-100 mb-4 sm:mb-6">
            <div class="p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-purple-800">
                            Final Draft LPM
                        </h3>
                        <div class="mt-2 text-xs sm:text-sm text-purple-700">
                            <p>Pantau status final draft modul dari berbagai jurusan. Semua penyusun yang approved ditampilkan, termasuk yang belum memulai.</p>
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
                            <div class="overflow-x-auto -mx-4 sm:mx-0">
                                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                    <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                No
                                            </th>
                                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Nama Penyusun
                                            </th>
                                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Judul Modul
                                            </th>
                                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Mata Kuliah
                                            </th>
                                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status Final Draft
                                            </th>
                                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Aksi
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($penyusuns as $index => $penyusun)
                                            <tr>
                                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $index + 1 }}
                                                </td>
                                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $penyusun->nama_penyusun }}
                                                    </div>
                                                </td>
                                                <td class="px-3 sm:px-6 py-4">
                                                    <div class="text-sm text-gray-900 max-w-xs truncate">
                                                        {{ $penyusun->judul_bahan_ajar }}
                                                    </div>
                                                </td>
                                                <td class="px-3 sm:px-6 py-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $penyusun->mataKuliah->nama_mata_kuliah }}
                                                    </div>
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        {{ $penyusun->mataKuliah->jurusan->nama_jurusan }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        Semester {{ $penyusun->mataKuliah->semester }}
                                                    </div>
                                                </td>
                                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                                                    @php
                                                        $finalDraft = $penyusun->finalDrafts->first();
                                                        $allTahapsValidated = $penyusun->moduls->where('status', 'approved')->count() >= 6;
                                                    @endphp
                                                    
                                                    @if($finalDraft)
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $finalDraft->statusBadgeClass() }}">
                                                            {{ $finalDraft->statusLabel() }}
                                                        </span>
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
                                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    @if($finalDraft && in_array($finalDraft->status, ['approved_by_reviewer', 'pending_lpm', 'approved', 'rejected'], true))
                                                        <div class="flex flex-col sm:flex-row space-y-1 sm:space-y-0 sm:space-x-2">
                                                            <a href="{{ route('lpm.final-draft.show', $finalDraft) }}"
                                                               class="inline-block px-3 py-1 sm:px-4 sm:py-2 bg-indigo-600 text-white text-xs sm:text-sm font-semibold rounded-md hover:bg-indigo-700 transition text-center">
                                                                Lihat
                                                            </a>
                                                            <a href="{{ route('lpm.final-draft.download', $finalDraft) }}"
                                                               class="inline-block px-3 py-1 sm:px-4 sm:py-2 bg-green-600 text-white text-xs sm:text-sm font-semibold rounded-md hover:bg-green-700 transition text-center">
                                                                Download
                                                            </a>
                                                        </div>
                                                    @else
                                                        <span class="text-gray-400 text-xs sm:text-sm">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    </table>
                                </div>
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
