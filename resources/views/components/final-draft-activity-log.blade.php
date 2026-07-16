@props([
    'logs',
    'viewer' => 'penyusun',
])

@php
    $showActorName = in_array($viewer, ['lpm', 'admin'], true);
@endphp

<div {{ $attributes->merge(['class' => 'border border-gray-200 rounded-lg overflow-hidden']) }}>
    <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
        <h4 class="text-sm font-medium text-gray-900">Riwayat Review</h4>
        <p class="text-xs text-gray-500 mt-0.5">Log keputusan Reviewer dan LPM untuk final draft ini.</p>
    </div>

    <div class="p-4">
        @forelse($logs as $log)
            <div @class([
                'relative pl-6 pb-5 last:pb-0',
                'border-l-2 border-gray-200 last:border-l-transparent' => !$loop->last,
                'border-l-2 border-transparent' => $loop->last,
            ])>
                <span @class([
                    'absolute -left-[9px] top-1 w-4 h-4 rounded-full ring-2 ring-white',
                    'bg-green-500' => $log->isApproved(),
                    'bg-yellow-500' => in_array($log->action, ['layak_dengan_perbaikan', 'perlu_revisi_mayor'], true),
                    'bg-red-500' => !$log->isApproved() && !in_array($log->action, ['layak_dengan_perbaikan', 'perlu_revisi_mayor'], true),
                ])></span>

                <div class="flex flex-wrap items-center gap-2 mb-1">
                    <span @class([
                        'inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium',
                        'bg-green-100 text-green-800' => $log->isApproved(),
                        'bg-yellow-100 text-yellow-800' => in_array($log->action, ['layak_dengan_perbaikan', 'perlu_revisi_mayor'], true),
                        'bg-red-100 text-red-800' => !$log->isApproved() && !in_array($log->action, ['layak_dengan_perbaikan', 'perlu_revisi_mayor'], true),
                    ])>
                        {{ $log->actionLabel() }}
                    </span>
                    <span class="text-xs font-medium text-gray-700">{{ $log->roleLabel() }}</span>
                    @if($showActorName && $log->actor)
                        <span class="text-xs text-gray-500">· {{ $log->actor->name }}</span>
                    @endif
                    <span class="text-xs text-gray-400 ml-auto">
                        {{ $log->created_at?->format('d M Y H:i') }}
                    </span>
                </div>

                @if($log->notes)
                    <div class="mt-1.5 text-xs sm:text-sm text-gray-700 bg-gray-50 border border-gray-100 rounded-md px-3 py-2">
                        {{ $log->notes }}
                    </div>
                @endif
            </div>
        @empty
            <p class="text-sm text-gray-500 text-center py-4">Belum ada aktivitas review.</p>
        @endforelse
    </div>
</div>
