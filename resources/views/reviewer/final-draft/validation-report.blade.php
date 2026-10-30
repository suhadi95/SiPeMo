@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Laporan Validasi Bahan Ajar Modular
    </h2>
@endsection

@section('content')
<div class="py-4 sm:py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <a href="{{ route('reviewer.final-draft.show', $finalDraft) }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-900">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Detail Draft
            </a>
            <div class="text-sm text-gray-500">
                FD-{{ str_pad($finalDraft->id, 5, '0', STR_PAD_LEFT) }} · {{ $finalDraft->judul_modul }}
            </div>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <p class="font-medium text-sm mb-1">Periksa kembali isian formulir:</p>
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-blue-50 border border-blue-200 text-blue-900 px-4 py-3 rounded-lg mb-4 text-sm">
            <p class="font-semibold mb-1">Form Laporan Validasi</p>
            <p class="text-xs text-blue-800">
                Lengkapi identitas validator, rekomendasi umum, dan unggah berkas gambar tanda tangan Anda untuk menghasilkan PDF Laporan Validasi resmi.
            </p>
        </div>

        <!-- Ringkasan Informasi Modul -->
        <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 p-4 sm:p-6 mb-4">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">A. Informasi Bahan Ajar</h3>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2 text-sm">
                <div>
                    <dt class="text-gray-500">Judul Modul</dt>
                    <dd class="font-medium text-gray-900">{{ $finalDraft->judul_modul }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Mata Kuliah</dt>
                    <dd class="font-medium text-gray-900">{{ $finalDraft->mataKuliah->nama_mata_kuliah }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">SKS</dt>
                    <dd class="font-medium text-gray-900">{{ $finalDraft->mataKuliah->sks ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Jumlah Bab (Modul)</dt>
                    <dd class="font-medium text-gray-900">{{ $finalDraft->mataKuliah?->jumlahBab() ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Penulis / Pengembang</dt>
                    <dd class="font-medium text-gray-900">{{ $finalDraft->penyusunApplication->nama_penyusun ?? '-' }}</dd>
                </div>
            </dl>
        </div>

        <form method="POST" action="{{ route('reviewer.final-draft.validation-report.store', $finalDraft) }}" enctype="multipart/form-data" class="space-y-4" id="validation-report-form">
            @csrf

            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 p-4 sm:p-6 space-y-4">
                <h3 class="text-sm font-semibold text-gray-900">B. Identitas Validator</h3>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Validator</label>
                    <input type="text" value="{{ $review->reviewer->name ?? auth()->user()->name }}" readonly
                           class="w-full border-gray-200 bg-gray-50 rounded-md text-sm text-gray-700">
                </div>

                <div>
                    <label for="validator_institusi" class="block text-sm font-medium text-gray-700 mb-1">
                        Institusi / Unit Kerja <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="validator_institusi" name="validator_institusi"
                           value="{{ old('validator_institusi', $review->validator_institusi) }}"
                           class="w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm"
                           placeholder="Contoh: LP2M UINSSC" required>
                    @error('validator_institusi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="validator_bidang_keahlian" class="block text-sm font-medium text-gray-700 mb-1">
                        Bidang Keahlian <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="validator_bidang_keahlian" name="validator_bidang_keahlian"
                           value="{{ old('validator_bidang_keahlian', $review->validator_bidang_keahlian) }}"
                           class="w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm"
                           placeholder="Contoh: Pendidikan Bahasa Arab" required>
                    @error('validator_bidang_keahlian')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="validator_kontak" class="block text-sm font-medium text-gray-700 mb-1">
                        Email / Kontak <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="validator_kontak" name="validator_kontak"
                           value="{{ old('validator_kontak', $review->validator_email_kontak ?? ($reviewerApp->email ?? auth()->user()->email)) }}"
                           class="w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm"
                           placeholder="email@uinssc.ac.id / 08xxxxxxxxxx" required>
                    @error('validator_kontak')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 p-4 sm:p-6 space-y-3">
                <h3 class="text-sm font-semibold text-gray-900">C. Penilaian Umum</h3>
                <div>
                    <label for="rekomendasi_validator" class="block text-sm font-medium text-gray-700 mb-1">
                        Rekomendasi Validator <span class="text-red-500">*</span>
                    </label>
                    <textarea id="rekomendasi_validator" name="rekomendasi_validator" rows="4"
                              class="w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm"
                              placeholder="Tuliskan rekomendasi umum terhadap modul yang telah divalidasi..."
                              required>{{ old('rekomendasi_validator', $review->rekomendasi_validator) }}</textarea>
                    @error('rekomendasi_validator')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Bagian Unggah Berkas Gambar Tanda Tangan -->
            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 p-4 sm:p-6 space-y-4">
                <h3 class="text-sm font-semibold text-gray-900">D. Tanda Tangan Validator (Unggah Berkas Gambar)</h3>
                
                @if(($isEditing ?? false) && $review->signatureDataUri())
                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg">
                        <p class="text-xs font-semibold text-gray-600 mb-1">Tanda tangan saat ini:</p>
                        <div class="inline-block bg-white p-2 border border-gray-200 rounded">
                            <img src="{{ $review->signatureDataUri() }}" alt="Tanda tangan tersimpan" class="max-h-24 max-w-xs object-contain">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Unggah berkas baru di bawah hanya jika ingin memperbarui tanda tangan.</p>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Unggah Berkas Gambar Tanda Tangan
                        @if(!($isEditing ?? false) || !$review->validator_signature)
                            <span class="text-red-500">*</span>
                        @endif
                    </label>
                    <p class="text-xs text-gray-500 mb-2">
                        Format berkas yang didukung: <strong>JPG, PNG, WEBP, atau SVG</strong> (maksimal 3MB). Disarankan berlatar belakang transparan atau putih bersih.
                    </p>

                    <div class="flex items-center gap-4">
                        <input type="file"
                               name="signature_file"
                               id="signature_file"
                               accept="image/png,image/jpeg,image/jpg,image/webp,image/svg+xml"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer"
                               {{ (!($isEditing ?? false) || !$review->validator_signature) ? 'required' : '' }}
                               onchange="previewSignature(this)">
                    </div>

                    <!-- Pratinjau berkas baru yang dipilih -->
                    <div id="new-signature-preview-container" class="hidden mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-xs font-semibold text-blue-800 mb-1">Pratinjau Berkas Terpilih:</p>
                        <img id="new-signature-preview" class="max-h-24 max-w-xs object-contain bg-white p-2 border border-gray-200 rounded" alt="Preview Tanda Tangan">
                    </div>

                    @error('signature_file')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="text-sm text-gray-600 pt-2 border-t border-gray-100">
                    <p><strong>Nama:</strong> {{ $review->reviewer->name ?? auth()->user()->name }}</p>
                    <p><strong>Tanggal:</strong> {{ ($review->validator_report_completed_at ?? now())->format('d F Y') }}</p>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2">
                <a href="{{ route('reviewer.final-draft.show', $finalDraft) }}"
                   class="inline-flex justify-center px-4 py-2.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex justify-center items-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md text-sm shadow-sm transition">
                    {{ ($isEditing ?? false) ? 'Simpan Perubahan Laporan' : 'Simpan Laporan & Selesai' }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewSignature(input) {
    const container = document.getElementById('new-signature-preview-container');
    const preview = document.getElementById('new-signature-preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            container.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        container.classList.add('hidden');
    }
}
</script>
@endsection
