@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Formulir Penilaian Final Draft
    </h2>
@endsection

@section('content')
@php
    $initialScores = [];
    foreach ($aspeks as $a) {
        foreach ($a->activePertanyaans as $p) {
            $val = old("jawaban.{$p->id}.skor");
            if ($val !== null && $val !== '') {
                $initialScores[$p->id] = (int) $val;
            }
        }
    }
@endphp

<div class="py-4 sm:py-6" x-data="{
    scores: @js((object) $initialScores),
    maxScore: {{ (int) ($maksimalSkor ?: 1) }},
    totalQuestions: {{ (int) $totalPertanyaan }},
    get totalScore() {
        return Object.values(this.scores).reduce((sum, val) => sum + (parseInt(val) || 0), 0);
    },
    get answeredCount() {
        return Object.keys(this.scores).filter(k => this.scores[k] > 0).length;
    },
    get percentage() {
        if (!this.maxScore) return 0;
        return ((this.totalScore / this.maxScore) * 100).toFixed(1);
    },
    get isComplete() {
        return this.answeredCount >= this.totalQuestions && this.totalQuestions > 0;
    },
    get isSangatLayak() {
        return parseFloat(this.percentage) >= 80.0;
    }
}">
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

        <!-- Card Modul Info -->
        <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 px-4 py-3 mb-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div class="min-w-0">
                    <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $finalDraft->judul_modul }}</h3>
                    <p class="text-xs text-gray-500 mt-0.5">
                        FD-{{ str_pad($finalDraft->id, 5, '0', STR_PAD_LEFT) }} · {{ $finalDraft->mataKuliah->nama_mata_kuliah }}
                    </p>
                </div>
                <a href="{{ route('reviewer.final-draft.download', $finalDraft) }}"
                   class="shrink-0 inline-flex items-center justify-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Unduh Dokumen
                </a>
            </div>
            <p class="text-xs text-gray-400 mt-2">
                Skala: {{ collect($likertLabels)->map(fn ($label, $skor) => $skor.'='.$label)->implode(' · ') }}
            </p>
        </div>

        <!-- Sticky / Live Score Indicator -->
        <div class="bg-white shadow-sm sm:rounded-lg border border-blue-200 p-4 mb-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div>
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Perhitungan Penilaian Otomatis</h4>
                    <div class="flex items-baseline gap-2 mt-1">
                        <span class="text-2xl font-extrabold text-gray-900" x-text="totalScore">0</span>
                        <span class="text-sm text-gray-500">/ <span x-text="maxScore">{{ $maksimalSkor }}</span> poin</span>
                        <span class="text-lg font-bold text-blue-600 ml-2">(<span x-text="percentage">0.0</span>%)</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Terjawab: <span class="font-semibold text-gray-800" x-text="answeredCount">0</span> dari {{ $totalPertanyaan }} pertanyaan
                    </p>
                </div>

                <div class="flex items-center">
                    <template x-if="!isComplete">
                        <div class="px-3 py-1.5 rounded-full bg-gray-100 text-gray-600 text-xs font-medium flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                            Lengkapi seluruh pertanyaan untuk kalkulasi akhir
                        </div>
                    </template>
                    <template x-if="isComplete && isSangatLayak">
                        <div class="px-4 py-2 rounded-lg bg-green-100 text-green-800 text-sm font-bold flex items-center gap-2 border border-green-300">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Keputusan: Sangat Layak (&ge; 80%)
                        </div>
                    </template>
                    <template x-if="isComplete && !isSangatLayak">
                        <div class="px-4 py-2 rounded-lg bg-amber-100 text-amber-900 text-sm font-bold flex items-center gap-2 border border-amber-300">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Keputusan: Perlu Perbaikan (&lt; 80%)
                        </div>
                    </template>
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2 mt-3 overflow-hidden">
                <div class="h-2 rounded-full transition-all duration-300"
                     :class="parseFloat(percentage) >= 80 ? 'bg-green-600' : (parseFloat(percentage) >= 50 ? 'bg-amber-500' : 'bg-red-500')"
                     :style="`width: ${Math.min(100, Math.max(0, percentage))}%`"></div>
            </div>
        </div>

        @if($aspeks->isEmpty())
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded text-sm">
                Belum ada kriteria penilaian aktif. Hubungi admin untuk menambahkan aspek dan pertanyaan.
            </div>
        @else
            <form method="POST" action="{{ route('reviewer.final-draft.validate', $finalDraft) }}" class="space-y-4">
                @csrf

                @foreach($aspeks as $aspek)
                    <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 overflow-hidden">
                        <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-900">{{ $aspek->nama }}</h4>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @foreach($aspek->activePertanyaans as $pertanyaan)
                                @php
                                    $skorErrorKey = 'jawaban.'.$pertanyaan->id.'.skor';
                                    $oldSkor = old('jawaban.'.$pertanyaan->id.'.skor');
                                    $oldCatatan = old('jawaban.'.$pertanyaan->id.'.catatan');
                                @endphp
                                <div class="px-4 py-3">
                                    <p class="text-sm text-gray-800 mb-2">{{ $pertanyaan->teks_pertanyaan }}</p>
                                    <div class="flex flex-nowrap gap-2 w-full">
                                        @foreach($likertLabels as $skor => $label)
                                            <label class="flex-1 min-w-0 flex items-center gap-2 px-3 py-2 border rounded-md cursor-pointer text-sm hover:bg-gray-50 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                                <input type="radio"
                                                       name="jawaban[{{ $pertanyaan->id }}][skor]"
                                                       value="{{ $skor }}"
                                                       x-on:change="scores[{{ $pertanyaan->id }}] = {{ $skor }}"
                                                       class="shrink-0 text-blue-600 focus:ring-blue-500 border-gray-300"
                                                       {{ (string) $oldSkor === (string) $skor ? 'checked' : '' }}
                                                       required>
                                                <span class="min-w-0 truncate">
                                                    <span class="font-semibold text-gray-900">{{ $skor }}</span>
                                                    <span class="text-gray-500 text-xs ml-1">{{ $label }}</span>
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error($skorErrorKey)
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                    <input type="text"
                                           name="jawaban[{{ $pertanyaan->id }}][catatan]"
                                           value="{{ $oldCatatan }}"
                                           class="mt-2 w-full border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 text-xs py-1.5"
                                           placeholder="Catatan / saran untuk butir ini (opsional)">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <!-- Bagian Catatan & Submit -->
                <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 p-4 space-y-4">
                    <div>
                        <label for="catatan_revisi" class="block text-sm font-medium text-gray-700 mb-1">
                            Catatan Revisi / Saran Perbaikan
                            <span class="text-red-500" x-show="isComplete && !isSangatLayak" x-cloak>*wajib jika skor di bawah 80%</span>
                            <span class="text-gray-400 font-normal" x-show="!isComplete || isSangatLayak">(opsional untuk Sangat Layak)</span>
                        </label>
                        <textarea id="catatan_revisi" name="catatan_revisi" rows="4"
                                  class="w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm"
                                  placeholder="Tuliskan catatan revisi komprehensif bagi penyusun jika terdapat aspek yang perlu diperbaiki...">{{ old('catatan_revisi') }}</textarea>
                        @error('catatan_revisi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2 pt-2 border-t border-gray-100">
                        <a href="{{ route('reviewer.final-draft.show', $finalDraft) }}"
                           class="inline-flex justify-center px-4 py-2.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Batal
                        </a>
                        <button type="submit"
                                class="inline-flex justify-center items-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md text-sm shadow-sm transition">
                            Simpan & Kirim Penilaian
                        </button>
                    </div>
                </div>
            </form>
        @endif
    </div>
</div>
@endsection
