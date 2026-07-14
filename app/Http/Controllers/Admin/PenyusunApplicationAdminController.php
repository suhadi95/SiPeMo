<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PenyusunApplication;
use App\Models\MataKuliah;
use App\Models\Jurusan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PenyusunApplicationAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = PenyusunApplication::query();
        
        // Fitur pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_penyusun', 'like', "%{$search}%")
                  ->orWhere('judul_bahan_ajar', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('jurusan', 'like', "%{$search}%")
                  ->orWhere('mata_kuliah', 'like', "%{$search}%");
            });
        }
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter berdasarkan jurusan
        if ($request->filled('jurusan_id')) {
            $query->whereHas('mataKuliah', function($q) use ($request) {
                $q->where('jurusan_id', $request->jurusan_id);
            });
        }
        
        // Urutkan berdasarkan tanggal terbaru
        $query->orderBy('created_at', 'desc');
        
        // Ambil jumlah per halaman dari request, default 15
        $perPage = (int) $request->get('per_page', 15);
        // Validasi nilai per_page agar hanya menerima nilai yang diizinkan
        $allowedPerPage = [15, 30, 60, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 15;
        }
        
        $applications = $query->paginate($perPage)->withQueryString();
        
        // Statistik untuk summary cards
        $stats = [
            'total' => PenyusunApplication::count(),
            'pending' => PenyusunApplication::where('status', 'pending')->count(),
            'approved' => PenyusunApplication::where('status', 'approved')->count(),
            'rejected' => PenyusunApplication::where('status', 'rejected')->count(),
        ];
        
        // Ambil semua jurusan untuk dropdown filter
        $jurusans = Jurusan::orderBy('nama_jurusan')->get();
        
        return view('admin.penyusun.index', compact('applications', 'stats', 'jurusans'));
    }

    public function show(PenyusunApplication $application)
    {
        return view('admin.penyusun.show', compact('application'));
    }

    public function approve(PenyusunApplication $application)
    {
        if ($application->status !== 'pending') {
            return back()->with('error', 'Pengajuan sudah diproses.');
        }

        DB::transaction(function () use ($application) {
            // Buat atau update user penyusun
            $passwordPlain = $application->nidn ?: 'Password123!';

            $user = User::firstOrCreate(
                ['email' => $application->email],
                [
                    'name' => $application->nama_penyusun,
                    'password' => Hash::make($passwordPlain),
                ]
            );

            // Tandai sebagai penyusun
            if (! $user->is_penyusun) {
                $user->is_penyusun = true;
                $user->save();
            }

            // Setujui aplikasi ini
            $application->status = 'approved';
            $application->approved_at = now();
            $application->validated_by = Auth::id();
            $application->save();

            // Auto-reject aplikasi lain dengan mata kuliah yang sama
            PenyusunApplication::where('mata_kuliah_id', $application->mata_kuliah_id)
                ->where('id', '!=', $application->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'rejected',
                    'rejection_reason' => 'Mata kuliah ini sudah diambil oleh penyusun lain',
                    'validated_by' => Auth::id()
                ]);

            // Auto-reject aplikasi lain dengan email yang sama (mata kuliah berbeda)
            PenyusunApplication::where('email', $application->email)
                ->where('id', '!=', $application->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'rejected',
                    'rejection_reason' => 'Email ini sudah digunakan untuk pengajuan lain yang disetujui',
                    'validated_by' => Auth::id()
                ]);
        });

        return redirect()->route('admin.penyusun.index')->with('status', 'Pengajuan disetujui dan akun penyusun dibuat/diperbarui.');
    }

    public function reject(PenyusunApplication $application)
    {
        if ($application->status !== 'pending') {
            return back()->with('error', 'Pengajuan sudah diproses.');
        }

        $application->status = 'rejected';
        $application->rejection_reason = null;
        $application->validated_by = Auth::id();
        $application->save();

        return redirect()->route('admin.penyusun.index')->with('status', 'Pengajuan ditolak.');
    }

    public function destroy(Request $request, PenyusunApplication $application)
    {
        // Validasi konfirmasi untuk penyusun yang disetujui
        if ($application->status === 'approved') {
            // Cek apakah ada konfirmasi 'YA'
            $confirmation = $request->input('confirmation');
            
            if (!$confirmation || strtoupper(trim($confirmation)) !== 'YA') {
                return back()->with('error', 'Konfirmasi penghapusan tidak valid. Silakan ketik "YA" untuk menghapus penyusun yang disetujui.');
            }

            // Hapus dalam transaction untuk memastikan data konsisten
            DB::transaction(function () use ($application) {
                // Hapus file draft jika ada
                if ($application->draft_path && Storage::disk('public')->exists($application->draft_path)) {
                    Storage::disk('public')->delete($application->draft_path);
                }

                // Hapus user terkait jika ada
                $user = User::where('email', $application->email)->first();
                if ($user) {
                    // Cek apakah user ini hanya penyusun atau juga memiliki role lain
                    if ($user->is_penyusun && !$user->is_admin && !$user->is_lpm) {
                        // Jika hanya penyusun, hapus user
                        $user->delete();
                    } else {
                        // Jika memiliki role lain, hanya hapus flag penyusun
                        $user->is_penyusun = false;
                        $user->save();
                    }
                }

                // Hapus application
                $application->delete();
            });

            return redirect()->route('admin.penyusun.index')->with('success', 'Penyusun yang disetujui dan data terkait berhasil dihapus.');
        }

        // Untuk status rejected, tidak perlu konfirmasi khusus
        if ($application->status === 'rejected') {
            // Hapus file draft jika ada
            if ($application->draft_path && Storage::disk('public')->exists($application->draft_path)) {
                Storage::disk('public')->delete($application->draft_path);
            }

            $application->delete();

            return redirect()->route('admin.penyusun.index')->with('success', 'Pengajuan yang ditolak berhasil dihapus.');
        }

        // Untuk status pending, tidak diizinkan hapus
        return back()->with('error', 'Pengajuan dengan status pending tidak dapat dihapus. Silakan tolak terlebih dahulu.');
    }

    public function downloadDraft(PenyusunApplication $application)
    {
        if (!$application->draft_path || !Storage::disk('public')->exists($application->draft_path)) {
            return back()->with('error', 'Tidak ada dokumen yang dapat diunduh. Penyusun tidak mengunggah dokumen draft.');
        }

        $fileSize = Storage::disk('public')->size($application->draft_path);
        if ($fileSize === 0) {
            return back()->with('error', 'File draft kosong.');
        }

        $filePath = Storage::disk('public')->path($application->draft_path);
        $fileName = 'draft_' . $application->nama_penyusun . '_' . $application->id . '.docx';
        
        return response()->download($filePath, $fileName);
    }

    public function downloadLaporanValidasi()
    {
        // Ambil semua penyusun yang approved dengan relasi yang diperlukan
        $penyusuns = PenyusunApplication::where('status', 'approved')
            ->with(['mataKuliah.jurusan'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Kelompokkan penyusun berdasarkan jurusan
        $penyusunsByJurusan = $penyusuns->groupBy(function($penyusun) {
            return $penyusun->mataKuliah->jurusan->nama_jurusan ?? 'Lainnya';
        });

        // Hitung statistik per jurusan
        $statistikPerJurusan = [];
        foreach ($penyusunsByJurusan as $namaJurusan => $penyusunsJurusan) {
            $totalSks = $penyusunsJurusan->sum(function($penyusun) {
                return $penyusun->mataKuliah->sks ?? 0;
            });
            
            $statistikPerJurusan[$namaJurusan] = [
                'total_penyusun' => $penyusunsJurusan->count(),
                'total_sks' => $totalSks
            ];
        }

        // Data untuk PDF
        $data = [
            'penyusunsByJurusan' => $penyusunsByJurusan,
            'statistikPerJurusan' => $statistikPerJurusan,
            'tanggalLaporan' => now()->format('d F Y'),
            'totalPenyusun' => $penyusuns->count()
        ];

        // Generate PDF dengan konfigurasi A4 dan margin 3cm
        $pdf = Pdf::loadView('admin.penyusun.laporan-pdf', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'margin_top' => 30,
                'margin_right' => 30,
                'margin_bottom' => 30,
                'margin_left' => 30,
            ]);

        $fileName = 'Laporan_Validasi_Penyusun_' . now()->format('Y-m-d') . '.pdf';
        
        return $pdf->stream($fileName);
    }

    public function downloadLaporanMataKuliahTersedia()
    {
        // Ambil semua mata kuliah yang belum memiliki penyusun approved
        $mataKuliahs = MataKuliah::with(['jurusan'])
            ->whereDoesntHave('penyusunApplications', function($q) {
                $q->where('status', 'approved');
            })
            ->orderBy('semester')
            ->orderBy('nama_mata_kuliah')
            ->get();

        // Kelompokkan berdasarkan jurusan
        $mkByJurusan = $mataKuliahs->groupBy(function($mk) {
            return $mk->jurusan->nama_jurusan ?? 'Lainnya';
        });

        $data = [
            'mkByJurusan' => $mkByJurusan,
            'tanggalLaporan' => now()->format('d F Y'),
            'totalMataKuliah' => $mataKuliahs->count(),
        ];

        $pdf = Pdf::loadView('admin.penyusun.laporan-mk-tersedia-pdf', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'margin_top' => 30,
                'margin_right' => 30,
                'margin_bottom' => 30,
                'margin_left' => 30,
            ]);

        $fileName = 'Laporan_MK_Tanpa_Penyusun_' . now()->format('Y-m-d') . '.pdf';

        return $pdf->stream($fileName);
    }
}


