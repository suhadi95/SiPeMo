<?php

namespace App\Http\Controllers;

use App\Models\PenyusunApplication;
use App\Models\Jurusan;
use App\Models\MataKuliah;
use App\Rules\EmailNotValidatedByAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PenyusunApplicationController extends Controller
{
    public function create()
    {
        $jurusan = Jurusan::all();
        return view('penyusun.daftar', compact('jurusan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_penyusun' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'email', 
                'max:255',
                new EmailNotValidatedByAdmin,
                function ($attribute, $value, $fail) {
                    $isReviewerUser = \App\Models\User::where('email', $value)->where('is_reviewer', true)->exists();
                    $hasReviewerApp = \App\Models\ReviewerApplication::where('email', $value)->whereIn('status', ['approved', 'pending'])->exists();
                    if ($isReviewerUser || $hasReviewerApp) {
                        $fail('Email sudah terdaftar sebagai Reviewer dan tidak diperbolehkan mendaftar sebagai Penyusun.');
                    }
                }
            ],
            'no_wa' => ['required', 'string', 'max:30', 'regex:/^\d+$/'],
            'nip' => ['nullable', 'string', 'max:32', 'regex:/^\d+$/'],
            'nidn' => ['required', 'string', 'max:32', 'regex:/^\d+$/'],
            'judul_bahan_ajar' => ['required', 'string', 'max:255'],
            'jurusan_id' => ['required', 'exists:jurusans,id'],
            'mata_kuliah_id' => ['required', 'exists:mata_kuliahs,id'],
            // Validasi berbasis ekstensi agar tidak terblokir oleh deteksi MIME yang tidak konsisten (mis. octet-stream)
            'draft' => [
                'nullable',
                'file',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $extension = strtolower($value->getClientOriginalExtension());
                        if (! in_array($extension, ['doc', 'docx'])) {
                            $fail("The {$attribute} must be a file of type: doc, docx.");
                        }
                    }
                },
                'max:1024',
            ],
            'setuju_informasi' => ['accepted'],
            'setuju_pelaksanaan' => ['accepted'],
            'setuju_jml_modul' => ['accepted'],
            'setuju_pembiayaan' => ['accepted'],
        ]);

        // Jika ada NIP, anggap pendaftar ASN. Validasi bisnis: NIP wajib bila ASN.
        // Catatan: penentuan ASN tidak otomatis, diasumsikan oleh admin saat review jika perlu.

        $path = null;
        if ($request->hasFile('draft')) {
            $file = $request->file('draft');
            $path = $file->store('drafts', 'public');
            
            // Verifikasi file tersimpan dengan benar
            if (!Storage::disk('public')->exists($path) || Storage::disk('public')->size($path) === 0) {
                return back()->withErrors(['draft' => 'Gagal mengupload file draft. Silakan coba lagi.'])->withInput();
            }
        }

        $mataKuliah = MataKuliah::find($validated['mata_kuliah_id']);
        
        PenyusunApplication::create([
            'nama_penyusun' => $validated['nama_penyusun'],
            'email' => $validated['email'],
            'no_wa' => $validated['no_wa'] ?? null,
            'nip' => $validated['nip'] ?? null,
            'nidn' => $validated['nidn'] ?? null,
            'judul_bahan_ajar' => $validated['judul_bahan_ajar'],
            'jurusan' => $mataKuliah->jurusan->nama_jurusan,
            'semester' => 'Semester ' . $mataKuliah->semester,
            'mata_kuliah' => $mataKuliah->nama_mata_kuliah,
            'mata_kuliah_id' => $mataKuliah->id,
            'draft_path' => $path,
            'setuju_informasi' => $validated['setuju_informasi'],
            'setuju_pelaksanaan' => $validated['setuju_pelaksanaan'],
            'setuju_jml_modul' => $validated['setuju_jml_modul'],
            'setuju_pembiayaan' => $validated['setuju_pembiayaan'],
            'status' => 'pending',
        ]);

        return redirect()->route('home')->with([
            'status' => 'Pengajuan pendaftaran penyusun berhasil dikirim.',
            'show_modal' => true
        ]);
    }
}


