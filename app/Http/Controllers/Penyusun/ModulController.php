<?php

namespace App\Http\Controllers\Penyusun;

use App\Http\Controllers\Controller;
use App\Models\Modul;
use App\Models\PenyusunApplication;
use App\Models\Setting;
use App\Models\TahapPenyusunan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ModulController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ambil aplikasi penyusun yang sudah disetujui dengan relasi mata kuliah
        $application = PenyusunApplication::where('email', $user->email)
            ->where('status', 'approved')
            ->with('mataKuliah')
            ->first();

        if (!$application) {
            return redirect()->route('penyusun.dashboard')
                ->with('error', 'Anda belum memiliki aplikasi yang disetujui.');
        }

        // Ambil semua tahap penyusunan global (termasuk yang sudah berlalu)
        $tahaps = TahapPenyusunan::global()
            ->orderBy('tahap')
            ->get();

        // Ambil modul yang sudah diupload
        $moduls = Modul::where('penyusun_application_id', $application->id)
            ->with(['mataKuliah', 'tahapPenyusunan'])
            ->orderBy('tahap_id')
            ->get();

        $templateModulUrl = Setting::get('template_modul_url');

        return view('penyusun.modul.index', compact('application', 'tahaps', 'moduls', 'templateModulUrl'));
    }

    public function create($tahap)
    {
        $user = Auth::user();
        
        $application = PenyusunApplication::where('email', $user->email)
            ->where('status', 'approved')
            ->first();

        if (!$application) {
            return redirect()->route('penyusun.dashboard')
                ->with('error', 'Anda belum memiliki aplikasi yang disetujui.');
        }

        $tahapPenyusunan = TahapPenyusunan::global()
            ->where('tahap', $tahap)
            ->first();

        if (!$tahapPenyusunan) {
            return redirect()->route('penyusun.modul.index')
                ->with('error', 'Tahap tidak ditemukan.');
        }

        // Cek apakah tahap dapat diakses oleh user ini
        if (!$tahapPenyusunan->isAccessibleForUser($application->id)) {
            return redirect()->route('penyusun.modul.index')
                ->with('error', 'Tahap ini belum dapat diakses atau sudah berakhir.');
        }

        // Ambil mata kuliah untuk aplikasi ini
        $mataKuliah = \App\Models\MataKuliah::where('nama_mata_kuliah', $application->mata_kuliah)->first();

        return view('penyusun.modul.create', compact('application', 'tahapPenyusunan', 'mataKuliah'));
    }

    public function store(Request $request, $tahap)
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

            $tahapPenyusunan = TahapPenyusunan::global()
                ->where('tahap', $tahap)
                ->first();

            if (!$tahapPenyusunan) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tahap tidak ditemukan.'
                    ], 404);
                }
                return redirect()->route('penyusun.modul.index')
                    ->with('error', 'Tahap tidak ditemukan.');
            }

            // Cek apakah tahap dapat diakses oleh user ini
            if (!$tahapPenyusunan->isAccessibleForUser($application->id)) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tahap ini belum dapat diakses atau sudah berakhir.'
                    ], 400);
                }
                return redirect()->route('penyusun.modul.index')
                    ->with('error', 'Tahap ini belum dapat diakses atau sudah berakhir.');
            }
        } catch (\Exception $e) {
            Log::error('Error in store method start: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
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
                        
                        Log::info('File validation', [
                            'extension' => $extension,
                            'mime_type' => $mimeType,
                            'size' => $value->getSize(),
                            'original_name' => $value->getClientOriginalName()
                        ]);
                        
                        if (!in_array($extension, ['doc', 'docx'])) {
                            $fail("File harus berupa dokumen dengan format .doc atau .docx. Ekstensi yang diterima: " . $extension);
                        }
                        
                        // Validasi tambahan untuk MIME type
                        $allowedMimeTypes = [
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/octet-stream' // Beberapa browser mengirim DOC sebagai octet-stream
                        ];
                        
                        if (!in_array($mimeType, $allowedMimeTypes)) {
                            $fail("Tipe file tidak didukung. MIME type: " . $mimeType);
                        }
                    },
                    'max:10240', // Max 10MB
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
            $mataKuliah = \App\Models\MataKuliah::where('nama_mata_kuliah', $application->mata_kuliah)->first();

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
        } catch (\Exception $e) {
            Log::error('Error getting mata kuliah: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat mengambil data mata kuliah: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengambil data mata kuliah.');
        }

        try {
            // Cek apakah modul sudah ada untuk tahap ini
            $existingModul = Modul::where('penyusun_application_id', $application->id)
                ->where('tahap_id', $tahapPenyusunan->id)
                ->first();

            if ($existingModul) {
                // Hapus file lama jika ada
                if ($existingModul->file_path && Storage::disk('public')->exists($existingModul->file_path)) {
                    Storage::disk('public')->delete($existingModul->file_path);
                    Log::info('File lama dihapus: ' . $existingModul->file_path);
                }
                
                // Hapus record modul lama
                $existingModul->delete();
                Log::info('Record modul lama dihapus: ' . $existingModul->id);
            }

            // Upload file dengan format: Tahap x_nama penyusun_tanggal-bulan-tahun
            $file = $request->file('file_modul');
            $extension = $file->getClientOriginalExtension();
            $namaPenyusun = Str::slug($application->nama_penyusun);
            $tanggal = now()->format('d-m-Y');
            $timestamp = time(); // Untuk memastikan nama file unik
            
            $fileName = "Tahap_{$tahapPenyusunan->tahap}_{$namaPenyusun}_{$tanggal}_{$timestamp}.{$extension}";
            
            // Pastikan nama file unik dengan menambahkan counter jika diperlukan
            $originalFileName = $fileName;
            $counter = 1;
            while (Storage::disk('public')->exists('moduls/' . $fileName)) {
                $fileName = "Tahap_{$tahapPenyusunan->tahap}_{$namaPenyusun}_{$tanggal}_{$timestamp}_{$counter}.{$extension}";
                $counter++;
            }
            
            $filePath = $file->storeAs('moduls', $fileName, 'public');
            
            Log::info('File uploaded successfully', [
                'original_name' => $file->getClientOriginalName(),
                'stored_name' => $fileName,
                'file_path' => $filePath,
                'file_size' => $file->getSize()
            ]);

            // Verifikasi file tersimpan dengan benar
            if (!Storage::disk('public')->exists($filePath) || Storage::disk('public')->size($filePath) === 0) {
                Log::error('File upload gagal: ' . $filePath);
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal mengupload file modul. Silakan coba lagi.'
                    ], 500);
                }
                return redirect()->back()
                    ->with('error', 'Gagal mengupload file modul. Silakan coba lagi.');
            }

            // Buat 1 modul untuk tahap ini
            $modul = Modul::create([
                'penyusun_application_id' => $application->id,
                'mata_kuliah_id' => $mataKuliah->id,
                'tahap_id' => $tahapPenyusunan->id,
                'nomor_modul' => $tahapPenyusunan->tahap, // Gunakan nomor tahap sebagai nomor modul
                'judul_modul' => $request->judul_modul,
                'deskripsi_modul' => $request->deskripsi_modul,
                'file_path' => $filePath,
                'file_name' => $fileName,
                'status' => 'pending',
                'uploaded_at' => now(),
            ]);
            
            Log::info('Modul berhasil dibuat', [
                'modul_id' => $modul->id,
                'file_name' => $fileName,
                'file_path' => $filePath,
                'tahap' => $tahapPenyusunan->tahap,
                'penyusun' => $application->nama_penyusun
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Modul berhasil diupload dan sedang menunggu validasi admin.',
                    'file_name' => $fileName
                ]);
            }

            return redirect()->route('penyusun.modul.index')
                ->with('success', 'Modul berhasil diupload dengan nama file: ' . $fileName);
                
        } catch (\Exception $e) {
            Log::error('Error upload modul: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat mengupload modul: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengupload modul: ' . $e->getMessage());
        }
    }

    public function show(Modul $modul)
    {
        $user = Auth::user();
        
        $application = PenyusunApplication::where('email', $user->email)
            ->where('status', 'approved')
            ->first();

        if (!$application || $modul->penyusun_application_id !== $application->id) {
            abort(403, 'Anda tidak memiliki akses ke modul ini.');
        }

        $modul->load(['mataKuliah', 'validator', 'tahapPenyusunan']);

        return view('penyusun.modul.show', compact('modul'));
    }

    public function download(Modul $modul)
    {
        $user = Auth::user();
        
        $application = PenyusunApplication::where('email', $user->email)
            ->where('status', 'approved')
            ->first();

        if (!$application || $modul->penyusun_application_id !== $application->id) {
            abort(403, 'Anda tidak memiliki akses ke modul ini.');
        }

        if (!$modul->file_path || !Storage::disk('public')->exists($modul->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->download(Storage::disk('public')->path($modul->file_path), $modul->file_name);
    }

    public function destroy(Modul $modul)
    {
        $user = Auth::user();
        
        $application = PenyusunApplication::where('email', $user->email)
            ->where('status', 'approved')
            ->first();

        if (!$application || $modul->penyusun_application_id !== $application->id) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke modul ini.'
                ], 403);
            }
            abort(403, 'Anda tidak memiliki akses ke modul ini.');
        }

        // Hanya bisa menghapus modul dengan status pending
        if ($modul->status !== 'pending') {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Modul yang sudah disetujui atau ditolak tidak dapat dihapus.'
                ], 400);
            }
            return redirect()->back()
                ->with('error', 'Modul yang sudah disetujui atau ditolak tidak dapat dihapus.');
        }

        try {
            // Hapus file dari storage
            if ($modul->file_path && Storage::disk('public')->exists($modul->file_path)) {
                Storage::disk('public')->delete($modul->file_path);
                Log::info('File modul dihapus: ' . $modul->file_path);
            }

            // Hapus record dari database
            $modul->delete();
            
            Log::info('Modul berhasil dihapus', [
                'modul_id' => $modul->id,
                'penyusun' => $application->nama_penyusun
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Modul berhasil dihapus. Anda dapat mengupload modul baru.'
                ]);
            }

            return redirect()->route('penyusun.modul.index')
                ->with('success', 'Modul berhasil dihapus. Anda dapat mengupload modul baru.');
                
        } catch (\Exception $e) {
            Log::error('Error deleting modul: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus modul: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus modul: ' . $e->getMessage());
        }
    }
}
