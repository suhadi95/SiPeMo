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
                        <span class="block text-xs font-semibold text-gray-500 uppercase">Status Pengisian</span>
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
                    </div>
                </div>
            </div>

            <!-- Right Card: Actions & Course Mapping -->
            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 p-6 lg:col-span-2">
                @if($application->status === 'pending')
                    <div x-data="{ activeTab: 'approve' }">
                        <div class="flex gap-4 mb-6">
                            <button type="button" @click="activeTab = 'approve'" :class="activeTab === 'approve' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'" class="w-1/2 py-2.5 text-center font-semibold rounded-lg text-sm transition-all focus:outline-none">
                                Setujui & Atur Mata Kuliah
                            </button>
                            <button type="button" @click="activeTab = 'reject'" :class="activeTab === 'reject' ? 'bg-red-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'" class="w-1/2 py-2.5 text-center font-semibold rounded-lg text-sm transition-all focus:outline-none">
                                Tolak Pengajuan
                            </button>
                        </div>

                        <!-- Form Approve -->
                        <div x-show="activeTab === 'approve'">
                            <form method="POST" action="{{ route('admin.reviewer.approve', $application) }}">
                                @csrf
                                <h3 class="text-base font-semibold text-gray-900 mb-3">Tugaskan Mata Kuliah untuk Ditinjau</h3>
                                <p class="text-xs text-gray-500 mb-4">Pilih satu atau beberapa mata kuliah yang akan ditugaskan ke reviewer ini. 1 Mata Kuliah hanya dapat ditugaskan ke 1 Reviewer.</p>
                                
                                <div class="space-y-6 max-h-[400px] overflow-y-auto border border-gray-200 rounded-lg p-4 mb-6">
                                    @foreach($jurusans as $jurusan)
                                        <div>
                                            <h4 class="text-sm font-bold text-gray-700 bg-gray-100 p-2 rounded mb-2">{{ $jurusan->nama_jurusan }}</h4>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pl-2">
                                                @forelse($jurusan->mataKuliah as $mk)
                                                    <div class="flex items-start">
                                                        <input type="checkbox" name="mata_kuliah_ids[]" value="{{ $mk->id }}" id="mk_{{ $mk->id }}" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-1" />
                                                        <label for="mk_{{ $mk->id }}" class="ml-2 text-xs text-gray-700 leading-tight">
                                                            <strong>{{ $mk->nama_mata_kuliah }}</strong> (Smtr {{ $mk->semester }}, {{ $mk->sks }} SKS)
                                                            @if($mk->reviewer)
                                                                <span class="block text-[10px] text-orange-600 font-medium mt-0.5">Sudah memiliki reviewer: {{ $mk->reviewer->name }}</span>
                                                            @endif
                                                        </label>
                                                    </div>
                                                @empty
                                                    <span class="text-xs text-gray-500 italic pl-2">Tidak ada mata kuliah di jurusan ini.</span>
                                                @endforelse
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md text-sm shadow-sm transition">
                                    Setujui Reviewer & Simpan Penugasan
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
                    <!-- Mengubah Penugasan Mata Kuliah setelah disetujui -->
                    <form method="POST" action="{{ route('admin.reviewer.approve', $application) }}">
                        @csrf
                        <h3 class="text-base font-semibold text-gray-900 mb-2">Penugasan Mata Kuliah</h3>
                        <p class="text-xs text-gray-500 mb-4">Gunakan form di bawah ini untuk memperbarui mata kuliah yang ditinjau oleh reviewer ini.</p>
                        
                        <div class="space-y-6 max-h-[400px] overflow-y-auto border border-gray-200 rounded-lg p-4 mb-6">
                            @foreach($jurusans as $jurusan)
                                <div>
                                    <h4 class="text-sm font-bold text-gray-700 bg-gray-100 p-2 rounded mb-2">{{ $jurusan->nama_jurusan }}</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pl-2">
                                        @forelse($jurusan->mataKuliah as $mk)
                                            <div class="flex items-start">
                                                <input type="checkbox" name="mata_kuliah_ids[]" value="{{ $mk->id }}" id="mk_{{ $mk->id }}" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-1" 
                                                    {{ in_array($mk->id, $assignedMkIds) ? 'checked' : '' }} />
                                                <label for="mk_{{ $mk->id }}" class="ml-2 text-xs text-gray-700 leading-tight">
                                                    <strong>{{ $mk->nama_mata_kuliah }}</strong> (Smtr {{ $mk->semester }}, {{ $mk->sks }} SKS)
                                                    @if($mk->reviewer && $mk->reviewer_id !== $user->id)
                                                        <span class="block text-[10px] text-orange-600 font-medium mt-0.5">Reviewer lain: {{ $mk->reviewer->name }}</span>
                                                    @elseif($mk->reviewer_id === $user->id)
                                                        <span class="block text-[10px] text-green-600 font-medium mt-0.5">Sedang ditugaskan ke reviewer ini</span>
                                                    @endif
                                                </label>
                                            </div>
                                        @empty
                                            <span class="text-xs text-gray-500 italic pl-2">Tidak ada mata kuliah di jurusan ini.</span>
                                        @endforelse
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md text-sm shadow-sm transition">
                            Perbarui Penugasan Mata Kuliah
                        </button>
                    </form>

                    <!-- Danger Zone (Delete) -->
                    <div class="mt-8 pt-6 border-t border-red-200">
                        <h3 class="text-sm font-semibold text-red-600 uppercase mb-2">Danger Zone (Hapus Akun)</h3>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <p class="text-xs text-red-800 mb-3">Menghapus data pendaftar reviewer yang disetujui akan menonaktifkan role Reviewer pengguna ini. Masukkan kata "YA" di bawah ini untuk mengonfirmasi.</p>
                            
                            <form method="POST" action="{{ route('admin.reviewer.destroy', $application) }}" class="flex flex-col sm:flex-row sm:items-center gap-3">
                                @csrf
                                @method('DELETE')
                                <input type="text" name="confirmation" placeholder="Ketik YA di sini..." class="w-full sm:w-48 border-red-300 rounded text-xs focus:ring-red-500 focus:border-red-500" required />
                                <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-md text-xs shadow-sm transition">
                                    Hapus Reviewer
                                </button>
                            </form>
                        </div>
                    </div>

                @elseif($application->status === 'rejected')
                    <!-- Rejected Delete Button (no confirmation string needed) -->
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <p class="text-sm text-gray-600 mb-4">Pengajuan pendaftaran reviewer ini telah ditolak. Anda dapat menghapus berkas pendaftaran dari sistem.</p>
                        
                        <form method="POST" action="{{ route('admin.reviewer.destroy', $application) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex justify-center items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-md text-sm shadow-sm transition">
                                Hapus Berkas Pendaftaran
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
