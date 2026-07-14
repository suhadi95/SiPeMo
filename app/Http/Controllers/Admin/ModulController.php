<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Modul;
use App\Models\PenyusunApplication;
use App\Models\TahapPenyusunan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ModulController extends Controller
{
    public function index()
    {
        // Ambil semua penyusun yang approved dengan relasi yang diperlukan
        $penyusuns = PenyusunApplication::where('status', 'approved')
            ->with(['mataKuliah.jurusan', 'moduls.tahapPenyusunan'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil semua jurusan
        $allJurusans = \App\Models\Jurusan::orderBy('nama_jurusan')->get();

        // Kelompokkan penyusun berdasarkan jurusan
        $penyusunsByJurusan = $penyusuns->groupBy(function($penyusun) {
            return $penyusun->mataKuliah->jurusan->nama_jurusan ?? 'Lainnya';
        });

        // Pastikan semua jurusan ada dalam array, meskipun kosong
        $penyusunsByJurusanWithAll = collect();
        foreach($allJurusans as $jurusan) {
            $penyusunsByJurusanWithAll->put($jurusan->nama_jurusan, $penyusunsByJurusan->get($jurusan->nama_jurusan, collect()));
        }

        // Tambahkan jurusan "Lainnya" jika ada penyusun tanpa jurusan
        if($penyusunsByJurusan->has('Lainnya')) {
            $penyusunsByJurusanWithAll->put('Lainnya', $penyusunsByJurusan->get('Lainnya'));
        }

        // Hitung summary data
        $totalPenyusun = $penyusuns->count();
        
        // Hitung penyusun yang menunggu validasi (memiliki modul dengan status pending)
        $menungguValidasi = $penyusuns->filter(function($penyusun) {
            return $penyusun->moduls->where('status', 'pending')->count() > 0;
        })->count();
        
        // Hitung penyusun yang selesai penyusunan (memiliki modul dengan status approved untuk seluruh tahap)
        $selesaiPenyusunan = $penyusuns->filter(function($penyusun) {
            $approvedModuls = $penyusun->moduls->where('status', 'approved');
            if ($approvedModuls->count() == 0) return false;
            
            // Ambil semua tahap yang tersedia (global)
            $totalTahap = \App\Models\TahapPenyusunan::global()->count();
            if ($totalTahap == 0) return false;
            
            // Cek apakah penyusun memiliki modul approved untuk semua tahap
            $tahapApproved = $approvedModuls->pluck('tahapPenyusunan.tahap')->filter()->unique()->sort()->values()->toArray();
            
            // Pastikan ada modul approved untuk tahap 1 sampai tahap terakhir
            $expectedTahap = range(1, $totalTahap);
            return count($tahapApproved) == $totalTahap && $tahapApproved == $expectedTahap;
        })->count();

        return view('admin.modul.index', compact('penyusunsByJurusanWithAll', 'totalPenyusun', 'menungguValidasi', 'selesaiPenyusunan'));
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
