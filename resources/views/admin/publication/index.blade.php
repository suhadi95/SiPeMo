@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Kelola Publikasi Modul
    </h2>
@endsection

@section('content')
<div class="py-4 sm:py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Summary Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6">
            <!-- Total Penyusun -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-gray-200">
                <div class="p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-6 h-6 md:w-8 md:h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 md:w-5 md:h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4">
                            <p class="text-xs md:text-sm font-medium text-gray-500">Total Penyusun</p>
                            <p class="text-lg md:text-2xl font-semibold text-gray-900">{{ $summary['total_penyusun'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Belum Tersedia -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-gray-200">
                <div class="p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-6 h-6 md:w-8 md:h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 md:w-5 md:h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4">
                            <p class="text-xs md:text-sm font-medium text-gray-500">Belum Tersedia</p>
                            <p class="text-lg md:text-2xl font-semibold text-gray-900">{{ $summary['belum_tersedia'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menunggu Validasi -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-gray-200">
                <div class="p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-6 h-6 md:w-8 md:h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 md:w-5 md:h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4">
                            <p class="text-xs md:text-sm font-medium text-gray-500">Menunggu Validasi</p>
                            <p class="text-lg md:text-2xl font-semibold text-gray-900">{{ $summary['menunggu_validasi'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Selesai -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-gray-200">
                <div class="p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-6 h-6 md:w-8 md:h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 md:w-5 md:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4">
                            <p class="text-xs md:text-sm font-medium text-gray-500">Selesai</p>
                            <p class="text-lg md:text-2xl font-semibold text-gray-900">{{ $summary['selesai'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-purple-100 mb-4 sm:mb-6">
            <div class="p-4 sm:p-6">
                <div class="flex items-start sm:items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-purple-800">
                            Validasi Publikasi Modul
                        </h3>
                        <div class="mt-2 text-xs sm:text-sm text-purple-700">
                            <p>Validasi final modul, sertifikat HKI, dan informasi rekening. Transfer dilakukan di luar sistem.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter dan Pencarian -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4 sm:mb-6">
            <div class="p-4 sm:p-6">
                <form method="GET" action="{{ route('admin.publication.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                placeholder="Cari nama, judul, email, MK..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Filter Status</label>
                            <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="">Semua Status</option>
                                <option value="belum_tersedia" {{ request('status') == 'belum_tersedia' ? 'selected' : '' }}>Belum Tersedia</option>
                                <option value="siap_upload" {{ request('status') == 'siap_upload' ? 'selected' : '' }}>Siap Upload</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Validasi</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>

                        <div>
                            <label for="jurusan_id" class="block text-sm font-medium text-gray-700 mb-1">Filter Jurusan</label>
                            <select name="jurusan_id" id="jurusan_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="">Semua Jurusan</option>
                                @foreach($jurusans as $jurusan)
                                    <option value="{{ $jurusan->id }}" {{ request('jurusan_id') == $jurusan->id ? 'selected' : '' }}>
                                        {{ $jurusan->nama_jurusan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="per_page" class="block text-sm font-medium text-gray-700 mb-1">Tampilkan</label>
                            <select name="per_page" id="per_page" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="15" {{ request('per_page', '15') == '15' ? 'selected' : '' }}>15</option>
                                <option value="30" {{ request('per_page') == '30' ? 'selected' : '' }}>30</option>
                                <option value="60" {{ request('per_page') == '60' ? 'selected' : '' }}>60</option>
                                <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3">
                        <a href="{{ route('admin.publication.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 text-center">
                            Reset
                        </a>
                        <button type="submit" class="px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @php $rowNumber = $paginator->firstItem() ?? 1; @endphp
        @if($penyusunsByJurusanWithAll->count() > 0)
            @foreach($penyusunsByJurusanWithAll as $namaJurusan => $penyusuns)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4 sm:mb-6">
                    <div class="p-4 sm:p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $namaJurusan }}</h3>
                        
                        @if($penyusuns->count() > 0)
                            <div class="overflow-x-auto -mx-4 sm:mx-0">
                                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                                        <table class="min-w-full divide-y divide-gray-300">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        No
                                                    </th>
                                                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Penyusun
                                                    </th>
                                                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Judul Modul
                                                    </th>
                                                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Mata Kuliah
                                                    </th>
                                                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Status
                                                    </th>
                                                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Tanggal Upload
                                                    </th>
                                                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Aksi
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($penyusuns as $penyusun)
                                                    <tr>
                                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900">
                                                            {{ $rowNumber++ }}
                                                        </td>
                                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                                            <div class="text-xs sm:text-sm font-medium text-gray-900">
                                                                {{ $penyusun->nama_penyusun }}
                                                            </div>
                                                        </td>
                                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                                            <div class="text-xs sm:text-sm text-gray-900 max-w-xs truncate">
                                                                {{ $penyusun->judul_bahan_ajar }}
                                                            </div>
                                                        </td>
                                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                                            <div class="text-xs sm:text-sm text-gray-900">
                                                                {{ $penyusun->mataKuliah->nama_mata_kuliah }}
                                                            </div>
                                                            <div class="text-xs text-gray-500">
                                                                Semester {{ $penyusun->mataKuliah->semester }}
                                                            </div>
                                                        </td>
                                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                                            @php
                                                                $publication = $penyusun->publicationModuls->first();
                                                                $finalDraft = $penyusun->finalDrafts->first();
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
                                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900">
                                                            @if($publication)
                                                                {{ $publication->uploaded_at->format('d M Y') }}
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm font-medium">
                                                            @if($publication)
                                                                <a href="{{ route('admin.publication.show', $publication) }}"
                                                                   class="inline-block px-2 sm:px-4 py-1 sm:py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition text-xs sm:text-sm">
                                                                    Validasi
                                                                </a>
                                                            @elseif($finalDraft)
                                                                <a href="{{ route('admin.final-draft.show', $finalDraft) }}"
                                                                   class="inline-block px-2 sm:px-4 py-1 sm:py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 transition text-xs sm:text-sm">
                                                                    Lihat Draft
                                                                </a>
                                                            @else
                                                                <span class="text-gray-400 text-xs">-</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-gray-50 p-4 sm:p-6 rounded-lg text-center">
                                <div class="text-gray-500">
                                    <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada penyusun</h3>
                                    <p class="mt-1 text-xs sm:text-sm text-gray-500">Jurusan {{ $namaJurusan }} belum memiliki penyusun yang terdaftar.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

            @if($paginator->hasPages())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4 sm:mb-6">
                    <div class="p-4 sm:p-6">
                        {{ $paginator->links() }}
                    </div>
                </div>
            @endif
        @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-center">
                    <div class="text-gray-500">
                        <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">
                            @if(request()->hasAny(['search', 'status', 'jurusan_id']))
                                Tidak ada data
                            @else
                                Belum ada publikasi
                            @endif
                        </h3>
                        <p class="mt-1 text-xs sm:text-sm text-gray-500">
                            @if(request()->hasAny(['search', 'status', 'jurusan_id']))
                                Tidak ada data yang sesuai dengan filter.
                            @else
                                Belum ada publikasi modul yang perlu divalidasi.
                            @endif
                        </p>
                        @if(request()->hasAny(['search', 'status', 'jurusan_id']))
                            <a href="{{ route('admin.publication.index') }}" class="inline-block mt-3 text-sm text-blue-600 hover:text-blue-800">Reset Filter</a>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
