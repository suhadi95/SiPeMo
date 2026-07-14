@extends('layouts.app')

@section('header')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Penyusunan Modul') }}
        </h2>
    </div>
</div>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Penyusun Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Penyusun</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $totalPenyusun }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menunggu Validasi Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Menunggu Validasi</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $menungguValidasi }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Selesai Penyusunan Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Selesai Penyusunan</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $selesaiPenyusunan }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                                                Nama
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Judul Modul
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Mata Kuliah
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Aksi
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($penyusuns as $index => $penyusun)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $index + 1 }}
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
                                                    <div class="text-sm text-gray-900 font-medium">
                                                        {{ $penyusun->mataKuliah?->nama_mata_kuliah ?? ($penyusun->mata_kuliah ?? 'Mata Kuliah dihapus') }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        Semester {{ $penyusun->mataKuliah?->semester ?? '-' }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @php
                                                        $totalTahap = \App\Models\TahapPenyusunan::global()->count();
                                                        $uploadedModuls = $penyusun->moduls->keyBy(function($modul) {
                                                            return $modul->tahapPenyusunan->tahap ?? null;
                                                        });
                                                        $statusText = '';
                                                        $statusClass = '';
                                                        $foundStatus = false;

                                                        for ($i = 1; $i <= $totalTahap; $i++) {
                                                            if (!isset($uploadedModuls[$i])) {
                                                                $statusText = "Tahap {$i} Belum Diunggah";
                                                                $statusClass = 'bg-gray-100 text-gray-800';
                                                                $foundStatus = true;
                                                                break;
                                                            } elseif ($uploadedModuls[$i]->status == 'rejected') {
                                                                $statusText = "Ditolak Tahap {$i}";
                                                                $statusClass = 'bg-red-100 text-red-800';
                                                                $foundStatus = true;
                                                                break;
                                                            } elseif ($uploadedModuls[$i]->status == 'pending') {
                                                                $statusText = "Validasi Tahap {$i}";
                                                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                                                $foundStatus = true;
                                                                break;
                                                            }
                                                        }

                                                        if (!$foundStatus) {
                                                            // All stages are uploaded and approved
                                                            $statusText = 'Selesai';
                                                            $statusClass = 'bg-green-100 text-green-800';
                                                        }
                                                    @endphp
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                                        {{ $statusText }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex space-x-2">
                                                        <a href="{{ route('admin.modul.show', $penyusun->id) }}" 
                                                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-xs">
                                                            Detail
                                                        </a>
                                                        @if($penyusun->moduls->count() > 0)
                                                            @php
                                                                $latestModul = $penyusun->moduls->sortByDesc('created_at')->first();
                                                            @endphp
                                                            @if($latestModul->file_path)
                                                                <a href="{{ route('admin.modul.download', $latestModul) }}" 
                                                                   class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-xs">
                                                                    Download
                                                                </a>
                                                            @endif
                                                            @if($latestModul->status == 'pending')
                                                                <form method="POST" action="{{ route('admin.modul.approve', $latestModul) }}" class="inline">
                                                                    @csrf
                                                                    <button type="submit" 
                                                                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-xs"
                                                                            onclick="return confirm('Apakah Anda yakin ingin menyetujui modul ini?')">
                                                                        Setujui
                                                                    </button>
                                                                </form>
                                                                <button type="button"
                                                                        class="reject-btn bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-xs"
                                                                        data-modul-id="{{ $latestModul->id }}"
                                                                        data-modul-title="{{ $latestModul->judul_modul }}">
                                                                    Tolak
                                                                </button>
                                                            @endif
                                                        @endif
                                                    </div>
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
        @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center">
                    <p class="text-gray-500">Belum ada jurusan yang terdaftar.</p>
                </div>
            </div>
        @endif
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
    
    // Make closeRejectModal available globally
    window.closeRejectModal = closeRejectModal;

    // Close modal when clicking outside
    document.getElementById('rejectModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeRejectModal();
        }
    });

    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeRejectModal();
        }
    });
});
</script>
@endsection
@endsection
