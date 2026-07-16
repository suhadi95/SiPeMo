<?php

namespace App\Http\Controllers\Lpm;

use App\Http\Controllers\Controller;
use App\Models\FinalDraft;
use App\Models\FinalDraftActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FinalDraftController extends Controller
{
    public function index()
    {
        $penyusuns = \App\Models\PenyusunApplication::where('status', 'approved')
            ->with(['mataKuliah.jurusan', 'moduls.tahapPenyusunan', 'finalDrafts', 'publicationModuls'])
            ->orderBy('created_at', 'desc')
            ->get();

        $allJurusans = \App\Models\Jurusan::orderBy('nama_jurusan')->get();

        $penyusunsByJurusan = $penyusuns->groupBy(function ($penyusun) {
            return $penyusun->mataKuliah->jurusan->nama_jurusan ?? 'Lainnya';
        });

        $penyusunsByJurusanWithAll = collect();
        foreach ($allJurusans as $jurusan) {
            $penyusunsByJurusanWithAll->put($jurusan->nama_jurusan, $penyusunsByJurusan->get($jurusan->nama_jurusan, collect()));
        }

        if ($penyusunsByJurusan->has('Lainnya')) {
            $penyusunsByJurusanWithAll->put('Lainnya', $penyusunsByJurusan->get('Lainnya'));
        }

        $finalDrafts = FinalDraft::whereIn('status', ['approved_by_reviewer', 'pending_lpm', 'approved', 'rejected'])
            ->with(['penyusunApplication', 'mataKuliah', 'lpmValidator'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('lpm.final-draft.index', compact('penyusunsByJurusanWithAll', 'finalDrafts'));
    }

    public function show(FinalDraft $finalDraft)
    {
        $finalDraft->load([
            'penyusunApplication',
            'mataKuliah',
            'lpmValidator',
            'reviewerValidator',
            'activityLogs.actor',
            'latestReview.answers',
            'latestReview.reviewer',
        ]);

        return view('lpm.final-draft.show', compact('finalDraft'));
    }

    public function download(FinalDraft $finalDraft)
    {
        if (!$finalDraft->file_path || !Storage::disk('public')->exists($finalDraft->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->download(Storage::disk('public')->path($finalDraft->file_path), $finalDraft->file_name);
    }

    public function validate(Request $request, FinalDraft $finalDraft)
    {
        if (!$finalDraft->isAwaitingLpm()) {
            return redirect()->back()
                ->with('error', 'Final draft ini belum siap untuk divalidasi LPM.');
        }

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'catatan_lpm' => 'nullable|string|max:1000',
        ]);

        try {
            $finalDraft->update([
                'status' => $request->status,
                'catatan_lpm' => $request->catatan_lpm,
                'lpm_validated_at' => now(),
                'lpm_validated_by' => Auth::id(),
            ]);

            FinalDraftActivityLog::create([
                'final_draft_id' => $finalDraft->id,
                'actor_id' => Auth::id(),
                'actor_role' => 'lpm',
                'action' => $request->status,
                'status_after' => $request->status,
                'notes' => $request->catatan_lpm,
                'created_at' => now(),
            ]);

            Log::info('Final draft validated by LPM', [
                'final_draft_id' => $finalDraft->id,
                'lpm_decision' => $request->status,
                'lpm_id' => Auth::id(),
            ]);

            $message = $request->status === 'approved'
                ? 'Final draft disetujui LPM.'
                : 'Final draft ditolak LPM.';

            return redirect()->route('lpm.final-draft.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error validating final draft by LPM: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memvalidasi final draft: ' . $e->getMessage());
        }
    }
}
