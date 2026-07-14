<?php

namespace App\Http\Controllers\Penyusun;

use App\Http\Controllers\Controller;
use App\Models\PublicationModul;
use App\Models\PenyusunApplication;
use App\Models\FinalDraft;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PublicationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil aplikasi penyusun yang sudah disetujui
        $penyusunApplications = PenyusunApplication::where('email', $user->email)
            ->where('status', 'approved')
            ->with(['mataKuliah', 'finalDrafts', 'publicationModuls'])
            ->get();

        return view('penyusun.publication.index', compact('penyusunApplications'));
    }

    public function create(PenyusunApplication $penyusunApplication)
    {
        // Pastikan aplikasi milik user yang login
        if ($penyusunApplication->email !== Auth::user()->email) {
            abort(403);
        }

        // Pastikan aplikasi sudah disetujui
        if ($penyusunApplication->status !== 'approved') {
            abort(403, 'Aplikasi belum disetujui.');
        }

        // Pastikan ada final draft yang sudah disetujui
        $finalDraft = $penyusunApplication->finalDrafts()
            ->where('status', 'approved')
            ->whereNotNull('lpm_validated_at')
            ->whereNotNull('lpm_validated_by')
            ->first();

        if (!$finalDraft) {
            return redirect()->route('penyusun.publication.index')
                ->with('error', 'Final draft belum disetujui oleh LPM.');
        }

        // Cek apakah sudah ada publikasi
        $existingPublication = $penyusunApplication->publicationModuls()->first();

        // Jika ada publikasi dan status bukan rejected, redirect ke show
        if ($existingPublication && $existingPublication->status !== 'rejected') {
            return redirect()->route('penyusun.publication.show', $existingPublication)
                ->with('info', 'Publikasi sudah ada untuk aplikasi ini.');
        }

        return view('penyusun.publication.create', compact('penyusunApplication', 'finalDraft', 'existingPublication'));
    }

    public function store(Request $request, PenyusunApplication $penyusunApplication)
    {
        // Pastikan aplikasi milik user yang login
        if ($penyusunApplication->email !== Auth::user()->email) {
            abort(403);
        }

        $request->validate([
            'judul_modul' => 'required|string|max:255',
            'deskripsi_modul' => 'nullable|string|max:1000',
            'final_modul_file' => 'required|file|mimes:pdf,doc,docx|max:25600', // 25MB
            'sertifikat_hki_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB
            'nik' => 'required|string|size:16|regex:/^[0-9]{16}$/',
            'npwp' => [
                'required',
                'string',
                'regex:/^[0-9]{15,16}$/',
                function ($attribute, $value, $fail) {
                    if (strlen($value) < 15 || strlen($value) > 16) {
                        $fail('NPWP harus terdiri dari 15 atau 16 digit angka.');
                    }
                },
            ],
            'nama_bank' => 'required|string|max:255',
            'nomor_rekening' => 'required|string|max:50',
            'nama_pemilik_rekening' => 'required|string|max:255',
        ]);

        try {
            // Pastikan ada final draft yang sudah disetujui
            $finalDraft = $penyusunApplication->finalDrafts()
                ->where('status', 'approved')
                ->whereNotNull('lpm_validated_at')
                ->whereNotNull('lpm_validated_by')
                ->first();

            if (!$finalDraft) {
                return redirect()->back()
                    ->with('error', 'Final draft belum disetujui oleh LPM.');
            }

            // Upload file final modul
            $finalModulFile = $request->file('final_modul_file');
            $finalModulFileName = time() . '_final_modul_' . $finalModulFile->getClientOriginalName();
            $finalModulFilePath = $finalModulFile->storeAs('publications/final-moduls', $finalModulFileName, 'public');

            // Upload file sertifikat HKI
            $sertifikatHkiFile = $request->file('sertifikat_hki_file');
            $sertifikatHkiFileName = time() . '_sertifikat_hki_' . $sertifikatHkiFile->getClientOriginalName();
            $sertifikatHkiFilePath = $sertifikatHkiFile->storeAs('publications/sertifikat-hki', $sertifikatHkiFileName, 'public');

            // Cek apakah sudah ada publikasi yang ditolak
            $existingPublication = $penyusunApplication->publicationModuls()->first();

            if ($existingPublication && $existingPublication->status === 'rejected') {
                // Update publikasi yang ditolak
                $existingPublication->update([
                    'judul_modul' => $request->judul_modul,
                    'deskripsi_modul' => $request->deskripsi_modul,
                    'final_modul_file_path' => $finalModulFilePath,
                    'final_modul_file_name' => $finalModulFileName,
                    'sertifikat_hki_file_path' => $sertifikatHkiFilePath,
                    'sertifikat_hki_file_name' => $sertifikatHkiFileName,
                    'nik' => $request->nik,
                    'npwp' => $request->npwp,
                    'nama_bank' => $request->nama_bank,
                    'nomor_rekening' => $request->nomor_rekening,
                    'nama_pemilik_rekening' => $request->nama_pemilik_rekening,
                    'status' => 'pending',
                    'uploaded_at' => now(),
                    'catatan_admin' => null,
                    'validated_at' => null,
                    'validated_by' => null,
                ]);

                $publicationModul = $existingPublication;

                Log::info('Publication modul updated (resubmit)', [
                    'publication_modul_id' => $publicationModul->id,
                    'penyusun_application_id' => $penyusunApplication->id,
                    'penyusun_id' => Auth::id(),
                    'penyusun_name' => Auth::user()->name
                ]);
            } else {
                // Buat publikasi modul baru
                $publicationModul = PublicationModul::create([
                    'penyusun_application_id' => $penyusunApplication->id,
                    'final_draft_id' => $finalDraft->id,
                    'judul_modul' => $request->judul_modul,
                    'deskripsi_modul' => $request->deskripsi_modul,
                    'final_modul_file_path' => $finalModulFilePath,
                    'final_modul_file_name' => $finalModulFileName,
                    'sertifikat_hki_file_path' => $sertifikatHkiFilePath,
                    'sertifikat_hki_file_name' => $sertifikatHkiFileName,
                    'nik' => $request->nik,
                    'npwp' => $request->npwp,
                    'nama_bank' => $request->nama_bank,
                    'nomor_rekening' => $request->nomor_rekening,
                    'nama_pemilik_rekening' => $request->nama_pemilik_rekening,
                    'uploaded_at' => now(),
                ]);

                Log::info('Publication modul created by penyusun', [
                    'publication_modul_id' => $publicationModul->id,
                    'penyusun_application_id' => $penyusunApplication->id,
                    'penyusun_id' => Auth::id(),
                    'penyusun_name' => Auth::user()->name
                ]);
            }

            $successMessage = $existingPublication && $existingPublication->status === 'rejected'
                ? 'Publikasi modul berhasil diupload ulang. Menunggu validasi admin.'
                : 'Publikasi modul berhasil diupload. Menunggu validasi admin.';

            return redirect()->route('penyusun.publication.show', $publicationModul)
                ->with('success', $successMessage);
        } catch (\Exception $e) {
            Log::error('Error creating publication modul: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengupload publikasi modul: ' . $e->getMessage());
        }
    }

    public function show(PublicationModul $publicationModul)
    {
        // Pastikan publikasi milik user yang login
        if ($publicationModul->penyusunApplication->email !== Auth::user()->email) {
            abort(403);
        }

        $publicationModul->load(['penyusunApplication.mataKuliah', 'finalDraft', 'validator']);

        return view('penyusun.publication.show', compact('publicationModul'));
    }

    public function downloadFinalModul(PublicationModul $publicationModul)
    {
        // Pastikan publikasi milik user yang login
        if ($publicationModul->penyusunApplication->email !== Auth::user()->email) {
            abort(403);
        }

        if (!$publicationModul->final_modul_file_path || !Storage::disk('public')->exists($publicationModul->final_modul_file_path)) {
            abort(404, 'File final modul tidak ditemukan.');
        }

        return response()->download(
            Storage::disk('public')->path($publicationModul->final_modul_file_path),
            $publicationModul->final_modul_file_name
        );
    }

    public function downloadSertifikatHki(PublicationModul $publicationModul)
    {
        // Pastikan publikasi milik user yang login
        if ($publicationModul->penyusunApplication->email !== Auth::user()->email) {
            abort(403);
        }

        if (!$publicationModul->sertifikat_hki_file_path || !Storage::disk('public')->exists($publicationModul->sertifikat_hki_file_path)) {
            abort(404, 'File sertifikat HKI tidak ditemukan.');
        }

        return response()->download(
            Storage::disk('public')->path($publicationModul->sertifikat_hki_file_path),
            $publicationModul->sertifikat_hki_file_name
        );
    }
}
