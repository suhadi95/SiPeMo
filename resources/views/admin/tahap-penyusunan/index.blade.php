@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Kelola Periode Tahap Penyusunan') }}
    </h2>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        {{-- Template Modul --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-4 sm:p-6 text-gray-900">
                <h3 class="text-lg font-medium text-gray-900 mb-1">Template Modul</h3>
                <p class="text-sm text-gray-500 mb-4">
                    Masukkan link Google Drive template modul. Link ini akan ditampilkan di dashboard dan halaman penyusunan penyusun.
                </p>

                @if($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.tahap-penyusunan.template.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="flex flex-col sm:flex-row sm:items-end gap-3">
                        <div class="flex-1 min-w-0">
                            <label for="template_modul_url" class="block text-sm font-medium text-gray-700 mb-1">
                                Link Template Modul (Google Drive)
                            </label>
                            <input type="url"
                                   name="template_modul_url"
                                   id="template_modul_url"
                                   value="{{ old('template_modul_url', $templateModulUrl) }}"
                                   placeholder="https://drive.google.com/..."
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <button type="submit"
                                class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded whitespace-nowrap">
                            Simpan Template
                        </button>
                    </div>
                </form>

                @if($templateModulUrl)
                    <p class="mt-3 text-sm text-gray-600">
                        Template saat ini:
                        <a href="{{ $templateModulUrl }}" target="_blank" rel="noopener noreferrer"
                           class="text-blue-600 hover:text-blue-800 underline break-all">
                            {{ $templateModulUrl }}
                        </a>
                    </p>
                @endif
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-4 sm:p-6 text-gray-900">
                @if($tahaps->count() > 0)
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Periode Tahap Penyusunan</h3>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ $tahaps->first()->nama_periode }} · {{ $tahaps->count() }} tahap
                            </p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                            <a href="{{ route('admin.tahap-penyusunan.periode.edit') }}"
                               class="inline-flex justify-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                Edit Periode
                            </a>
                            <button type="button"
                                    onclick="openDeletePeriodeModal()"
                                    class="w-full sm:w-auto bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded text-sm">
                                Hapus Periode
                            </button>
                            <button type="button"
                                    onclick="openResetModal()"
                                    class="w-full sm:w-auto bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm">
                                Reset Periode
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahap</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Deskripsi</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Mulai</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Selesai</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($tahaps as $tahap)
                                    <tr class="{{ $tahap->is_active ? 'bg-green-50' : 'hover:bg-gray-50' }}">
                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $tahap->nama_tahap }}</div>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $tahap->nama_periode }}</div>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 hidden md:table-cell">
                                            <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $tahap->deskripsi }}">
                                                {{ $tahap->deskripsi }}
                                            </div>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $tahap->tanggal_mulai->format('d/m/Y') }}</div>
                                            <div class="text-xs text-gray-500">{{ formatTanggalLengkapIndonesia($tahap->tanggal_mulai) }}</div>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $tahap->tanggal_selesai->format('d/m/Y') }}</div>
                                            <div class="text-xs text-gray-500">{{ formatTanggalLengkapIndonesia($tahap->tanggal_selesai) }}</div>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                            @if($tahap->is_active)
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                            @else
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex flex-wrap gap-2">
                                                <a href="{{ route('admin.tahap-penyusunan.edit', $tahap) }}"
                                                   class="text-blue-600 hover:text-blue-900 bg-blue-100 hover:bg-blue-200 px-3 py-1 rounded text-xs">
                                                    Edit Tahap
                                                </a>
                                                @if(!$tahap->is_active)
                                                    <form method="POST" action="{{ route('admin.tahap-penyusunan.activate', $tahap) }}" class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                                class="text-green-600 hover:text-green-900 bg-green-100 hover:bg-green-200 px-3 py-1 rounded text-xs"
                                                                onclick="return confirm('Apakah Anda yakin ingin mengaktifkan tahap ini? Tanggal mulai akan diubah ke hari ini atau hari sebelumnya.')">
                                                            Aktifkan
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500 mb-4">Belum ada periode tahap penyusunan yang dibuat.</p>
                        <a href="{{ route('admin.tahap-penyusunan.create') }}"
                           class="inline-flex bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Buat Periode Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus Periode -->
<div id="deletePeriodeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white" style="max-width: calc(100% - 2rem);">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">Konfirmasi Hapus Periode</h3>
            <div class="mt-2 px-4 py-3 text-left">
                <p class="text-sm text-gray-600 mb-2">
                    Hapus periode dan progres penyusunan saja?
                    <strong class="text-yellow-700">Tindakan ini tidak dapat dibatalkan.</strong>
                </p>
                <ul class="text-sm text-gray-600 list-disc list-inside space-y-1">
                    <li>Periode &amp; tahap penyusunan dihapus</li>
                    <li>Progres modul, final draft, publikasi, dan file upload terkait dihapus</li>
                    <li>Akun serta data pendaftaran <strong>penyusun &amp; reviewer tetap</strong></li>
                    <li>Jurusan, Mata Kuliah, Admin, dan LPM tetap</li>
                </ul>
            </div>
            <div class="flex justify-center gap-2 px-4 py-3">
                <button type="button"
                        onclick="closeDeletePeriodeModal()"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Batal
                </button>
                <form method="POST" action="{{ route('admin.tahap-penyusunan.periode.destroy') }}" class="inline">
                    @csrf
                    <button type="submit"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">
                        Ya, Hapus Periode
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Reset -->
<div id="resetModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white" style="max-width: calc(100% - 2rem);">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">Konfirmasi Reset Periode</h3>
            <div class="mt-2 px-4 py-3 text-left">
                <p class="text-sm text-gray-600 mb-2">
                    Apakah Anda yakin ingin mereset periode penyusunan?
                    <strong class="text-red-600">Tindakan ini tidak dapat dibatalkan.</strong>
                </p>
                <ul class="text-sm text-gray-600 list-disc list-inside space-y-1">
                    <li>Semua progres penyusunan (modul, final draft, publikasi) akan dihapus</li>
                    <li>Semua file yang diupload akan dihapus dari server</li>
                    <li>Aplikasi dan akun User penyusun &amp; reviewer akan dihapus (harus daftar ulang)</li>
                    <li>Yang <strong>tetap</strong>: akun Admin &amp; LPM, data Jurusan, dan Mata Kuliah</li>
                </ul>
                <p class="text-xs text-gray-500 mt-3">
                    Jika hanya ingin menghapus periode tanpa menghapus akun, gunakan <strong>Hapus Periode</strong>.
                </p>
            </div>
            <div class="flex justify-center gap-2 px-4 py-3">
                <button type="button"
                        onclick="closeResetModal()"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Batal
                </button>
                <form method="POST" action="{{ route('admin.tahap-penyusunan.reset') }}" class="inline">
                    @csrf
                    <button type="submit"
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Ya, Reset Periode
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openResetModal() {
    document.getElementById('resetModal').classList.remove('hidden');
}

function closeResetModal() {
    document.getElementById('resetModal').classList.add('hidden');
}

function openDeletePeriodeModal() {
    document.getElementById('deletePeriodeModal').classList.remove('hidden');
}

function closeDeletePeriodeModal() {
    document.getElementById('deletePeriodeModal').classList.add('hidden');
}

document.getElementById('resetModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeResetModal();
    }
});

document.getElementById('deletePeriodeModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeletePeriodeModal();
    }
});
</script>
@endsection
