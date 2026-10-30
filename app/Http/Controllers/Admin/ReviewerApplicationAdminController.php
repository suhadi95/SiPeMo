<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinalDraftReview;
use App\Models\MataKuliah;
use App\Models\ReviewerApplication;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ReviewerApplicationAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = ReviewerApplication::query();

        // Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_reviewer', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nidn', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $query->orderBy('created_at', 'desc');

        $perPage = (int) $request->get('per_page', 15);
        $allowedPerPage = [15, 30, 60, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 15;
        }

        $applications = $query->paginate($perPage)->withQueryString();

        $usersByEmail = User::whereIn('email', $applications->pluck('email')->unique()->filter())
            ->with(['assignedPenyusunApplications'])
            ->get()
            ->keyBy('email');

        $stats = [
            'total' => ReviewerApplication::count(),
            'pending' => ReviewerApplication::where('status', 'pending')->count(),
            'approved' => ReviewerApplication::where('status', 'approved')->count(),
            'rejected' => ReviewerApplication::where('status', 'rejected')->count(),
        ];

        return view('admin.reviewer.index', compact('applications', 'stats', 'usersByEmail'));
    }

    public function show(ReviewerApplication $application)
    {
        $user = null;
        $assignedApplications = collect();
        $reviews = collect();

        if ($application->status === 'approved') {
            $user = User::where('email', $application->email)->first();
            if ($user) {
                $assignedApplications = $user->assignedPenyusunApplications()
                    ->with(['mataKuliah.jurusan', 'finalDrafts'])
                    ->get();

                $reviews = FinalDraftReview::where('reviewer_id', $user->id)
                    ->with(['finalDraft.mataKuliah.jurusan', 'finalDraft.penyusunApplication', 'answers'])
                    ->orderByDesc('submitted_at')
                    ->get();
            }
        }

        return view('admin.reviewer.show', compact('application', 'user', 'assignedApplications', 'reviews'));
    }

    public function approve(Request $request, ReviewerApplication $application)
    {
        // Validasi: reviewer tidak boleh dari penyusun
        $email = $application->email;
        $isPenyusunUser = User::where('email', $email)->where('is_penyusun', true)->exists();
        $hasPenyusunApp = \App\Models\PenyusunApplication::where('email', $email)->whereIn('status', ['approved', 'pending'])->exists();

        if ($isPenyusunUser || $hasPenyusunApp) {
            return back()->with('error', 'Pendaftar ini terdaftar sebagai Penyusun Modul dan tidak diperbolehkan menjadi Reviewer.');
        }

        DB::transaction(function () use ($application) {
            $passwordPlain = $application->nidn ?: 'Password123!';

            // Buat atau update user reviewer
            $user = User::firstOrCreate(
                ['email' => $application->email],
                [
                    'name' => $application->nama_reviewer,
                    'password' => Hash::make($passwordPlain),
                ]
            );

            if (!$user->is_reviewer) {
                $user->is_reviewer = true;
                $user->save();
            }

            // Setujui pendaftaran
            $application->status = 'approved';
            $application->approved_at = now();
            $application->validated_by = Auth::id();
            $application->save();
        });

        return redirect()->route('admin.reviewer.show', $application)
            ->with('status', 'Pendaftaran reviewer berhasil disetujui. Penetapan reviewer ke modul dapat dilakukan melalui menu Monitoring Final Draft Modul.');
    }

    public function reject(Request $request, ReviewerApplication $application)
    {
        if ($application->status !== 'pending') {
            return back()->with('error', 'Pendaftaran sudah diproses.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $application->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'validated_by' => Auth::id(),
        ]);

        return redirect()->route('admin.reviewer.index')->with('status', 'Pendaftaran reviewer berhasil ditolak.');
    }

    public function destroy(Request $request, ReviewerApplication $application)
    {
        if ($application->status === 'approved') {
            $confirmation = $request->input('confirmation');
            if (!$confirmation || strtoupper(trim($confirmation)) !== 'YA') {
                return back()->with('error', 'Konfirmasi penghapusan tidak valid. Silakan ketik "YA" untuk menghapus.');
            }

            DB::transaction(function () use ($application) {
                if ($application->sertifikasi_path && Storage::disk('public')->exists($application->sertifikasi_path)) {
                    Storage::disk('public')->delete($application->sertifikasi_path);
                }

                $user = User::where('email', $application->email)->first();
                if ($user) {
                    // Bersihkan penugasan modul multi-reviewer
                    DB::table('penyusun_application_reviewers')->where('reviewer_id', $user->id)->delete();
                    MataKuliah::where('reviewer_id', $user->id)->update(['reviewer_id' => null]);

                    if ($user->is_reviewer && !$user->is_admin && !$user->is_lpm && !$user->is_penyusun) {
                        $user->delete();
                    } else {
                        $user->is_reviewer = false;
                        $user->save();
                    }
                }

                $application->delete();
            });

            return redirect()->route('admin.reviewer.index')->with('success', 'Data reviewer dan akun terkait berhasil dihapus.');
        }

        if ($application->status === 'rejected') {
            if ($application->sertifikasi_path && Storage::disk('public')->exists($application->sertifikasi_path)) {
                Storage::disk('public')->delete($application->sertifikasi_path);
            }
            $application->delete();
            return redirect()->route('admin.reviewer.index')->with('success', 'Data pendaftaran reviewer berhasil dihapus.');
        }

        return back()->with('error', 'Pendaftaran pending tidak dapat dihapus. Silakan tolak terlebih dahulu.');
    }

    public function downloadCertification(ReviewerApplication $application)
    {
        if (!$application->sertifikasi_path || !Storage::disk('public')->exists($application->sertifikasi_path)) {
            return back()->with('error', 'File sertifikasi tidak ditemukan.');
        }

        return response()->download(
            Storage::disk('public')->path($application->sertifikasi_path),
            $application->sertifikasi_name
        );
    }

    public function downloadReviewHistoryPdf(ReviewerApplication $application)
    {
        $user = User::where('email', $application->email)->first();
        if (!$user) {
            return back()->with('error', 'User reviewer tidak ditemukan.');
        }

        $reviews = FinalDraftReview::where('reviewer_id', $user->id)
            ->with(['finalDraft.mataKuliah.jurusan', 'finalDraft.penyusunApplication', 'answers'])
            ->orderByDesc('submitted_at')
            ->get();

        $pdf = Pdf::loadView('pdf.reviewer-history', [
            'application' => $application,
            'user' => $user,
            'reviews' => $reviews,
        ])->setPaper('a4', 'portrait')->setOptions([
            'defaultFont' => 'DejaVu Sans',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
        ]);

        $safeName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $application->nama_reviewer);
        $fileName = 'Riwayat_Review_' . $safeName . '_' . date('Ymd') . '.pdf';

        return $pdf->download($fileName);
    }
}
