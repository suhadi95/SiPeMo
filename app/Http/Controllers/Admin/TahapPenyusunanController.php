<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinalDraft;
use App\Models\FinalDraftReview;
use App\Models\MataKuliah;
use App\Models\Modul;
use App\Models\PenyusunApplication;
use App\Models\PublicationModul;
use App\Models\ReviewerApplication;
use App\Models\Setting;
use App\Models\TahapPenyusunan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TahapPenyusunanController extends Controller
{
    public function index()
    {
        $tahaps = TahapPenyusunan::global()->orderBy('tahap')->get();
        $templateModulUrl = Setting::get('template_modul_url');

        return view('admin.tahap-penyusunan.index', compact('tahaps', 'templateModulUrl'));
    }

    public function updateTemplate(Request $request)
    {
        $request->validate([
            'template_modul_url' => 'nullable|url|max:2048',
        ], [
            'template_modul_url.url' => 'Link template modul harus berupa URL yang valid.',
        ]);

        Setting::set('template_modul_url', $request->template_modul_url);

        return redirect()->route('admin.tahap-penyusunan.index')
            ->with('success', 'Template modul berhasil disimpan.');
    }

    public function create()
    {
        if (TahapPenyusunan::query()->exists()) {
            return redirect()->route('admin.tahap-penyusunan.index')
                ->with('error', 'Periode sudah ada. Reset periode terlebih dahulu sebelum membuat periode baru.');
        }

        return view('admin.tahap-penyusunan.create');
    }

    public function store(Request $request)
    {
        if (TahapPenyusunan::query()->exists()) {
            return redirect()->route('admin.tahap-penyusunan.index')
                ->with('error', 'Periode sudah ada. Reset periode terlebih dahulu sebelum membuat periode baru.');
        }

        $request->validate([
            'nama_periode' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'jumlah_tahap' => 'required|integer|min:1|max:10',
            'deskripsi_tahap' => 'required|array',
            'deskripsi_tahap.*' => 'required|string|max:1000',
        ], [
            'jumlah_tahap.required' => 'Jumlah tahap wajib diisi.',
            'jumlah_tahap.min' => 'Jumlah tahap minimal 1.',
            'jumlah_tahap.max' => 'Jumlah tahap maksimal 10.',
            'deskripsi_tahap.required' => 'Deskripsi tiap tahap wajib diisi.',
            'deskripsi_tahap.*.required' => 'Deskripsi setiap tahap wajib diisi.',
        ]);

        $jumlahTahap = (int) $request->jumlah_tahap;

        if (count($request->deskripsi_tahap) !== $jumlahTahap) {
            return back()
                ->withErrors(['deskripsi_tahap' => 'Jumlah deskripsi tahap harus sama dengan jumlah tahap.'])
                ->withInput();
        }

        DB::transaction(function () use ($request, $jumlahTahap) {
            $tanggalMulai = $request->tanggal_mulai;
            $tanggalSelesai = $request->tanggal_selesai;
            $daysPerTahap = (strtotime($tanggalSelesai) - strtotime($tanggalMulai)) / ($jumlahTahap * 24 * 3600);

            for ($i = 1; $i <= $jumlahTahap; $i++) {
                $startDate = date('Y-m-d', strtotime($tanggalMulai) + (($i - 1) * $daysPerTahap * 24 * 3600));
                $endDate = date('Y-m-d', strtotime($tanggalMulai) + ($i * $daysPerTahap * 24 * 3600) - (24 * 3600));

                if ($i === $jumlahTahap) {
                    $endDate = $tanggalSelesai;
                }

                TahapPenyusunan::create([
                    'tahap' => $i,
                    'nama_tahap' => "Tahap {$i}",
                    'nama_periode' => $request->nama_periode,
                    'tanggal_mulai' => $startDate,
                    'tanggal_selesai' => $endDate,
                    'deskripsi' => $request->deskripsi_tahap[$i - 1],
                ]);
            }
        });

        return redirect()->route('admin.tahap-penyusunan.index')
            ->with('success', 'Periode tahap penyusunan berhasil dibuat.');
    }

    public function edit(TahapPenyusunan $tahap)
    {
        return view('admin.tahap-penyusunan.edit', compact('tahap'));
    }

    public function update(Request $request, TahapPenyusunan $tahap)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'deskripsi' => 'required|string|max:1000',
        ]);

        $tahap->update([
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('admin.tahap-penyusunan.index')
            ->with('success', 'Tahap penyusunan berhasil diperbarui.');
    }

    public function activate(TahapPenyusunan $tahap)
    {
        $tanggalMulai = now()->subDay()->toDateString();

        $tahap->update([
            'tanggal_mulai' => $tanggalMulai,
        ]);

        return redirect()->route('admin.tahap-penyusunan.index')
            ->with('success', 'Tahap penyusunan berhasil diaktifkan.');
    }

    public function reset()
    {
        $filePaths = $this->collectUploadedFilePaths();

        DB::transaction(function () {
            PublicationModul::query()->delete();
            FinalDraft::query()->delete();
            Modul::query()->delete();
            TahapPenyusunan::query()->delete();
            PenyusunApplication::query()->delete();
            ReviewerApplication::query()->delete();

            MataKuliah::query()->update(['reviewer_id' => null]);

            User::query()
                ->where('is_admin', false)
                ->where('is_lpm', false)
                ->delete();
        });

        $this->deleteCollectedFiles($filePaths);
        $this->cleanupKnownUploadDirectories();

        return redirect()->route('admin.tahap-penyusunan.index')
            ->with('success', 'Periode berhasil direset. Progres, file upload, aplikasi, dan akun penyusun/reviewer telah dihapus. Anda dapat membuat periode baru.');
    }

    private function collectUploadedFilePaths(): array
    {
        $paths = [];

        $paths = array_merge($paths, Modul::query()->whereNotNull('file_path')->pluck('file_path')->all());
        $paths = array_merge($paths, FinalDraft::query()->whereNotNull('file_path')->pluck('file_path')->all());
        $paths = array_merge($paths, PublicationModul::query()->whereNotNull('final_modul_file_path')->pluck('final_modul_file_path')->all());
        $paths = array_merge($paths, PublicationModul::query()->whereNotNull('sertifikat_hki_file_path')->pluck('sertifikat_hki_file_path')->all());
        $paths = array_merge($paths, PenyusunApplication::query()->whereNotNull('draft_path')->pluck('draft_path')->all());
        $paths = array_merge($paths, ReviewerApplication::query()->whereNotNull('sertifikasi_path')->pluck('sertifikasi_path')->all());

        if (class_exists(FinalDraftReview::class)) {
            $paths = array_merge(
                $paths,
                FinalDraftReview::query()->whereNotNull('validator_signature')->pluck('validator_signature')->all()
            );
        }

        return array_values(array_unique(array_filter($paths)));
    }

    private function deleteCollectedFiles(array $paths): void
    {
        foreach ($paths as $path) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }

    private function cleanupKnownUploadDirectories(): void
    {
        $directories = [
            'moduls',
            'final-drafts',
            'publications',
            'drafts',
            'certifications',
            'validation-signatures',
        ];

        foreach ($directories as $directory) {
            if (Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->deleteDirectory($directory);
            }
        }
    }
}
