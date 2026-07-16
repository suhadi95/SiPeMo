<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Jurusan;
use App\Models\MataKuliah;
use App\Models\User;
use App\Models\Modul;
use App\Models\FinalDraft;
use App\Models\PublicationModul;
use App\Models\PenyusunApplication;
use App\Models\TahapPenyusunan;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set locale untuk Carbon ke bahasa Indonesia
        Carbon::setLocale('id');
        
        // Set format tanggal Indonesia
        Carbon::setLocale('id');

        // Share counts for navbar notification badges
        View::composer('*', function ($view) {
            if (!Auth::check()) {
                return;
            }

            // Default zeros
            $pendingApplications = 0;
            $modulDalamProses = 0;
            $finalModulPending = 0;
            $publikasiModulPending = 0;
            $notificationItems = [];

            $user = Auth::user();

            // Hanya hitung yang relevan untuk masing-masing role
            if ($user->is_admin) {
                $pendingApplications = \App\Models\PenyusunApplication::where('status', 'pending')->count();
                $modulDalamProses = Modul::where('status', 'pending')->count();
                $finalModulPending = FinalDraft::whereIn('status', ['pending_review', 'approved_by_reviewer', 'pending_lpm'])->count();
                $publikasiModulPending = PublicationModul::where('status', 'pending')->orWhereNull('validated_at')->count();

                // Build admin notification items
                $notificationItems[] = [
                    'label' => 'Validasi Pendaftaran Penyusun',
                    'count' => $pendingApplications,
                    'url' => route('admin.penyusun.index'),
                ];
                $notificationItems[] = [
                    'label' => 'Penyusunan Modul (Pending)',
                    'count' => $modulDalamProses,
                    'url' => route('admin.modul.index'),
                ];
                $notificationItems[] = [
                    'label' => 'Final Draft (Pending)',
                    'count' => $finalModulPending,
                    'url' => route('admin.final-draft.index'),
                ];
                $notificationItems[] = [
                    'label' => 'Publikasi (Pending)',
                    'count' => $publikasiModulPending,
                    'url' => route('admin.publication.index'),
                ];
            }

            if ($user->is_penyusun) {
                // Hitung indikator untuk penyusun
                $applications = PenyusunApplication::where('email', $user->email)
                    ->where('status', 'approved')
                    ->get();

                $penyusunUploadModulAvailable = 0;
                $penyusunFinalDraftReady = 0;
                $penyusunPublicationReady = 0;

                if ($applications->count() > 0) {
                    // Ambil semua tahap global sekali
                    $tahaps = TahapPenyusunan::global()->orderBy('tahap')->get();

                    foreach ($applications as $application) {
                        // 1) Kesiapan upload modul per tahap (reminder jika boleh upload sekarang)
                        foreach ($tahaps as $tahap) {
                            $modulInTahap = Modul::where('penyusun_application_id', $application->id)
                                ->where('tahap_id', $tahap->id)
                                ->first();

                            $isAccessible = $tahap->isAccessibleForUser($application->id);
                            $isCurrentlyActive = $tahap->isCurrentlyActive();
                            $hasUnfinishedModuls = $tahap->hasUnfinishedModuls($application->id);

                            $canUpload = $isAccessible &&
                                         (!$modulInTahap || $modulInTahap->status !== 'approved') &&
                                         ($isCurrentlyActive || $hasUnfinishedModuls);

                            // Jangan ingatkan jika sudah upload dan sedang pending
                            if ($canUpload && (!$modulInTahap || $modulInTahap->status !== 'pending')) {
                                $penyusunUploadModulAvailable++;
                            }
                        }

                        // 2) Kesiapan upload final draft per aplikasi: semua tahap approved dan belum ada final draft atau ditolak
                        $allTahapsApproved = true;
                        foreach ($tahaps as $tahap) {
                            $approvedInTahap = Modul::where('penyusun_application_id', $application->id)
                                ->where('tahap_id', $tahap->id)
                                ->where('status', 'approved')
                                ->exists();
                            if (!$approvedInTahap) {
                                $allTahapsApproved = false;
                                break;
                            }
                        }

                        if ($allTahapsApproved) {
                            $existingFinalDraft = FinalDraft::where('penyusun_application_id', $application->id)->first();
                            if (!$existingFinalDraft || ($existingFinalDraft && in_array($existingFinalDraft->status, ['rejected', 'rejected_by_reviewer']))) {
                                $penyusunFinalDraftReady++;
                            }
                        }

                        // 3) Kesiapan upload publikasi per aplikasi: final draft disetujui LPM dan belum ada publikasi atau ditolak
                        $approvedFinalDraft = FinalDraft::where('penyusun_application_id', $application->id)
                            ->where('status', 'approved')
                            ->whereNotNull('lpm_validated_at')
                            ->whereNotNull('lpm_validated_by')
                            ->first();
                        if ($approvedFinalDraft) {
                            $existingPublication = PublicationModul::where('penyusun_application_id', $application->id)->first();
                            if (!$existingPublication || ($existingPublication && $existingPublication->status === 'rejected')) {
                                $penyusunPublicationReady++;
                            }
                        }
                    }

                    // Tambahkan item notifikasi penyusun
                    $notificationItems[] = [
                        'label' => 'Upload Modul pada tahap yang tersedia',
                        'count' => $penyusunUploadModulAvailable,
                        'url' => route('penyusun.modul.index'),
                    ];
                    $notificationItems[] = [
                        'label' => 'Upload Final Draft siap',
                        'count' => $penyusunFinalDraftReady,
                        'url' => route('penyusun.final-draft.index'),
                    ];
                    $notificationItems[] = [
                        'label' => 'Kirim Publikasi siap',
                        'count' => $penyusunPublicationReady,
                        'url' => route('penyusun.publication.index'),
                    ];

                    // Masukkan ke ringkasan total
                    $navbarNotifications['penyusunUploadModulAvailable'] = $penyusunUploadModulAvailable;
                    $navbarNotifications['penyusunFinalDraftReady'] = $penyusunFinalDraftReady;
                    $navbarNotifications['penyusunPublicationReady'] = $penyusunPublicationReady;
                }
            }

            if ($user->is_lpm) {
                // LPM: draft yang menunggu validasi (lolos reviewer atau resubmit setelah tolak LPM)
                $finalModulPending = FinalDraft::whereIn('status', ['approved_by_reviewer', 'pending_lpm'])->count();

                $notificationItems[] = [
                    'label' => 'Final Draft menunggu validasi LPM',
                    'count' => $finalModulPending,
                    'url' => route('lpm.final-draft.index'),
                ];
            }

            if ($user->is_reviewer) {
                // Reviewer: jumlah final draft yang ditugaskan dan menunggu review
                $reviewerMataKuliahIds = \App\Models\MataKuliah::where('reviewer_id', $user->id)->pluck('id')->toArray();
                $reviewerPending = FinalDraft::whereIn('mata_kuliah_id', $reviewerMataKuliahIds)
                    ->where('status', 'pending_review')
                    ->count();

                $notificationItems[] = [
                    'label' => 'Final Draft Menunggu Review',
                    'count' => $reviewerPending,
                    'url' => route('reviewer.final-draft.index'),
                ];
                $navbarNotifications['reviewerPending'] = $reviewerPending;
            }

            // Gabungkan semua indikator ke dalam navbarNotifications tanpa menimpa yang sudah ada (penyusun)
            if (!isset($navbarNotifications) || !is_array($navbarNotifications)) {
                $navbarNotifications = [];
            }
            $baseCounts = [
                'pendingApplications' => $pendingApplications,
                'modulDalamProses' => $modulDalamProses,
                'finalModulPending' => $finalModulPending,
                'publikasiModulPending' => $publikasiModulPending,
            ];
            $navbarNotifications = array_merge($navbarNotifications, $baseCounts);

            // Total dihitung dari seluruh item notifikasi yang dibangun (aman untuk semua role)
            $totalNotif = 0;
            foreach ($notificationItems as $item) {
                $totalNotif += (int) ($item['count'] ?? 0);
            }
            $navbarNotifications['total'] = $totalNotif;

            $view->with('navbarNotifications', $navbarNotifications)
                 ->with('navbarNotificationItems', $notificationItems);
        });
    }
}
