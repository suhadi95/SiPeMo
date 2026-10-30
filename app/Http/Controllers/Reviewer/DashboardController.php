<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\FinalDraft;
use App\Models\FinalDraftReview;
use App\Models\MataKuliah;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $assignedAppIds = $user->assignedPenyusunApplications()->pluck('penyusun_applications.id')->toArray();
        $legacyMkIds = MataKuliah::where('reviewer_id', $user->id)->pluck('id')->toArray();

        // Hitung total modul yang ditugaskan ke reviewer ini
        $totalCourses = count($assignedAppIds) ?: count($legacyMkIds);

        // Ambil query final drafts untuk reviewer ini
        $baseQuery = FinalDraft::where(function ($q) use ($assignedAppIds, $legacyMkIds) {
            if (!empty($assignedAppIds)) {
                $q->whereIn('penyusun_application_id', $assignedAppIds);
            }
            if (!empty($legacyMkIds)) {
                $q->orWhereIn('mata_kuliah_id', $legacyMkIds);
            }
            if (empty($assignedAppIds) && empty($legacyMkIds)) {
                $q->whereRaw('1 = 0');
            }
        });

        // Draft yang masih menunggu review dari reviewer yang sedang login
        $allAssignedDrafts = (clone $baseQuery)->with('reviews')->get();
        $pendingReviews = $allAssignedDrafts->filter(function ($draft) use ($user) {
            return in_array($draft->status, ['pending_review', 'pending'], true)
                && !$draft->isReviewCompletedBy($user->id);
        })->count();

        // Review yang telah diselesaikan oleh reviewer ini
        $approvedReviews = FinalDraftReview::where('reviewer_id', $user->id)
            ->where('hasil_penilaian', FinalDraftReview::HASIL_SANGAT_LAYAK)
            ->count();

        $rejectedReviews = FinalDraftReview::where('reviewer_id', $user->id)
            ->where('hasil_penilaian', '!=', FinalDraftReview::HASIL_SANGAT_LAYAK)
            ->count();

        // Ambil final drafts terbaru yang perlu di-review
        $latestDrafts = (clone $baseQuery)
            ->with(['mataKuliah'])
            ->orderBy('uploaded_at', 'desc')
            ->take(5)
            ->get();

        return view('reviewer.dashboard', compact(
            'totalCourses',
            'pendingReviews',
            'approvedReviews',
            'rejectedReviews',
            'latestDrafts'
        ));
    }
}
