@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ ($isEditing ?? false) ? 'Edit Laporan Validasi' : 'Lengkapi Laporan Validasi' }}
    </h2>
@endsection

@section('content')
<div class="py-4 sm:py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-4">
            <a href="{{ route('reviewer.final-draft.show', $finalDraft) }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-900">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Detail Draft
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 p-4 sm:p-6 mb-4">
            <h3 class="text-base font-semibold text-gray-900 mb-2">Ringkasan Penilaian</h3>
            <p class="text-sm text-gray-600 mb-3">
                Penilaian Anda: <strong class="text-green-700">{{ $review->hasil_penilaian_label }}</strong>.
                @if($isEditing ?? false)
                    Perbarui data laporan validasi di bawah ini jika ada kesalahan input.
                @else
                    Lengkapi data berikut untuk menghasilkan laporan validasi PDF.
                @endif
            </p>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
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

        <form method="POST" action="{{ route('reviewer.final-draft.validation-report.store', $finalDraft) }}" class="space-y-4" id="validation-report-form">
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
                <h3 class="text-sm font-semibold text-gray-900">D. Penilaian Umum</h3>
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

            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 p-4 sm:p-6 space-y-3">
                <h3 class="text-sm font-semibold text-gray-900">E. Tanda Tangan Validator</h3>
                @if(($isEditing ?? false) && $review->signatureDataUri())
                    <div class="mb-2">
                        <p class="text-xs text-gray-500 mb-1">Tanda tangan saat ini:</p>
                        <div class="border border-gray-200 rounded-md bg-gray-50 p-2 inline-block">
                            <img src="{{ $review->signatureDataUri() }}" alt="Tanda tangan saat ini" class="max-h-20">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Gambar ulang di bawah hanya jika ingin mengganti tanda tangan.</p>
                    </div>
                @else
                    <p class="text-xs text-gray-500">Gambar tanda tangan Anda pada kotak di bawah menggunakan mouse atau sentuhan layar.</p>
                @endif

                <div class="border border-gray-300 rounded-md bg-white overflow-hidden">
                    <canvas id="signature-pad" class="w-full touch-none" style="height: 180px;"></canvas>
                </div>
                <input type="hidden" name="signature" id="signature-input" value="{{ old('signature') }}">
                @error('signature')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror

                <div class="flex gap-2">
                    <button type="button" id="clear-signature"
                            class="px-3 py-1.5 border border-gray-300 text-sm rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Hapus Tanda Tangan
                    </button>
                </div>

                <div class="text-sm text-gray-600">
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
                    {{ ($isEditing ?? false) ? 'Simpan Perubahan' : 'Simpan Laporan & Selesai' }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('signature-pad');
    const hiddenInput = document.getElementById('signature-input');
    const form = document.getElementById('validation-report-form');
    const clearBtn = document.getElementById('clear-signature');
    const ctx = canvas.getContext('2d');
    const hasExistingSignature = @json(($isEditing ?? false) && $review->signatureDataUri());
    let drawing = false;
    let hasDrawn = false;
    let signatureCleared = false;

    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        const rect = canvas.getBoundingClientRect();
        canvas.width = rect.width * ratio;
        canvas.height = rect.height * ratio;
        ctx.scale(ratio, ratio);
        ctx.lineWidth = 2;
        ctx.lineCap = 'round';
        ctx.strokeStyle = '#111827';
    }

    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);

    function getPos(event) {
        const rect = canvas.getBoundingClientRect();
        const clientX = event.touches ? event.touches[0].clientX : event.clientX;
        const clientY = event.touches ? event.touches[0].clientY : event.clientY;
        return { x: clientX - rect.left, y: clientY - rect.top };
    }

    function startDraw(event) {
        drawing = true;
        const pos = getPos(event);
        ctx.beginPath();
        ctx.moveTo(pos.x, pos.y);
        event.preventDefault();
    }

    function draw(event) {
        if (!drawing) return;
        const pos = getPos(event);
        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();
        hasDrawn = true;
        signatureCleared = false;
        event.preventDefault();
    }

    function stopDraw() {
        drawing = false;
    }

    canvas.addEventListener('mousedown', startDraw);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDraw);
    canvas.addEventListener('mouseleave', stopDraw);
    canvas.addEventListener('touchstart', startDraw, { passive: false });
    canvas.addEventListener('touchmove', draw, { passive: false });
    canvas.addEventListener('touchend', stopDraw);

    clearBtn.addEventListener('click', function () {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        hiddenInput.value = '';
        hasDrawn = false;
        signatureCleared = true;
    });

    form.addEventListener('submit', function (event) {
        if (hasDrawn) {
            hiddenInput.value = canvas.toDataURL('image/png');
            return;
        }

        if (hasExistingSignature && !signatureCleared) {
            hiddenInput.value = '';
            return;
        }

        event.preventDefault();
        alert('Tanda tangan wajib diisi.');
    });
});
</script>
@endsection
