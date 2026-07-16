@extends('layouts.app')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ $existingFinalDraft && in_array($existingFinalDraft->status, ['rejected', 'rejected_by_reviewer'], true) ? 'Upload Ulang Final Draft Modul' : 'Upload Final Draft Modul' }}
</h2>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <!-- Info Card -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-purple-100 mb-6">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-purple-800">
                            {{ $existingFinalDraft && in_array($existingFinalDraft->status, ['rejected', 'rejected_by_reviewer'], true) ? 'Petunjuk Upload Ulang Final Draft' : 'Petunjuk Upload Final Draft' }}
                        </h3>
                        <div class="mt-2 text-sm text-purple-700">
                            @if($existingFinalDraft && $existingFinalDraft->status === 'rejected')
                            <div class="mb-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-red-800 font-medium">Final draft sebelumnya ditolak oleh LPM</p>
                                <p class="text-red-700 mt-1 text-xs">Setelah upload ulang, draft akan langsung masuk antrean LPM (tanpa ulang review reviewer).</p>
                                @if($existingFinalDraft->catatan_lpm)
                                <p class="text-red-700 mt-1"><strong>Catatan LPM:</strong> {{ $existingFinalDraft->catatan_lpm }}</p>
                                @endif
                            </div>
                            @elseif($existingFinalDraft && $existingFinalDraft->status === 'rejected_by_reviewer')
                            <div class="mb-3 p-3 bg-orange-50 border border-orange-200 rounded-lg">
                                <p class="text-orange-800 font-medium">
                                    Final draft perlu diperbaiki
                                    @if($existingFinalDraft->hasil_penilaian_label)
                                        ({{ $existingFinalDraft->hasil_penilaian_label }})
                                    @endif
                                </p>
                                <p class="text-orange-700 mt-1 text-xs">Setelah upload ulang, draft akan dinilai ulang oleh reviewer.</p>
                                @if($existingFinalDraft->catatan_reviewer)
                                <p class="text-orange-700 mt-1"><strong>Catatan Reviewer:</strong> {{ $existingFinalDraft->catatan_reviewer }}</p>
                                @endif
                            </div>
                            @endif
                            <ul class="list-disc list-inside space-y-1">
                                <li>Pastikan semua 4 tahap penyusunan sudah divalidasi</li>
                                <li>File harus dalam format .doc atau .docx</li>
                                <li>Ukuran file maksimal 25MB</li>
                                <li>
                                    @if($existingFinalDraft && $existingFinalDraft->status === 'rejected')
                                        Final draft akan divalidasi ulang oleh LPM
                                    @else
                                        Final draft akan dinilai oleh reviewer, lalu divalidasi LPM
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-gray-200">
            <div class="p-6">
                <form action="{{ route('penyusun.final-draft.store') }}" method="POST" enctype="multipart/form-data" id="finalDraftForm">
                    @csrf

                    <!-- Judul Modul -->
                    <div class="mb-6">
                        <label for="judul_modul" class="block text-sm font-medium text-gray-700 mb-2">
                            Judul Modul <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                            name="judul_modul"
                            id="judul_modul"
                            value="{{ old('judul_modul', $existingFinalDraft->judul_modul ?? '') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm @error('judul_modul') border-red-300 @enderror"
                            placeholder="Masukkan judul modul"
                            required>
                        @error('judul_modul')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi Modul -->
                    <div class="mb-6">
                        <label for="deskripsi_modul" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi Modul
                        </label>
                        <textarea name="deskripsi_modul"
                            id="deskripsi_modul"
                            rows="4"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm @error('deskripsi_modul') border-red-300 @enderror"
                            placeholder="Masukkan deskripsi modul (opsional)">{{ old('deskripsi_modul', $existingFinalDraft->deskripsi_modul ?? '') }}</textarea>
                        @error('deskripsi_modul')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- File Upload -->
                    <div class="mb-6">
                        <label for="file_modul" class="block text-sm font-medium text-gray-700 mb-2">
                            File Modul <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors" id="dropZone">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="file_modul" class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-purple-500">
                                        <span>Upload file</span>
                                        <input id="file_modul"
                                            name="file_modul"
                                            type="file"
                                            class="sr-only"
                                            accept=".doc,.docx"
                                            required>
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">
                                    DOC, DOCX hingga 25MB
                                </p>
                            </div>
                        </div>
                        <div id="fileInfo" class="mt-2 text-sm text-gray-600 hidden">
                            <p><strong>File:</strong> <span id="fileName"></span></p>
                            <p><strong>Ukuran:</strong> <span id="fileSize"></span></p>
                        </div>
                        @error('file_modul')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end space-x-3">
                        <a href="{{ route('penyusun.final-draft.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500"
                            id="submitBtn">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            Upload Final Draft
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('file_modul');
        const dropZone = document.getElementById('dropZone');
        const fileInfo = document.getElementById('fileInfo');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');
        const form = document.getElementById('finalDraftForm');
        const submitBtn = document.getElementById('submitBtn');

        // File input change handler
        fileInput.addEventListener('change', function(e) {
            handleFileSelect(e.target.files[0]);
        });

        // Drag and drop handlers
        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            dropZone.classList.add('border-purple-400', 'bg-purple-50');
        });

        dropZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            dropZone.classList.remove('border-purple-400', 'bg-purple-50');
        });

        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            dropZone.classList.remove('border-purple-400', 'bg-purple-50');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelect(files[0]);
            }
        });

        function handleFileSelect(file) {
            if (file) {
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);
                fileInfo.classList.remove('hidden');

                // Validate file type
                const allowedTypes = ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                const allowedExtensions = ['doc', 'docx'];
                const fileExtension = file.name.split('.').pop().toLowerCase();

                if (!allowedTypes.includes(file.type) && !allowedExtensions.includes(fileExtension)) {
                    alert('File harus berupa dokumen dengan format .doc atau .docx');
                    fileInput.value = '';
                    fileInfo.classList.add('hidden');
                    return;
                }

                // Validate file size (25MB)
                if (file.size > 25 * 1024 * 1024) {
                    alert('Ukuran file maksimal 25MB');
                    fileInput.value = '';
                    fileInfo.classList.add('hidden');
                    return;
                }

            }
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Form submission handler
        form.addEventListener('submit', function(e) {
            if (!fileInput.files[0]) {
                e.preventDefault();
                alert('Pilih file modul terlebih dahulu');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Mengupload...';
        });
    });
</script>
@endsection