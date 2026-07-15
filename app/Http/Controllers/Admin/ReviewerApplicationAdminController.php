<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReviewerApplication;
use App\Models\MataKuliah;
use App\Models\Jurusan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReviewerApplicationAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = ReviewerApplication::query();

        // Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
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

        // Map mata kuliah yang ditugaskan per email reviewer (untuk baris yang sudah approved)
        $usersByEmail = User::whereIn('email', $applications->pluck('email')->unique()->filter())
            ->with(['reviewerMataKuliahs' => fn ($q) => $q->orderBy('nama_mata_kuliah')])
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
        // Ambil semua jurusan dengan mata kuliah untuk di-assign
        $jurusans = Jurusan::with('mataKuliah.reviewer')->orderBy('nama_jurusan')->get();
        
        // Ambil user reviewer jika statusnya approved
        $user = null;
        $assignedMkIds = [];
        if ($application->status === 'approved') {
            $user = User::where('email', $application->email)->first();
            if ($user) {
                $assignedMkIds = MataKuliah::where('reviewer_id', $user->id)->pluck('id')->toArray();
            }
        }

        return view('admin.reviewer.show', compact('application', 'jurusans', 'assignedMkIds', 'user'));
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

        $request->validate([
            'mata_kuliah_ids' => 'nullable|array',
            'mata_kuliah_ids.*' => 'exists:mata_kuliahs,id',
        ]);

        DB::transaction(function () use ($application, $request) {
            $passwordPlain = $application->nidn ?: 'Password123!';

            // Buat atau update user
            $user = User::firstOrCreate(
                ['email' => $application->email],
                [
                    'name' => $application->nama_reviewer,
                    'password' => Hash::make($passwordPlain),
                ]
            );

            // Set is_reviewer
            if (!$user->is_reviewer) {
                $user->is_reviewer = true;
                $user->save();
            }

            // Setujui pendaftaran
            $application->status = 'approved';
            $application->approved_at = now();
            $application->validated_by = Auth::id();
            $application->save();

            // Set penugasan mata kuliah
            // Reset yang sebelumnya ditugaskan ke reviewer ini
            MataKuliah::where('reviewer_id', $user->id)->update(['reviewer_id' => null]);
            
            // Assign yang baru
            if ($request->filled('mata_kuliah_ids')) {
                MataKuliah::whereIn('id', $request->mata_kuliah_ids)->update(['reviewer_id' => $user->id]);
            }
        });

        return redirect()->route('admin.reviewer.show', $application)->with('status', 'Pendaftaran reviewer berhasil disetujui dan penugasan mata kuliah telah diperbarui.');
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
                // Hapus sertifikasi
                if ($application->sertifikasi_path && Storage::disk('public')->exists($application->sertifikasi_path)) {
                    Storage::disk('public')->delete($application->sertifikasi_path);
                }

                // Ambil user
                $user = User::where('email', $application->email)->first();
                if ($user) {
                    // Reset mata kuliah reviewer_id
                    MataKuliah::where('reviewer_id', $user->id)->update(['reviewer_id' => null]);

                    // Hapus user jika hanya reviewer
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

        // Untuk rejected
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
}
