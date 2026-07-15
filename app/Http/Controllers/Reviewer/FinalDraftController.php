<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\FinalDraft;
use App\Models\FinalDraftActivityLog;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FinalDraftController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Ambil mata kuliah yang ditugaskan ke reviewer ini
        $mataKuliahIds = MataKuliah::where('reviewer_id', $user->id)->pluck('id')->toArray();
        
        $query = FinalDraft::whereIn('mata_kuliah_id', $mataKuliahIds)
            ->with(['mataKuliah.jurusan']);

        // Search (blind review: tanpa nama/identitas penyusun)
        if ($request->filled('search')) {
            $search = $request->search;
            $draftId = null;
            if (preg_match('/^(?:FD-?)?(\d+)$/i', trim($search), $matches)) {
                $draftId = (int) $matches[1];
            }

            $query->where(function ($q) use ($search, $draftId) {
                $q->where('judul_modul', 'like', "%{$search}%");
                if ($draftId !== null) {
                    $q->orWhere('id', $draftId);
                }
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $finalDrafts = $query->orderBy('uploaded_at', 'desc')->paginate(15)->withQueryString();

        return view('reviewer.final-draft.index', compact('finalDrafts'));
    }

    public function show(FinalDraft $finalDraft)
    {
        // Pastikan reviewer memiliki wewenang atas mata kuliah ini
        if ($finalDraft->mataKuliah->reviewer_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk mereview final draft ini.');
        }

        $finalDraft->load(['mataKuliah.jurusan', 'reviewerValidator', 'lpmValidator', 'activityLogs.actor']);

        return view('reviewer.final-draft.show', compact('finalDraft'));
    }

    public function download(FinalDraft $finalDraft)
    {
        if ($finalDraft->mataKuliah->reviewer_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk mendownload file ini.');
        }

        if (!$finalDraft->file_path || !Storage::disk('public')->exists($finalDraft->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        // Blind review: unduhan memakai nama file anonim (tanpa nama penyusun)
        $extension = pathinfo($finalDraft->file_name, PATHINFO_EXTENSION);
        $anonymousName = 'FinalDraft_FD-' . str_pad((string) $finalDraft->id, 5, '0', STR_PAD_LEFT) . ($extension ? '.' . $extension : '');

        return response()->download(Storage::disk('public')->path($finalDraft->file_path), $anonymousName);
    }

    public function validateDraft(Request $request, FinalDraft $finalDraft)
    {
        if ($finalDraft->mataKuliah->reviewer_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk memvalidasi draft ini.');
        }

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'catatan_reviewer' => 'required_if:status,rejected|nullable|string|max:1000',
        ], [
            'catatan_reviewer.required_if' => 'Catatan reviewer wajib diisi jika Anda menolak final draft.',
        ]);

        try {
            $statusNew = $request->status === 'approved' ? 'approved_by_reviewer' : 'rejected_by_reviewer';

            $finalDraft->update([
                'status' => $statusNew,
                'catatan_reviewer' => $request->catatan_reviewer,
                'reviewer_validated_at' => now(),
                'reviewer_validated_by' => Auth::id(),
            ]);

            FinalDraftActivityLog::create([
                'final_draft_id' => $finalDraft->id,
                'actor_id' => Auth::id(),
                'actor_role' => 'reviewer',
                'action' => $request->status,
                'status_after' => $statusNew,
                'notes' => $request->catatan_reviewer,
                'created_at' => now(),
            ]);

            Log::info('Final draft validated by Reviewer', [
                'final_draft_id' => $finalDraft->id,
                'decision' => $statusNew,
                'reviewer_id' => Auth::id(),
                'reviewer_name' => Auth::user()->name
            ]);

            $message = $request->status === 'approved' 
                ? 'Final draft disetujui dan akan dilanjutkan ke LPM.' 
                : 'Final draft ditolak dan dikembalikan ke penyusun untuk perbaikan.';

            return redirect()->route('reviewer.final-draft.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            Log::error('Error validating final draft by Reviewer: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memvalidasi final draft: ' . $e->getMessage());
        }
    }
}
