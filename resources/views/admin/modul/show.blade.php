@extends('layouts.app')

@section('header')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Penyusunan Modul') }}
        </h2>
    </div>
</div>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="mb-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Informasi Penyusun</h3>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.modul.index') }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Kembali
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-3">Data Penyusun</h4>
                            <p><strong>Nama:</strong> {{ $penyusunApplication->nama_penyusun }}</p>
                            <p><strong>Email:</strong> {{ $penyusunApplication->email }}</p>
                            <p><strong>No. WA:</strong> {{ $penyusunApplication->no_wa }}</p>
                            <p><strong>NIP:</strong> {{ $penyusunApplication->nip }}</p>
                            <p><strong>NIDN:</strong> {{ $penyusunApplication->nidn }}</p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-3">Informasi Mata Kuliah</h4>
                            <p><strong>Jurusan:</strong> {{ $penyusunApplication->mataKuliah?->jurusan?->nama_jurusan ?? '-' }}</p>
                            <p><strong>Mata Kuliah:</strong> {{ $penyusunApplication->mataKuliah?->nama_mata_kuliah ?? ($penyusunApplication->mata_kuliah ?? '-') }}</p>
                            <p><strong>Kode:</strong> {{ $penyusunApplication->mataKuliah?->kode_mata_kuliah ?? '-' }}</p>
                            <p><strong>SKS:</strong> {{ $penyusunApplication->mataKuliah?->sks ?? '-' }}</p>
                            <p><strong>Semester:</strong> {{ $penyusunApplication->mataKuliah?->semester ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h4 class="font-medium text-gray-900 mb-3">Detail Penyusunan</h4>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p><strong>Judul Bahan Ajar:</strong> {{ $penyusunApplication->judul_bahan_ajar }}</p>
                        <p><strong>Status Pendaftaran:</strong> 
                            @if($penyusunApplication->status == 'approved')
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Disetujui
                                </span>
                            @elseif($penyusunApplication->status == 'pending')
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Pending
                                </span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Ditolak
                                </span>
                            @endif
                        </p>
                        <p><strong>Tanggal Pendaftaran:</strong> {{ $penyusunApplication->created_at->format('d M Y H:i') }}</p>
                        @if($penyusunApplication->approved_at)
                            <p><strong>Tanggal Disetujui:</strong> {{ $penyusunApplication->approved_at->format('d M Y H:i') }}</p>
                            <p><strong>Disetujui oleh:</strong> {{ $penyusunApplication->validator->name }}</p>
                        @endif
                    </div>
                </div>

                @if($penyusunApplication->moduls->count() > 0)
                    <div class="mb-6">
                        <h4 class="font-medium text-gray-900 mb-3">Daftar Modul yang Diupload</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tahap
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Judul Modul
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Catatan Admin
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tanggal Upload
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Aksi
                                            </th>
                                        </tr>
                                    </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($tahaps as $tahap)
                                        @php
                                            $modul = $penyusunApplication->moduls->where('tahap_id', $tahap->id)->first();
                                        @endphp
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                Tahap {{ $tahap->tahap }}
                                            </td>
                                            @if($modul)
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $modul->judul_modul }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($modul->status == 'pending')
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                            Menunggu Validasi
                                                        </span>
                                                    @elseif($modul->status == 'approved')
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                            Disetujui
                                                        </span>
                                                    @elseif($modul->status == 'rejected')
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                            Ditolak
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    @if($modul->catatan_admin)
                                                        <div class="max-w-xs">
                                                            <p class="text-xs text-gray-700" title="{{ $modul->catatan_admin }}">
                                                                {{ Str::limit($modul->catatan_admin, 80) }}
                                                            </p>
                                                            @if(strlen($modul->catatan_admin) > 80)
                                                                <button type="button" 
                                                                        class="view-note-btn text-blue-600 hover:text-blue-800 text-xs mt-1 underline"
                                                                        data-note="{{ htmlspecialchars($modul->catatan_admin) }}"
                                                                        data-tahap="{{ $tahap->tahap }}">
                                                                    Lihat selengkapnya
                                                                </button>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <span class="text-xs text-gray-400 italic">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $modul->uploaded_at ? $modul->uploaded_at->format('d M Y H:i') : '-' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex space-x-2">
                                                        @if($modul->file_path)
                                                            <a href="{{ route('admin.modul.download', $modul) }}" 
                                                               class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-xs">
                                                                Download
                                                            </a>
                                                        @endif
                                                        @if($modul->status == 'pending')
                                                            <form method="POST" action="{{ route('admin.modul.approve', $modul) }}" class="inline">
                                                                @csrf
                                                                <button type="submit" 
                                                                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-xs"
                                                                        onclick="return confirm('Apakah Anda yakin ingin menyetujui modul ini?')">
                                                                    Setujui
                                                                </button>
                                                            </form>
                                                            <button type="button"
                                                                    class="reject-btn bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-xs"
                                                                    data-modul-id="{{ $modul->id }}"
                                                                    data-modul-title="{{ $modul->judul_modul }}">
                                                                Tolak
                                                            </button>
                                                        @elseif($modul->status == 'approved')
                                                            <form method="POST" action="{{ route('admin.modul.revoke-approval', $modul) }}" class="inline">
                                                                @csrf
                                                                <button type="submit" 
                                                                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-3 rounded text-xs"
                                                                        onclick="return confirm('Apakah Anda yakin ingin membatalkan persetujuan modul ini? Status akan kembali ke menunggu validasi.')">
                                                                    Batalkan
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            @else
                                                <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                    Belum Diunggah
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="mb-6">
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                            <p class="text-gray-500">Belum ada modul yang diupload.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Tolak Modul</h3>
            <div class="mt-2">
                <p class="text-sm text-gray-500 mb-4" id="rejectModulTitle"></p>
                <form id="rejectForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="catatan_admin" class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan untuk Penyusun
                        </label>
                        <textarea id="catatan_admin" 
                                  name="catatan_admin" 
                                  rows="4" 
                                  class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                  placeholder="Masukkan alasan penolakan atau catatan untuk penyusun..."
                                  required></textarea>
                        <p class="mt-1 text-xs text-gray-500">Catatan ini akan terlihat oleh penyusun.</p>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" 
                                onclick="closeRejectModal()"
                                class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                            Tolak Modul
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- View Note Modal -->
<div id="viewNoteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Catatan Admin</h3>
                <button type="button" 
                        onclick="closeViewNoteModal()"
                        class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="mt-2">
                <p class="text-sm text-gray-500 mb-3" id="viewNoteTahap"></p>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-gray-700 whitespace-pre-wrap" id="viewNoteContent"></p>
                </div>
            </div>
            <div class="flex justify-end mt-4">
                <button type="button" 
                        onclick="closeViewNoteModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle reject button clicks
    document.querySelectorAll('.reject-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const modulId = this.getAttribute('data-modul-id');
            const modulTitle = this.getAttribute('data-modul-title');
            openRejectModal(modulId, modulTitle);
        });
    });

    // Handle view note button clicks
    document.querySelectorAll('.view-note-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const note = this.getAttribute('data-note');
            const tahap = this.getAttribute('data-tahap');
            openViewNoteModal(note, tahap);
        });
    });

    function openRejectModal(modulId, modulTitle) {
        document.getElementById('rejectModal').classList.remove('hidden');
        document.getElementById('rejectModulTitle').textContent = 'Modul: ' + modulTitle;
        document.getElementById('rejectForm').action = '/admin/modul/' + modulId + '/reject';
        document.getElementById('catatan_admin').value = '';
        document.getElementById('catatan_admin').focus();
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.getElementById('catatan_admin').value = '';
    }

    function openViewNoteModal(note, tahap) {
        document.getElementById('viewNoteModal').classList.remove('hidden');
        document.getElementById('viewNoteTahap').textContent = 'Catatan untuk Tahap ' + tahap;
        document.getElementById('viewNoteContent').textContent = note;
    }

    function closeViewNoteModal() {
        document.getElementById('viewNoteModal').classList.add('hidden');
    }
    
    // Make functions available globally
    window.closeRejectModal = closeRejectModal;
    window.closeViewNoteModal = closeViewNoteModal;

    // Close reject modal when clicking outside
    document.getElementById('rejectModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeRejectModal();
        }
    });

    // Close view note modal when clicking outside
    document.getElementById('viewNoteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeViewNoteModal();
        }
    });

    // Close modals with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeRejectModal();
            closeViewNoteModal();
        }
    });
});
</script>
@endsection
@endsection