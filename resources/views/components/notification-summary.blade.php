@props(['penyusunApplications'])

@php
    $user = auth()->user();
    $application = $penyusunApplications->first();
    
    // Hitung statistik modul
    $totalModuls = 0;
    $uploadedModuls = 0;
    $approvedModuls = 0;
    $pendingModuls = 0;
    $rejectedModuls = 0;
    
    // Hitung statistik final draft
    $hasFinalDraft = false;
    $finalDraftStatus = null;
    $finalDraftApproved = false;
    
    // Hitung statistik publikasi
    $hasPublication = false;
    $publicationStatus = null;
    
    // Status aplikasi
    $applicationStatus = $application ? $application->status : 'none';
    
    if ($application) {
        $moduls = $application->moduls;
        $totalModuls = $moduls->count();
        $uploadedModuls = $moduls->where('file_path', '!=', null)->count();
        $approvedModuls = $moduls->where('status', 'approved')->count();
        $pendingModuls = $moduls->where('status', 'pending')->count();
        $rejectedModuls = $moduls->where('status', 'rejected')->count();
        
        // Final draft
        $finalDraft = $application->finalDrafts->first();
        if ($finalDraft) {
            $hasFinalDraft = true;
            $finalDraftStatus = $finalDraft->status;
            $finalDraftApproved = $finalDraft->status === 'approved';
        }
        
        // Publikasi
        $publication = $application->publicationModuls->first();
        if ($publication) {
            $hasPublication = true;
            $publicationStatus = $publication->status;
        }
    }

    // Cek apakah ada tahap lanjutan (final draft atau publikasi) yang sudah disetujui
    $isAnyAdvancedStageApproved = $finalDraftApproved || ($hasPublication && $publicationStatus === 'approved');

    // Cek tahap yang sedang aktif
    $currentTahap = \App\Models\TahapPenyusunan::current()->first();
    $nextTahap = \App\Models\TahapPenyusunan::where('tanggal_mulai', '>', now()->toDateString())
        ->orderBy('tanggal_mulai', 'asc')
        ->first();
    
    $deadlineInfo = null;
    if ($currentTahap) {
        $deadlineInfo = [
            'nama_tahap' => $currentTahap->nama_tahap,
            'tanggal_selesai' => $currentTahap->tanggal_selesai,
            'is_overdue' => now()->toDateString() > $currentTahap->tanggal_selesai,
            'days_left' => now()->diffInDays($currentTahap->tanggal_selesai, false)
        ];
    }
    
    $nextTahapInfo = null;
    if ($nextTahap) {
        $nextTahapInfo = [
            'nama_tahap' => $nextTahap->nama_tahap,
            'tanggal_mulai' => $nextTahap->tanggal_mulai,
            'days_until_start' => now()->diffInDays($nextTahap->tanggal_mulai, false)
        ];
    }
    
    // Tentukan notifikasi yang perlu ditampilkan
    $notifications = [];

    // Notifikasi aplikasi
    if ($applicationStatus === 'pending') {
        $notifications[] = [
            'type' => 'warning',
            'icon' => 'clock',
            'title' => 'Aplikasi Menunggu Persetujuan',
            'message' => 'Aplikasi Anda sedang menunggu persetujuan dari admin.',
            'action' => null
        ];
    } elseif ($applicationStatus === 'rejected') {
        $notifications[] = [
            'type' => 'error',
            'icon' => 'x-circle',
            'title' => 'Aplikasi Ditolak',
            'message' => 'Aplikasi Anda ditolak. Silakan periksa alasan penolakan.',
            'action' => null
        ];
    }


    // Notifikasi tahap yang akan datang
    if ($nextTahapInfo && $nextTahapInfo['days_until_start'] <= 7 && $nextTahapInfo['days_until_start'] >= 0) {
        $notifications[] = [
            'type' => 'info',
            'icon' => 'calendar',
            'title' => 'Tahap Baru Segera Dimulai',
            'message' => "{$nextTahapInfo['nama_tahap']} akan dimulai dalam {$nextTahapInfo['days_until_start']} hari.",
            'action' => ['text' => 'Lihat Jadwal', 'url' => route('penyusun.modul.index')]
        ];
    }

    // Notifikasi modul yang ditolak
    if ($rejectedModuls > 0) {
        $notifications[] = [
            'type' => 'error',
            'icon' => 'x-circle',
            'title' => 'Modul Ditolak',
            'message' => "Ada {$rejectedModuls} modul yang ditolak dan perlu diperbaiki.",
            'action' => ['text' => 'Lihat Modul', 'url' => route('penyusun.modul.index')]
        ];
    }

    // Notifikasi modul pending
    if ($pendingModuls > 0) {
        $notifications[] = [
            'type' => 'info',
            'icon' => 'clock',
            'title' => 'Modul Menunggu Validasi',
            'message' => "Ada {$pendingModuls} modul yang sedang menunggu validasi admin.",
            'action' => ['text' => 'Lihat Status', 'url' => route('penyusun.modul.index')]
        ];
    }

    // Notifikasi publikasi
    if ($hasPublication) {
        if ($publicationStatus === 'rejected') {
            $notifications[] = [
                'type' => 'error',
                'icon' => 'x-circle',
                'title' => 'Publikasi Ditolak',
                'message' => 'Pengajuan publikasi Anda ditolak. Silakan periksa catatan dan perbaiki.',
                'action' => ['text' => 'Kelola Publikasi', 'url' => route('penyusun.publication.index')]
            ];
        } elseif ($publicationStatus === 'pending') {
            $notifications[] = [
                'type' => 'info',
                'icon' => 'clock',
                'title' => 'Publikasi Menunggu Validasi',
                'message' => 'Pengajuan publikasi Anda sedang menunggu validasi admin.',
                'action' => ['text' => 'Lihat Status', 'url' => route('penyusun.publication.index')]
            ];
        } elseif ($publicationStatus === 'approved') {
            $notifications[] = [
                'type' => 'success',
                'icon' => 'check-circle',
                'title' => 'Publikasi Disetujui',
                'message' => 'Selamat! Penyusunan Modul Anda Telah selesai.',
                'action' => null
            ];
        }
    }

    // Notifikasi final draft
    if ($hasFinalDraft) {
        if ($finalDraftStatus === 'rejected') {
            $notifications[] = [
                'type' => 'error',
                'icon' => 'x-circle',
                'title' => 'Final Draft Ditolak',
                'message' => 'Final draft Anda ditolak. Silakan periksa catatan dan perbaiki.',
                'action' => ['text' => 'Kelola Final Draft', 'url' => route('penyusun.final-draft.index')]
            ];
        } elseif ($finalDraftStatus === 'pending') {
            $notifications[] = [
                'type' => 'info',
                'icon' => 'clock',
                'title' => 'Final Draft Menunggu Validasi',
                'message' => 'Final draft Anda sedang menunggu validasi admin dan LPM.',
                'action' => ['text' => 'Lihat Status', 'url' => route('penyusun.final-draft.index')]
            ];
        } elseif ($finalDraftStatus === 'approved' && !$hasPublication) {
            // Final draft sudah disetujui tapi belum ada publikasi
            $notifications[] = [
                'type' => 'warning',
                'icon' => 'exclamation-triangle',
                'title' => 'Action Required: Upload Bukti Publikasi',
                'message' => 'Final draft Anda telah disetujui! Silakan segera upload bukti publikasi modul Anda.',
                'action' => ['text' => 'Upload Publikasi Sekarang', 'url' => route('penyusun.publication.index')]
            ];
        } elseif ($finalDraftStatus === 'approved' && $hasPublication) {
            // Final draft sudah disetujui dan sudah ada publikasi
            // Tidak perlu tampilkan notifikasi final draft lagi karena sudah lanjut ke tahap publikasi
        }
    }

    // Notifikasi progress
    if ($applicationStatus === 'approved' && $uploadedModuls < 4 && !$isAnyAdvancedStageApproved) {
        $notifications[] = [
            'type' => 'info',
            'icon' => 'document-text',
            'title' => 'Progress Penyusunan',
            'message' => "Anda telah mengupload {$uploadedModuls} dari 4 modul yang diperlukan.",
            'action' => ['text' => 'Lanjutkan Upload', 'url' => route('penyusun.modul.index')]
        ];
    }

    if ($applicationStatus === 'approved' && !$isAnyAdvancedStageApproved) {
        if ($uploadedModuls === 4 && $approvedModuls === 4) {
            $notifications[] = [
                'type' => 'success',
                'icon' => 'check-circle',
                'title' => 'Selamat! Semua Modul Disetujui',
                'message' => 'Semua 4 modul Anda telah disetujui. Anda dapat melanjutkan ke tahap final draft.',
                'action' => ['text' => 'Buat Final Draft', 'url' => route('penyusun.final-draft.index')]
            ];
        } elseif ($uploadedModuls === 4) {
            $notifications[] = [
                'type' => 'success',
                'icon' => 'check-circle',
                'title' => 'Semua Modul Telah Diupload',
                'message' => 'Anda telah mengupload semua 4 modul. Menunggu validasi dari admin.',
                'action' => ['text' => 'Lihat Status', 'url' => route('penyusun.modul.index')]
            ];
        }
    }
