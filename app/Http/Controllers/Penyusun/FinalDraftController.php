<?php

namespace App\Http\Controllers\Penyusun;

use App\Http\Controllers\Controller;
use App\Models\FinalDraft;
use App\Models\PenyusunApplication;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FinalDraftController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ambil aplikasi penyusun yang sudah disetujui
        $application = PenyusunApplication::where('email', $user->email)
            ->where('status', 'approved')
            ->with('mataKuliah')
            ->first();

        if (!$application) {
            return redirect()->route('penyusun.dashboard')
                ->with('error', 'Anda belum memiliki aplikasi yang disetujui.');
        }

        // Cek apakah semua 6 tahap sudah divalidasi
        $allTahapsValidated = $this->checkAllTahapsValidated($application->id);
        
        if (!$allTahapsValidated) {
            return redirect()->route('penyusun.dashboard')
                ->with('error', 'Semua tahap penyusunan harus divalidasi terlebih dahulu sebelum dapat mengupload final draft.');
        }

        // Ambil final draft yang sudah diupload
        $finalDraft = FinalDraft::where('penyusun_application_id', $application->id)
            ->with(['mataKuliah', 'lpmValidator', 'activityLogs.actor'])
            ->first();

        return view('penyusun.final-draft.index', compact('application', 'finalDraft'));
    }

    public function create()
    {
        $user = Auth::user();
        
        $application = PenyusunApplication::where('email', $user->email)
            ->where('status', 'approved')
            ->first();

        if (!$application) {
            return redirect()->route('penyusun.dashboard')
                ->with('error', 'Anda belum memiliki aplikasi yang disetujui.');
        }

        // Cek apakah semua 6 tahap sudah divalidasi
        $allTahapsValidated = $this->checkAllTahapsValidated($application->id);
        
        if (!$allTahapsValidated) {
            return redirect()->route('penyusun.dashboard')
                ->with('error', 'Semua tahap penyusunan harus divalidasi terlebih dahulu sebelum dapat mengupload final draft.');
        }

        // Cek apakah sudah ada final draft
        $existingFinalDraft = FinalDraft::where('penyusun_application_id', $application->id)->first();
        
        if ($existingFinalDraft && !in_array($existingFinalDraft->status, ['rejected', 'rejected_by_reviewer'])) {
            return redirect()->route('penyusun.final-draft.index')
                ->with('error', 'Final draft sudah pernah diupload dan masih dalam proses validasi.');
        }

        // Ambil mata kuliah untuk aplikasi ini
        $mataKuliah = MataKuliah::where('nama_mata_kuliah', $application->mata_kuliah)->first();

        return view('penyusun.final-draft.create', compact('application', 'mataKuliah', 'existingFinalDraft'));
    }

    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            
            $application = PenyusunApplication::where('email', $user->email)
                ->where('status', 'approved')
                ->first();

            if (!$application) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda belum memiliki aplikasi yang disetujui.'
                    ], 403);
                }
                return redirect()->route('penyusun.dashboard')
                    ->with('error', 'Anda belum memiliki aplikasi yang disetujui.');
            }

            // Cek apakah semua 6 tahap sudah divalidasi
            $allTahapsValidated = $this->checkAllTahapsValidated($application->id);
            
            if (!$allTahapsValidated) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Semua tahap penyusunan harus divalidasi terlebih dahulu.'
                    ], 400);
                }
                return redirect()->route('penyusun.dashboard')
                    ->with('error', 'Semua tahap penyusunan harus divalidasi terlebih dahulu.');
            }

            // Cek apakah sudah ada final draft
            $existingFinalDraft = FinalDraft::where('penyusun_application_id', $application->id)->first();
            
            if ($existingFinalDraft && !in_array($existingFinalDraft->status, ['rejected', 'rejected_by_reviewer'])) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Final draft sudah pernah diupload dan masih dalam proses validasi.'
                    ], 400);
                }
                return redirect()->route('penyusun.final-draft.index')
                    ->with('error', 'Final draft sudah pernah diupload dan masih dalam proses validasi.');
            }

        } catch (\Exception $e) {
            Log::error('Error in store method start: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        try {
            $request->validate([
                'judul_modul' => 'required|string|max:255',
                'deskripsi_modul' => 'nullable|string|max:1000',
                'file_modul' => [
                    'required',
                    'file',
                    function ($attribute, $value, $fail) {
                        $extension = strtolower($value->getClientOriginalExtension());
                        $mimeType = $value->getMimeType();
                        
                        if (!in_array($extension, ['doc', 'docx'])) {
                            $fail("File harus berupa dokumen dengan format .doc atau .docx. Ekstensi yang diterima: " . $extension);
                        }
                        
                        $allowedMimeTypes = [
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/octet-stream'
                        ];
                        
                        if (!in_array($mimeType, $allowedMimeTypes)) {
                            $fail("Tipe file tidak didukung. MIME type: " . $mimeType);
                        }
                    },
                    'max:25600', // Max 25MB
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all())
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        }

        try {
            // Ambil mata kuliah
            $mataKuliah = MataKuliah::where('nama_mata_kuliah', $application->mata_kuliah)->first();

            if (!$mataKuliah) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Mata kuliah tidak ditemukan.'
                    ], 404);
                }
                return redirect()->back()
                    ->with('error', 'Mata kuliah tidak ditemukan.');
            }

            // Upload file anonim (blind review): FinalDraft_{appId}_{tanggal}_{timestamp}
            $file = $request->file('file_modul');
            $extension = $file->getClientOriginalExtension();
            $tanggal = now()->format('d-m-Y');
            $timestamp = time();
            
            $fileName = "FinalDraft_APP{$application->id}_{$tanggal}_{$timestamp}.{$extension}";
            
            // Pastikan nama file unik
            $counter = 1;
            while (Storage::disk('public')->exists('final-drafts/' . $fileName)) {
                $fileName = "FinalDraft_APP{$application->id}_{$tanggal}_{$timestamp}_{$counter}.{$extension}";
                $counter++;
            }
            
            $filePath = $file->storeAs('final-drafts', $fileName, 'public');
            
            Log::info('Final draft file uploaded successfully', [
                'original_name' => $file->getClientOriginalName(),
                'stored_name' => $fileName,
                'file_path' => $filePath,
                'file_size' => $file->getSize()
            ]);

            // Verifikasi file tersimpan dengan benar
            if (!Storage::disk('public')->exists($filePath) || Storage::disk('public')->size($filePath) === 0) {
                Log::error('Final draft file upload gagal: ' . $filePath);
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal mengupload file final draft. Silakan coba lagi.'
                    ], 500);
                }
                return redirect()->back()
                    ->with('error', 'Gagal mengupload file final draft. Silakan coba lagi.');
            }

            // Buat atau update final draft
            if ($existingFinalDraft && in_array($existingFinalDraft->status, ['rejected', 'rejected_by_reviewer'])) {
                // Update final draft yang ditolak
                $existingFinalDraft->update([
                    'judul_modul' => $request->judul_modul,
                    'deskripsi_modul' => $request->deskripsi_modul,
                    'file_path' => $filePath,
                    'file_name' => $fileName,
                    'status' => 'pending_review',
                    'uploaded_at' => now(),
                    'catatan_reviewer' => null,
                    'reviewer_validated_at' => null,
                    'reviewer_validated_by' => null,
                    'catatan_lpm' => null,
                    'lpm_validated_at' => null,
                    'lpm_validated_by' => null,
                ]);
                
                $finalDraft = $existingFinalDraft;
                
                Log::info('Final draft berhasil diupdate (resubmit)', [
                    'final_draft_id' => $finalDraft->id,
                    'file_name' => $fileName,
                    'file_path' => $filePath,
                    'penyusun' => $application->nama_penyusun
                ]);
            } else {
                // Buat final draft baru
                $finalDraft = FinalDraft::create([
                    'penyusun_application_id' => $application->id,
                    'mata_kuliah_id' => $mataKuliah->id,
                    'judul_modul' => $request->judul_modul,
                    'deskripsi_modul' => $request->deskripsi_modul,
                    'file_path' => $filePath,
                    'file_name' => $fileName,
                    'status' => 'pending_review',
                    'uploaded_at' => now(),
                ]);
                
                Log::info('Final draft berhasil dibuat', [
                    'final_draft_id' => $finalDraft->id,
                    'file_name' => $fileName,
                    'file_path' => $filePath,
                    'penyusun' => $application->nama_penyusun
                ]);
            }

            if ($request->ajax()) {
                $message = $existingFinalDraft && in_array($existingFinalDraft->status, ['rejected', 'rejected_by_reviewer']) 
                    ? 'Final draft berhasil diupload ulang dan sedang menunggu review.'
                    : 'Final draft berhasil diupload dan sedang menunggu review.';
                    
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'file_name' => $fileName
                ]);
            }

            $successMessage = $existingFinalDraft && in_array($existingFinalDraft->status, ['rejected', 'rejected_by_reviewer']) 
                ? 'Final draft berhasil diupload ulang dengan nama file: ' . $fileName
                : 'Final draft berhasil diupload dengan nama file: ' . $fileName;

            return redirect()->route('penyusun.final-draft.index')
                ->with('success', $successMessage);
                
        } catch (\Exception $e) {
            Log::error('Error upload final draft: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat mengupload final draft: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengupload final draft: ' . $e->getMessage());
        }
    }

    public function show(FinalDraft $finalDraft)
    {
        $user = Auth::user();
        
        $application = PenyusunApplication::where('email', $user->email)
            ->where('status', 'approved')
            ->first();

        if (!$application || $finalDraft->penyusun_application_id !== $application->id) {
            abort(403, 'Anda tidak memiliki akses ke final draft ini.');
        }

        $finalDraft->load(['mataKuliah', 'lpmValidator', 'activityLogs.actor']);

        return view('penyusun.final-draft.show', compact('finalDraft'));
    }

    public function download(FinalDraft $finalDraft)
    {
        $user = Auth::user();
        
        $application = PenyusunApplication::where('email', $user->email)
            ->where('status', 'approved')
            ->first();

        if (!$application || $finalDraft->penyusun_application_id !== $application->id) {
            abort(403, 'Anda tidak memiliki akses ke final draft ini.');
        }

        if (!$finalDraft->file_path || !Storage::disk('public')->exists($finalDraft->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->download(Storage::disk('public')->path($finalDraft->file_path), $finalDraft->file_name);
    }

    private function checkAllTahapsValidated($penyusunApplicationId)
    {
        // Cek apakah semua 6 tahap sudah divalidasi (approved)
        $moduls = \App\Models\Modul::where('penyusun_application_id', $penyusunApplicationId)
            ->where('status', 'approved')
            ->count();

        return $moduls >= 4; // Minimal 4 modul harus sudah approved
    }
}
