@props([
    'review' => null,
])

@if($review)
    @php
        $grouped = $review->answersGroupedByAspek();
        $hasilClass = match ($review->hasil_penilaian) {
            'sangat_layak' => 'bg-green-100 text-green-800',
            'layak_dengan_perbaikan' => 'bg-yellow-100 text-yellow-800',
            'perlu_revisi_mayor' => 'bg-orange-100 text-orange-800',
            'tidak_layak' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    @endphp

    <div {{ $attributes->merge(['class' => 'border border-gray-200 rounded-lg overflow-hidden']) }}>
        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex flex-wrap items-center justify-between gap-2">
            <div>
                <h4 class="text-sm font-medium text-gray-900">Ringkasan Penilaian Reviewer</h4>
                <p class="text-xs text-gray-500 mt-0.5">
                    {{ $review->submitted_at?->format('d M Y H:i') }}
                    @if($review->reviewer)
                        · {{ $review->reviewer->name }}
                    @endif
                </p>
            </div>
            <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full {{ $hasilClass }}">
                {{ $review->hasil_penilaian_label }}
            </span>
        </div>

        <div class="p-4 space-y-5">
            @foreach($grouped as $aspekNama => $answers)
                <div>
                    <h5 class="text-sm font-semibold text-gray-800 mb-2">{{ $aspekNama }}</h5>
                    <div class="space-y-3">
                        @foreach($answers as $answer)
                            <div class="bg-gray-50 border border-gray-100 rounded-md px-3 py-2">
                                <p class="text-sm text-gray-800">{{ $answer->teks_pertanyaan }}</p>
                                <div class="mt-1.5 flex flex-wrap items-center gap-2 text-xs">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-medium">
                                        Skor {{ $answer->skor }} — {{ $answer->skor_label }}
                                    </span>
                                </div>
                                @if($answer->catatan)
                                    <p class="mt-1.5 text-xs text-gray-600 italic">Catatan: {{ $answer->catatan }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            @if($review->catatan_revisi)
                <div class="border-t border-gray-100 pt-3">
                    <span class="block text-xs font-semibold text-gray-500 uppercase">Catatan Revisi</span>
                    <p class="text-sm text-gray-700 mt-1 whitespace-pre-line">{{ $review->catatan_revisi }}</p>
                </div>
            @endif
        </div>
    </div>
@endif
