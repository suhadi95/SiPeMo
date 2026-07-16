<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\FinalDraft;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil mata kuliah yang ditugaskan ke reviewer ini
        $mataKuliahIds = MataKuliah::where('reviewer_id', $user->id)->pluck('id')->toArray();

        // Hitung statistik
        $totalCourses = count($mataKuliahIds);
        
        $pendingReviews = FinalDraft::whereIn('mata_kuliah_id', $mataKuliahIds)
            ->where('status', 'pending_review')
            ->count();
            
        $approvedReviews = FinalDraft::whereIn('mata_kuliah_id', $mataKuliahIds)
            ->whereIn('status', ['approved_by_reviewer', 'pending_lpm', 'approved'])
            ->count();
            
        $rejectedReviews = FinalDraft::whereIn('mata_kuliah_id', $mataKuliahIds)
            ->where('status', 'rejected_by_reviewer')
            ->count();

        // Ambil final drafts terbaru yang perlu di-review (tanpa data identitas penyusun)
        $latestDrafts = FinalDraft::whereIn('mata_kuliah_id', $mataKuliahIds)
            ->with(['mataKuliah'])
            ->orderBy('uploaded_at', 'desc')
            ->take(5)
            ->get();

        return view('reviewer.dashboard', compact('totalCourses', 'pendingReviews', 'approvedReviews', 'rejectedReviews', 'latestDrafts'));
    }
}
