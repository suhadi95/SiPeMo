@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Formulir Penilaian Final Draft
    </h2>
@endsection

@section('content')
<div class="py-4 sm:py-6" x-data="{ hasil: @js(old('hasil_penilaian', '')) }">
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
                                           placeholder="Catatan / saran (opsional)">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 p-4 space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hasil Penilaian <span class="text-red-500">*</span></label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($hasilOptions as $value => $label)
                                <label class="flex-1 min-w-[140px] flex items-center justify-center px-3 py-2 border rounded-md cursor-pointer transition-colors text-center"
                                       :class="hasil === '{{ $value }}' ? 'border-blue-500 bg-blue-50 text-blue-800' : 'border-gray-200 text-gray-700 hover:bg-gray-50'">
                                    <input type="radio" name="hasil_penilaian" value="{{ $value }}" class="sr-only"
                                           x-model="hasil" {{ old('hasil_penilaian') === $value ? 'checked' : '' }} required>
                                    <span class="text-xs sm:text-sm font-semibold">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('hasil_penilaian')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-show="hasil && hasil !== 'sangat_layak'" x-cloak>
                        <label for="catatan_revisi" class="block text-sm font-medium text-gray-700 mb-1">
                            Catatan Revisi <span class="text-red-500">*wajib</span>
                        </label>
                        <textarea id="catatan_revisi" name="catatan_revisi" rows="3"
                                  class="w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm"
                                  placeholder="Tuliskan catatan perbaikan yang harus dilakukan penyusun..."
                                  :required="hasil && hasil !== 'sangat_layak'">{{ old('catatan_revisi') }}</textarea>
                        @error('catatan_revisi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2 pt-2">
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