@endphp

@if(count($notifications) > 0)
<div class="mb-6 animate-fade-in">
    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center ml-4">
        <svg class="w-5 h-5 text-blue-600 mr-2 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-5a7.5 7.5 0 1 0-15 0v5h5l-5 5-5-5h5v-5a7.5 7.5 0 1 1 15 0v5z"></path>
        </svg>
        Notifikasi & Update
        @if(count($notifications) > 1)
            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                {{ count($notifications) }} notifikasi
            </span>
        @endif
    </h3>
    
    <div class="space-y-4">
        @foreach($notifications as $notification)
            <div class="bg-white rounded-lg shadow-sm border-l-4 notification-card animate-slide-in
                @if($notification['type'] === 'success') border-green-500 bg-green-50
                @elseif($notification['type'] === 'warning') border-yellow-500 bg-yellow-50
                @elseif($notification['type'] === 'error') border-red-500 bg-red-50
                @else border-blue-500 bg-blue-50 @endif">
                
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            @if($notification['icon'] === 'check-circle')
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            @elseif($notification['icon'] === 'clock')
                                <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                            @elseif($notification['icon'] === 'x-circle')
                                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                            @elseif($notification['icon'] === 'exclamation-triangle')
                                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            @elseif($notification['icon'] === 'document-text')
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                </svg>
                            @elseif($notification['icon'] === 'calendar')
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                            @endif
                        </div>
                        
                        <div class="ml-3 flex-1">
                            <h4 class="text-sm font-medium 
                                @if($notification['type'] === 'success') text-green-800
                                @elseif($notification['type'] === 'warning') text-yellow-800
                                @elseif($notification['type'] === 'error') text-red-800
                                @else text-blue-800 @endif">
                                {{ $notification['title'] }}
                            </h4>
                            <p class="mt-1 text-sm 
                                @if($notification['type'] === 'success') text-green-700
                                @elseif($notification['type'] === 'warning') text-yellow-700
                                @elseif($notification['type'] === 'error') text-red-700
                                @else text-blue-700 @endif">
                                {{ $notification['message'] }}
                            </p>
                            
                            @if($notification['action'])
                                <div class="mt-3">
                                    <a href="{{ $notification['action']['url'] }}" 
                                       class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md 
                                       @if($notification['type'] === 'success') text-green-700 bg-green-100 hover:bg-green-200
                                       @elseif($notification['type'] === 'warning') text-yellow-700 bg-yellow-100 hover:bg-yellow-200
                                       @elseif($notification['type'] === 'error') text-red-700 bg-red-100 hover:bg-red-200
                                       @else text-blue-700 bg-blue-100 hover:bg-blue-200 @endif
                                       focus:outline-none focus:ring-2 focus:ring-offset-2 
                                       @if($notification['type'] === 'success') focus:ring-green-500
                                       @elseif($notification['type'] === 'warning') focus:ring-yellow-500
                                       @elseif($notification['type'] === 'error') focus:ring-red-500
                                       @else focus:ring-blue-500 @endif
                                       transition ease-in-out duration-150">
                                        {{ $notification['action']['text'] }}
                                        <svg class="ml-2 -mr-0.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif
