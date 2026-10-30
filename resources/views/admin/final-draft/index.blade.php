@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
        Monitoring Final Draft Modul
    </h2>
@endsection

@section('content')
<div class="py-4 md:py-6" x-data="{ modalOpen: false, modalTitle: '', modalAction: '', selectedReviewers: [] }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6">
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
                            <p class="text-xs md:text-sm font-medium text-gray-500">Dalam Proses</p>
                            <p class="text-lg md:text-2xl font-semibold text-gray-900">{{ $finalDrafts->whereIn('status', ['pending_review', 'approved_by_reviewer', 'pending_lpm', 'pending', 'rejected_by_reviewer'])->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

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
                            <p class="text-xs md:text-sm font-medium text-gray-500">Disetujui LPM</p>
                            <p class="text-lg md:text-2xl font-semibold text-gray-900">{{ $finalDrafts->where('status', 'approved')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-gray-200">
                <div class="p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-6 h-6 md:w-8 md:h-8 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 md:w-5 md:h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4">
                            <p class="text-xs md:text-sm font-medium text-gray-500">Ditolak LPM</p>
                            <p class="text-lg md:text-2xl font-semibold text-gray-900">{{ $finalDrafts->where('status', 'rejected')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-gray-200">
                <div class="p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-6 h-6 md:w-8 md:h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 md:w-5 md:h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4">
                            <p class="text-xs md:text-sm font-medium text-gray-500">Total</p>
                            <p class="text-lg md:text-2xl font-semibold text-gray-900">{{ $finalDrafts->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-purple-100 mb-6">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-purple-800">
                            Monitoring Admin
                        </h3>
                        <div class="mt-2 text-sm text-purple-700">
                            <p>Pantau perkembangan final draft dari berbagai jurusan. Alur: Reviewer menilai terlebih dahulu, lalu LPM melakukan validasi akhir sebelum publikasi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter dan Pencarian -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4 sm:mb-6">
            <div class="p-4 sm:p-6">
                <form method="GET" action="{{ route('admin.final-draft.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                placeholder="Cari nama, judul, email, MK..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Filter Status Final Draft</label>
                            <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="">Semua Status</option>
                                <option value="belum_tersedia" {{ request('status') == 'belum_tersedia' ? 'selected' : '' }}>Belum Tersedia</option>
                                <option value="siap_upload" {{ request('status') == 'siap_upload' ? 'selected' : '' }}>Siap Upload</option>
                                <option value="pending_review" {{ request('status') == 'pending_review' ? 'selected' : '' }}>Menunggu Reviewer</option>
                                <option value="rejected_by_reviewer" {{ request('status') == 'rejected_by_reviewer' ? 'selected' : '' }}>Perlu Revisi (Reviewer)</option>
                                <option value="approved_by_reviewer" {{ request('status') == 'approved_by_reviewer' ? 'selected' : '' }}>Lolos Reviewer (Menunggu LPM)</option>
                                <option value="pending_lpm" {{ request('status') == 'pending_lpm' ? 'selected' : '' }}>Menunggu Validasi LPM (revisi)</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui LPM</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak LPM</option>
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
                        <a href="{{ route('admin.final-draft.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 text-center">
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
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $namaJurusan }}</h3>
                        
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
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Reviewer
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Aksi
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($penyusuns as $penyusun)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $rowNumber++ }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $penyusun->nama_penyusun }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">
                                                        {{ $penyusun->judul_bahan_ajar }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">
                                                        {{ $penyusun->mataKuliah->nama_mata_kuliah }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        Semester {{ $penyusun->mataKuliah->semester }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($penyusun->moduls->count() > 0)
                                                        @php
                                                            $latestModul = $penyusun->moduls->sortByDesc('created_at')->first();
                                                            $tahap = $latestModul->tahapPenyusunan->tahap ?? 1;
                                                        @endphp
                                                        
                                                        @if($latestModul->status == 'pending')
                                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                                Menunggu Validasi Tahap {{ $tahap }}
                                                            </span>
                                                        @elseif($latestModul->status == 'approved')
                                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                                Tahap {{ $tahap }} Selesai
                                                            </span>
                                                        @elseif($latestModul->status == 'rejected')
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
                                                <td class="px-6 py-4 text-sm">
                                                    @php
                                                        $assignedReviewersList = $penyusun->reviewers;
                                                        $assignedIds = $assignedReviewersList->pluck('id')->toArray();
                                                        $fdInstance = $penyusun->finalDrafts->first();
                                                    @endphp
                                                    <div class="space-y-1.5 min-w-[150px]">
                                                        @forelse($assignedReviewersList as $revUser)
                                                            @php
                                                                $isDone = $fdInstance && $fdInstance->isReviewCompletedBy($revUser->id);
                                                            @endphp
                                                            <div class="flex items-center gap-1">
                                                                @if($isDone)
                                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-green-100 text-green-800" title="Review telah selesai">
                                                                        ✓ {{ $revUser->name }}
                                                                    </span>
                                                                @else
                                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-yellow-100 text-yellow-800" title="Menunggu penilaian reviewer">
                                                                        ⏳ {{ $revUser->name }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        @empty
                                                            <span class="text-xs text-gray-400 italic">Belum ada reviewer</span>
                                                        @endforelse

                                                        <div>
                                                            <button type="button"
                                                                    @click="modalOpen = true; modalTitle = @js($penyusun->judul_bahan_ajar); modalAction = @js(route('admin.final-draft.assign-reviewers', $penyusun)); selectedReviewers = @js($assignedIds)"
                                                                    class="inline-flex items-center text-[11px] text-blue-600 hover:text-blue-800 font-medium hover:underline mt-1">
                                                                <svg class="w-3.5 h-3.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                                </svg>
                                                                Atur Reviewer
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    @php
                                                        $finalDraft = $penyusun->finalDrafts->first();
                                                    @endphp
                                                    @if($finalDraft)
                                                        <div class="flex flex-row">
                                                            <a href="{{ route('admin.final-draft.show', $finalDraft) }}" 
                                                               class="inline-flex items-center justify-center px-2 h-6 rounded bg-indigo-600 text-white text-xs font-medium shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition mr-1">
                                                                Lihat
                                                            </a>
                                                            <a href="{{ route('admin.final-draft.download', $finalDraft) }}" 
                                                               class="inline-flex items-center justify-center px-2 h-6 rounded bg-green-600 text-white text-xs font-medium shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                                                                Download
                                                            </a>
                                                        </div>
                                                        @if($finalDraft->reviews->isNotEmpty())
                                                            <div class="mt-1">
                                                                <a href="{{ route('admin.final-draft.reviews-pdf', $penyusun) }}"
                                                                   class="inline-flex items-center justify-center px-2 py-0.5 rounded border border-blue-300 text-[11px] font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 transition shadow-xs"
                                                                   title="Unduh rekap penilaian seluruh reviewer (PDF)">
                                                                    Hasil Review (PDF)
                                                                </a>
                                                            </div>
                                                        @endif
                                                    @else
                                                        <span class="text-gray-400 text-xs">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="bg-gray-50 p-6 rounded-lg text-center">
                                <div class="text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada penyusun</h3>
                                    <p class="mt-1 text-sm text-gray-500">Jurusan {{ $namaJurusan }} belum memiliki penyusun yang terdaftar.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

            @if($paginator->hasPages())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-4 sm:p-6">
                        {{ $paginator->links() }}
                    </div>
                </div>
            @endif
        @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center">
                    <p class="text-gray-500">
                        @if(request()->hasAny(['search', 'status', 'jurusan_id']))
                            Tidak ada data yang sesuai dengan filter.
                        @else
                            Belum ada jurusan yang terdaftar.
                        @endif
                    </p>
                    @if(request()->hasAny(['search', 'status', 'jurusan_id']))
                        <a href="{{ route('admin.final-draft.index') }}" class="inline-block mt-3 text-sm text-blue-600 hover:text-blue-800">Reset Filter</a>
                    @endif
                </div>
            </div>
        @endif

        <!-- Modal Atur Reviewer -->
        <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="modalOpen = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form :action="modalAction" method="POST">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-base font-semibold text-gray-900" id="modal-title">
                                        Tetapkan Reviewer Modul
                                    </h3>
                                    <div class="mt-1">
                                        <p class="text-xs text-gray-700 font-bold" x-text="modalTitle"></p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            Pilih satu atau lebih reviewer untuk modul ini. Modul dapat ditugaskan kapan pun tanpa harus menunggu tahap Final Draft.
                                        </p>
                                    </div>

                                    <div class="mt-4 max-h-60 overflow-y-auto space-y-2 border border-gray-200 rounded-md p-3">
                                        @forelse($availableReviewers as $rev)
                                            <label class="flex items-start p-2 rounded hover:bg-gray-50 cursor-pointer text-xs">
                                                <input type="checkbox" name="reviewer_ids[]" value="{{ $rev->id }}"
                                                       x-model="selectedReviewers"
                                                       class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-0.5">
                                                <div class="ml-2.5">
                                                    <span class="font-semibold text-gray-900 block">{{ $rev->name }}</span>
                                                    <span class="text-gray-500 text-[11px]">{{ $rev->email }}</span>
                                                </div>
                                            </label>
                                        @empty
                                            <p class="text-xs text-gray-400 italic text-center py-4">Belum ada akun reviewer yang aktif.</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:w-auto">
                                Simpan Penetapan
                            </button>
                            <button type="button" @click="modalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
