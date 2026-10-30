@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Detail Pendaftaran Reviewer
    </h2>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
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

        @if(session('status'))
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
                {{ session('status') }}
            </div>
        @endif

        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('admin.reviewer.index') }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-900">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar Reviewer
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Card: Detail Reviewer -->
            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 p-6 h-fit">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100">Profil Pendaftar</h3>
                
                <div class="space-y-4">
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 uppercase">Nama Lengkap</span>
                        <span class="text-sm font-medium text-gray-900">{{ $application->nama_reviewer }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 uppercase">Email</span>
                        <span class="text-sm font-medium text-gray-900">{{ $application->email }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 uppercase">No WhatsApp</span>
                        <span class="text-sm font-medium text-gray-900">{{ $application->no_wa }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 uppercase">NIDN</span>
                        <span class="text-sm font-medium text-gray-900">{{ $application->nidn }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 uppercase">NIP</span>
                        <span class="text-sm font-medium text-gray-900">{{ $application->nip ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 uppercase">Rekening Bank</span>
                        <span class="text-sm font-medium text-gray-900">
                            @if($application->nomor_rekening)
                                <span class="font-semibold">{{ $application->nama_bank ?? 'Bank' }}</span> - {{ $application->nomor_rekening }}
                                @if($application->nama_pemilik_rekening)
                                    <span class="block text-xs text-gray-500">a.n. {{ $application->nama_pemilik_rekening }}</span>
                                @endif
                            @else
                                <span class="text-gray-400 italic">Belum diisi</span>
                            @endif
                        </span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 uppercase">Status Pengajuan</span>
                        <span class="text-sm">
                            @if($application->status === 'approved')
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Diterima</span>
                            @elseif($application->status === 'rejected')
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Ditolak</span>
                            @else
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                            @endif
                        </span>
                    </div>
                    @if($application->rejection_reason)
                        <div>
                            <span class="block text-xs font-semibold text-red-500 uppercase">Alasan Penolakan</span>
                            <span class="text-sm text-red-700 bg-red-50 p-2 rounded block mt-1 border border-red-200">{{ $application->rejection_reason }}</span>
                        </div>
                    @endif
                    
                    <div class="pt-4 border-t border-gray-100 space-y-2">
                        <a href="{{ route('admin.reviewer.download-certification', $application) }}" 
                           class="w-full flex items-center justify-center px-4 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Unduh Sertifikat Keahlian
                        </a>

                        @if($application->status === 'approved')
                            <a href="{{ route('admin.reviewer.download-history-pdf', $application) }}"
                               class="w-full flex items-center justify-center px-4 py-2 border border-indigo-300 text-sm font-medium rounded-md text-indigo-700 bg-indigo-50 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                Unduh Riwayat Review (PDF)
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Card: Actions or Review History -->
            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 p-6 lg:col-span-2">
                @if($application->status === 'pending')
                    <div x-data="{ activeTab: 'approve' }">
                        <div class="flex gap-4 mb-6">
                            <button type="button" @click="activeTab = 'approve'" :class="activeTab === 'approve' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'" class="w-1/2 py-2.5 text-center font-semibold rounded-lg text-sm transition-all focus:outline-none">
                                Setujui Pendaftaran Reviewer
                            </button>
                            <button type="button" @click="activeTab = 'reject'" :class="activeTab === 'reject' ? 'bg-red-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'" class="w-1/2 py-2.5 text-center font-semibold rounded-lg text-sm transition-all focus:outline-none">
                                Tolak Pengajuan
                            </button>
                        </div>

                        <!-- Form Approve -->
                        <div x-show="activeTab === 'approve'">
                            <form method="POST" action="{{ route('admin.reviewer.approve', $application) }}">
                                @csrf
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 text-sm text-blue-900">
                                    <p class="font-semibold mb-1">Konfirmasi Persetujuan Reviewer</p>
                                    <p class="text-xs text-blue-800">
                                        Dengan menyetujui, akun reviewer akan dibuat/diaktifkan. Penetapan modul untuk direview dilakukan secara fleksibel melalui halaman <strong>Monitoring Final Draft Modul</strong>.
                                    </p>
                                </div>

                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md text-sm shadow-sm transition">
                                    Setujui Reviewer Ini
                                </button>
                            </form>
                        </div>

                        <!-- Form Reject -->
                        <div x-show="activeTab === 'reject'">
                            <form method="POST" action="{{ route('admin.reviewer.reject', $application) }}">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-1">Alasan Penolakan</label>
                                        <textarea id="rejection_reason" name="rejection_reason" rows="4" class="w-full border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500 text-sm" placeholder="Tuliskan alasan penolakan berkas pendaftar..." required></textarea>
                                    </div>
                                    
                                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-md text-sm shadow-sm transition">
                                        Tolak Pengajuan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                @elseif($application->status === 'approved')
                    <div class="space-y-6">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                            <div>
                                <h3 class="text-base font-semibold text-gray-900">Riwayat Penilaian Reviewer</h3>
                                <p class="text-xs text-gray-500">Daftar seluruh modul yang telah dinilai oleh reviewer ini.</p>
                            </div>
                            <a href="{{ route('admin.reviewer.download-history-pdf', $application) }}"
                               class="inline-flex items-center px-3 py-1.5 border border-indigo-300 rounded-md text-xs font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Cetak Riwayat (PDF)
                            </a>
                        </div>

                        @if($reviews->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 text-sm">
                                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                                        <tr>
                                            <th class="px-4 py-2 text-left">No</th>
                                            <th class="px-4 py-2 text-left">Judul Modul</th>
                                            <th class="px-4 py-2 text-left">Penyusun</th>
                                            <th class="px-4 py-2 text-center">Skor Likert</th>
                                            <th class="px-4 py-2 text-center">Keputusan</th>
                                            <th class="px-4 py-2 text-left">Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($reviews as $index => $r)
                                            <tr>
                                                <td class="px-4 py-2.5 text-gray-500">{{ $index + 1 }}</td>
                                                <td class="px-4 py-2.5 font-medium text-gray-900">
                                                    {{ $r->finalDraft->judul_modul ?? '-' }}
                                                    <div class="text-xs text-gray-400">{{ $r->finalDraft->mataKuliah->nama_mata_kuliah ?? '-' }}</div>
                                                </td>
                                                <td class="px-4 py-2.5 text-gray-700">
                                                    {{ $r->finalDraft->penyusunApplication->nama_penyusun ?? '-' }}
                                                </td>
                                                <td class="px-4 py-2.5 text-center">
                                                    <span class="font-bold text-gray-900">{{ $r->total_skor }}</span>
                                                    <span class="text-xs text-gray-400">/{{ $r->maksimal_skor }}</span>
                                                    <div class="text-xs font-semibold text-blue-600">({{ $r->persentase_skor }}%)</div>
                                                </td>
                                                <td class="px-4 py-2.5 text-center">
                                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $r->isSangatLayak() ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                                        {{ $r->hasil_penilaian_label }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-2.5 text-xs text-gray-500">
                                                    {{ $r->submitted_at->format('d M Y') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-6 text-center text-sm text-gray-500 bg-gray-50 rounded-lg">
                                Reviewer ini belum menyelesaikan penilaian modul apa pun.
                            </div>
                        @endif

                        <!-- Modul yang Sedang Ditugaskan -->
                        <div class="pt-4 border-t border-gray-100">
                            <h4 class="text-sm font-semibold text-gray-900 mb-2">Modul yang Sedang Ditugaskan</h4>
                            <p class="text-xs text-gray-500 mb-3">Untuk menambah atau mengubah penugasan reviewer pada modul, gunakan halaman <a href="{{ route('admin.final-draft.index') }}" class="text-blue-600 hover:underline">Monitoring Final Draft Modul</a>.</p>
                            
                            @if($assignedApplications->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach($assignedApplications as $app)
                                        <div class="p-3 border border-gray-200 rounded-lg bg-gray-50 text-xs">
                                            <div class="font-semibold text-gray-900">{{ $app->judul_bahan_ajar }}</div>
                                            <div class="text-gray-600 mt-0.5">{{ $app->mataKuliah->nama_mata_kuliah }} · {{ $app->nama_penyusun }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-xs text-gray-400 italic">Belum ada modul yang ditugaskan ke reviewer ini.</p>
                            @endif
                        </div>
                    </div>

                @else
                    <div class="p-6 text-center text-sm text-gray-500 bg-gray-50 rounded-lg">
                        Pendaftaran reviewer ini telah ditolak.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
