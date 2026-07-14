<?php

namespace App\Http\Controllers;

use App\Models\ReviewerApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ReviewerApplicationController extends Controller
{
    public function create()
    {
        return view('reviewer.daftar');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_reviewer' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:reviewer_applications,email',
                function ($attribute, $value, $fail) {
                    $isPenyusunUser = \App\Models\User::where('email', $value)->where('is_penyusun', true)->exists();
                    $hasPenyusunApp = \App\Models\PenyusunApplication::where('email', $value)->whereIn('status', ['approved', 'pending'])->exists();
                    
                    if ($isPenyusunUser || $hasPenyusunApp) {
                        $fail('Email sudah terdaftar sebagai Penyusun Modul dan tidak diperbolehkan mendaftar sebagai Reviewer.');
                    }
                }
            ],
            'no_wa' => ['required', 'string', 'max:30', 'regex:/^\d+$/'],
            'nip' => ['nullable', 'string', 'max:32', 'regex:/^\d+$/'],
            'nidn' => ['required', 'string', 'max:32', 'regex:/^\d+$/'],
            'sertifikasi' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120', // Max 5MB
            ],
            'setuju_informasi' => ['accepted'],
        ]);

        $path = null;
        $fileName = null;
        if ($request->hasFile('sertifikasi')) {
            $file = $request->file('sertifikasi');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('certifications', $fileName, 'public');
            
            // Verifikasi file tersimpan
            if (!Storage::disk('public')->exists($path) || Storage::disk('public')->size($path) === 0) {
                return back()->withErrors(['sertifikasi' => 'Gagal mengupload file sertifikasi. Silakan coba lagi.'])->withInput();
            }
        }

        ReviewerApplication::create([
            'nama_reviewer' => $validated['nama_reviewer'],
            'email' => $validated['email'],
            'no_wa' => $validated['no_wa'],
            'nip' => $validated['nip'] ?? null,
            'nidn' => $validated['nidn'],
            'sertifikasi_path' => $path,
            'sertifikasi_name' => $fileName,
            'status' => 'pending',
        ]);

        return redirect()->route('home')->with([
            'status' => 'Pengajuan pendaftaran reviewer berhasil dikirim.',
            'show_modal_reviewer' => true
        ]);
    }
}
