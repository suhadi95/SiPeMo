@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Tinjau Final Draft Modul
    </h2>
@endsection

@section('content')
<div class="py-4 sm:py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-4">
            <a href="{{ route('reviewer.final-draft.index') }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-900">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar Pekerjaan
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 p-6 lg:col-span-2">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100">Informasi Modul Ajar</h3>

                <div class="space-y-4 mb-6">
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 uppercase">Judul Modul</span>
                        <span class="text-base font-semibold text-gray-900">{{ $finalDraft->judul_modul }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 uppercase">Deskripsi / Abstrak Modul</span>
                        <p class="text-sm text-gray-700 mt-1 whitespace-pre-line">{{ $finalDraft->deskripsi_modul ?: 'Tidak ada deskripsi.' }}</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <span class="block text-xs font-semibold text-gray-500 uppercase">Mata Kuliah</span>
                            <span class="text-sm font-medium text-gray-900">{{ $finalDraft->mataKuliah->nama_mata_kuliah }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold text-gray-500 uppercase">Jurusan</span>
                            <span class="text-sm font-medium text-gray-900">{{ $finalDraft->mataKuliah->jurusan->nama_jurusan }}</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <span class="block text-xs font-semibold text-gray-500 uppercase">Kode Draft</span>
                            <span class="text-sm font-medium text-gray-900">FD-{{ str_pad($finalDraft->id, 5, '0', STR_PAD_LEFT) }}</span>
                            <p class="text-xs text-gray-400 mt-0.5">Identitas penyusun disembunyikan (blind review)</p>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold text-gray-500 uppercase">Tanggal Diunggah</span>
                            <span class="text-sm font-medium text-gray-900">{{ $finalDraft->uploaded_at->format('d F Y - H:i') }} WIB</span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                    <h4 class="text-sm font-semibold text-gray-900 mb-2">Berkas Dokumen Final Draft</h4>
                    <p class="text-xs text-gray-500 mb-4">Unduh dan periksa dokumen sebelum mengisi formulir penilaian.</p>

                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between bg-white border border-gray-200 p-4 rounded-lg gap-3">
                        <div class="flex items-center space-x-3">
                            <svg class="h-8 w-8 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <div class="max-w-[250px] sm:max-w-[400px]">
                                <span class="block text-sm font-semibold text-gray-900 truncate">FinalDraft_FD-{{ str_pad($finalDraft->id, 5, '0', STR_PAD_LEFT) }}.{{ pathinfo($finalDraft->file_name, PATHINFO_EXTENSION) }}</span>
                                <span class="block text-xs text-gray-400">Word Document (.doc / .docx)</span>
                            </div>
                        </div>
                        <a href="{{ route('reviewer.final-draft.download', $finalDraft) }}"
                           class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition">
                            Unduh Dokumen
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100">Status & Validasi</h3>

                @if($finalDraft->status === 'pending_review')
                    <div class="flex items-center text-yellow-700 mb-3">
                        <svg class="w-5 h-5 text-yellow-500 mr-2 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-sm font-semibold">Menunggu Penilaian Anda</span>
                    </div>
                    <p class="text-xs text-gray-500 mb-4">
                        Unduh dokumen terlebih dahulu, lalu buka formulir penilaian terpisah untuk mengisi skala Likert dan hasil kelayakan.
                    </p>

                    @if($hasActiveKriteria)
                        <a href="{{ route('reviewer.final-draft.assess', $finalDraft) }}"
                           class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md text-sm shadow-sm transition">
                            Mulai Formulir Penilaian
                        </a>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-3 py-2 rounded text-xs">
                            Belum ada kriteria penilaian aktif. Hubungi admin.
                        </div>
                    @endif
                @else
                    <div class="space-y-6">
                        <div class="border rounded-lg p-4 bg-gray-50">
                            <h4 class="text-xs font-semibold text-gray-500 uppercase mb-2">Pemeriksaan Reviewer</h4>

                            @if($finalDraft->hasil_penilaian)
                                @php
                                    $hasilClass = match ($finalDraft->hasil_penilaian) {
                                        'sangat_layak' => 'text-green-700',
                                        'layak_dengan_perbaikan' => 'text-yellow-700',
                                        'perlu_revisi_mayor' => 'text-orange-700',
                                        'tidak_layak' => 'text-red-700',
                                        default => 'text-gray-700',
                                    };
                                @endphp
                                <div class="{{ $hasilClass }} mb-2">
                                    <span class="text-sm font-semibold">{{ $finalDraft->hasil_penilaian_label }}</span>
                                </div>
                            @elseif($finalDraft->status === 'rejected_by_reviewer')
                                <div class="text-red-700 mb-2">
                                    <span class="text-sm font-semibold">Dikembalikan ke Penyusun</span>
                                </div>
                            @else
                                <div class="text-green-700 mb-2">
                                    <span class="text-sm font-semibold">Disetujui Reviewer</span>
                                </div>
                            @endif

                            <div class="text-xs text-gray-500 space-y-1 mt-3">
                                <div>Oleh: {{ $finalDraft->reviewerValidator->name ?? 'Reviewer' }}</div>
                                @if($finalDraft->reviewer_validated_at)
                                    <div>Waktu: {{ $finalDraft->reviewer_validated_at->format('d M Y H:i') }}</div>
                                @endif
                            </div>

                            @if($finalDraft->catatan_reviewer)
                                <div class="mt-4 border-t border-gray-200 pt-3">
                                    <span class="block text-xs font-semibold text-gray-500 uppercase">Catatan Revisi:</span>
                                    <p class="text-xs text-gray-700 mt-1 italic">"{{ $finalDraft->catatan_reviewer }}"</p>
                                </div>
                            @endif
                        </div>

                        <div class="border rounded-lg p-4 bg-gray-50">
                            <h4 class="text-xs font-semibold text-gray-500 uppercase mb-2">Pemeriksaan LPM</h4>

                            @if($finalDraft->status === 'pending_lpm')
                                <div class="text-yellow-700 mb-2">
                                    <span class="text-sm font-semibold">Menunggu Validasi LPM (revisi)</span>
                                </div>
                            @elseif($finalDraft->isLpmValidated())
                                @if($finalDraft->status === 'approved')
                                    <div class="text-green-700 mb-2">
                                        <span class="text-sm font-semibold">Telah Disetujui LPM</span>
                                    </div>
                                @elseif($finalDraft->status === 'rejected')
                                    <div class="text-red-700 mb-2">
                                        <span class="text-sm font-semibold">Ditolak LPM</span>
                                    </div>
                                @endif

                                <div class="text-xs text-gray-500 space-y-1 mt-3">
                                    <div>Oleh: {{ $finalDraft->lpmValidator->name ?? 'LPM' }}</div>
                                    @if($finalDraft->lpm_validated_at)
                                        <div>Waktu: {{ $finalDraft->lpm_validated_at->format('d M Y H:i') }}</div>
                                    @endif
                                </div>

                                @if($finalDraft->catatan_lpm)
                                    <div class="mt-4 border-t border-gray-200 pt-3">
                                        <span class="block text-xs font-semibold text-gray-500 uppercase">Catatan LPM:</span>
                                        <p class="text-xs text-gray-700 mt-1 italic">"{{ $finalDraft->catatan_lpm }}"</p>
                                    </div>
                                @endif
                            @elseif($finalDraft->status === 'approved_by_reviewer')
                                <div class="text-yellow-700 mb-2">
                                    <span class="text-sm font-semibold">Menunggu Validasi LPM</span>
                                </div>
                            @else
                                <p class="text-xs text-gray-500">Belum masuk antrean LPM.</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if($finalDraft->latestReview)
            <div class="mt-6">
                <x-final-draft-review-summary :review="$finalDraft->latestReview" />
            </div>
        @endif

        <div class="mt-6">
            <x-final-draft-activity-log :logs="$finalDraft->activityLogs" viewer="reviewer" />
        </div>

    </div>
</div>
@endsection
