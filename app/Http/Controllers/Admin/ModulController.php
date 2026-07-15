<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\Modul;
use App\Models\PenyusunApplication;
use App\Models\TahapPenyusunan;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ModulController extends Controller
{
    public function index(Request $request)
    {
        $totalTahap = TahapPenyusunan::global()->count();

        $allPenyusuns = PenyusunApplication::where('status', 'approved')
            ->with(['mataKuliah.jurusan', 'moduls.tahapPenyusunan'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Statistik dari data penuh (tanpa filter)
        $totalPenyusun = $allPenyusuns->count();
        $menungguValidasi = $allPenyusuns->filter(fn ($p) => $p->moduls->where('status', 'pending')->count() > 0)->count();
        $selesaiPenyusunan = $allPenyusuns->filter(function ($penyusun) use ($totalTahap) {
            if ($totalTahap == 0) return false;
            $approvedModuls = $penyusun->moduls->where('status', 'approved');
            if ($approvedModuls->count() == 0) return false;
            $tahapApproved = $approvedModuls->pluck('tahapPenyusunan.tahap')->filter()->unique()->sort()->values()->toArray();
            $expectedTahap = range(1, $totalTahap);
            return count($tahapApproved) == $totalTahap && $tahapApproved == $expectedTahap;
        })->count();

        $penyusuns = $allPenyusuns;

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $penyusuns = $penyusuns->filter(function ($penyusun) use ($search) {
                return str_contains(strtolower($penyusun->nama_penyusun ?? ''), $search)
                    || str_contains(strtolower($penyusun->judul_bahan_ajar ?? ''), $search)
                    || str_contains(strtolower($penyusun->email ?? ''), $search)
                    || str_contains(strtolower($penyusun->mataKuliah?->nama_mata_kuliah ?? ''), $search)
                    || str_contains(strtolower($penyusun->mataKuliah?->jurusan?->nama_jurusan ?? ''), $search);
            });
        }

        if ($request->filled('status')) {
            $status = $request->status;
            $penyusuns = $penyusuns->filter(function ($penyusun) use ($status, $totalTahap) {
                return $this->resolveModulProgressStatus($penyusun, $totalTahap) === $status;
            });
        }

        if ($request->filled('jurusan_id')) {
            $jurusanId = (int) $request->jurusan_id;
            $penyusuns = $penyusuns->filter(fn ($p) => ($p->mataKuliah?->jurusan_id ?? null) === $jurusanId);
        }

        $perPage = (int) $request->get('per_page', 15);
        $allowedPerPage = [15, 30, 60, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 15;
        }

        $penyusuns = $penyusuns->values();
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $penyusuns->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginator = new LengthAwarePaginator(
            $currentItems,
            $penyusuns->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $allJurusans = Jurusan::orderBy('nama_jurusan')->get();
        $penyusunsByJurusan = $currentItems->groupBy(fn ($p) => $p->mataKuliah->jurusan->nama_jurusan ?? 'Lainnya');

        $penyusunsByJurusanWithAll = collect();
        foreach ($penyusunsByJurusan as $nama => $items) {
            $penyusunsByJurusanWithAll->put($nama, $items);
        }

        $jurusans = $allJurusans;

        return view('admin.modul.index', compact(
            'penyusunsByJurusanWithAll',
            'totalPenyusun',
            'menungguValidasi',
            'selesaiPenyusunan',
            'paginator',
            'jurusans',
            'totalTahap'
        ));
    }

    /**
     * Status progres penyusunan modul yang ditampilkan di tabel.
     */
    private function resolveModulProgressStatus(PenyusunApplication $penyusun, int $totalTahap): string
    {
        $uploadedModuls = $penyusun->moduls->keyBy(fn ($modul) => $modul->tahapPenyusunan->tahap ?? null);

        for ($i = 1; $i <= $totalTahap; $i++) {
            if (!isset($uploadedModuls[$i])) {
                return 'belum_diunggah';
            }
            if ($uploadedModuls[$i]->status == 'rejected') {
                return 'ditolak';
            }
            if ($uploadedModuls[$i]->status == 'pending') {
                return 'menunggu_validasi';
            }
        }

        return 'selesai';
    }

    public function show($penyusunApplicationId)
    {
        $penyusunApplication = PenyusunApplication::with(['mataKuliah.jurusan', 'moduls.tahapPenyusunan', 'validator'])
            ->findOrFail($penyusunApplicationId);
        
        $tahaps = TahapPenyusunan::global()->orderBy('tahap')->get();

        return view('admin.modul.show', compact('penyusunApplication', 'tahaps'));
    }

    public function approve(Request $request, Modul $modul)
    {
        $request->validate([
            'catatan_admin' => 'nullable|string|max:1000',
        ]);

        $modul->update([
            'status' => 'approved',
            'catatan_admin' => $request->catatan_admin,
            'validated_at' => now(),
            'validated_by' => Auth::id(),
        ]);

        return redirect()->back()
            ->with('success', 'Modul berhasil disetujui.');
    }

    public function reject(Request $request, Modul $modul)
    {
        $request->validate([
            'catatan_admin' => 'required|string|max:1000',
        ]);

        $modul->update([
            'status' => 'rejected',
            'catatan_admin' => $request->catatan_admin,
            'validated_at' => now(),
            'validated_by' => Auth::id(),
        ]);

        return redirect()->back()
            ->with('success', 'Modul berhasil ditolak dengan catatan. Penyusun harus upload ulang.');
    }

    public function rejectWithoutNote(Modul $modul)
    {
        $modul->update([
            'status' => 'rejected',
            'catatan_admin' => null,
            'validated_at' => now(),
            'validated_by' => Auth::id(),
        ]);

        return redirect()->back()
            ->with('success', 'Modul berhasil ditolak. Penyusun harus upload ulang.');
    }

    public function revokeApproval(Modul $modul)
    {
        // Hanya bisa membatalkan jika statusnya approved
        if ($modul->status !== 'approved') {
            return redirect()->back()
                ->with('error', 'Hanya modul yang sudah disetujui yang dapat dibatalkan persetujuannya.');
        }

        $modul->update([
            'status' => 'pending',
            'validated_at' => null,
            'validated_by' => null,
            // Catatan admin tetap dipertahankan untuk referensi
        ]);

        return redirect()->back()
            ->with('success', 'Persetujuan modul berhasil dibatalkan. Status kembali ke menunggu validasi.');
    }

    public function download(Modul $modul)
    {
        if (!$modul->file_path || !Storage::disk('public')->exists($modul->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->download(Storage::disk('public')->path($modul->file_path), $modul->file_name);
    }

    public function pending()
    {
        $moduls = Modul::pending()
            ->with(['penyusunApplication', 'mataKuliah'])
            ->orderBy('uploaded_at', 'asc')
            ->paginate(20);

        return view('admin.modul.pending', compact('moduls'));
    }

    public function approved()
    {
        $moduls = Modul::approved()
            ->with(['penyusunApplication', 'mataKuliah', 'validator'])
            ->orderBy('validated_at', 'desc')
            ->paginate(20);

        return view('admin.modul.approved', compact('moduls'));
    }

    public function rejected()
    {
        $moduls = Modul::rejected()
            ->with(['penyusunApplication', 'mataKuliah', 'validator'])
            ->orderBy('validated_at', 'desc')
            ->paginate(20);

        return view('admin.modul.rejected', compact('moduls'));
    }
}
