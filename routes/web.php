<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PenyusunApplicationController;
use App\Http\Controllers\Admin\JurusanController;
use App\Http\Controllers\Admin\MataKuliahController;
use App\Http\Controllers\Admin\TahapPenyusunanController;
use App\Http\Controllers\Admin\ModulController as AdminModulController;
use App\Http\Controllers\Admin\FinalDraftController as AdminFinalDraftController;
use App\Http\Controllers\Admin\PublicationController as AdminPublicationController;
use App\Http\Controllers\Penyusun\PublicationController as PenyusunPublicationController;
use App\Http\Controllers\Penyusun\ModulController as PenyusunModulController;
use App\Http\Controllers\Penyusun\FinalDraftController as PenyusunFinalDraftController;
use App\Http\Controllers\Lpm\DashboardController as LpmDashboardController;
use App\Http\Controllers\Lpm\FinalDraftController as LpmFinalDraftController;
use App\Http\Controllers\Lpm\MonitoringController as LpmMonitoringController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('home');
})->name('home');

// Route untuk halaman progres penyusunan (tanpa kolom aksi)
Route::get('/progres-penyusunan', function () {
    // Ambil semua penyusun yang approved dengan relasi yang diperlukan
    $penyusuns = \App\Models\PenyusunApplication::where('status', 'approved')
        ->with(['mataKuliah.jurusan', 'moduls.tahapPenyusunan', 'finalDrafts', 'publicationModuls'])
        ->orderBy('created_at', 'desc')
        ->get();

    // Ambil semua jurusan
    $allJurusans = \App\Models\Jurusan::orderBy('nama_jurusan')->get();

    // Kelompokkan penyusun berdasarkan jurusan
    $penyusunsByJurusan = $penyusuns->groupBy(function($penyusun) {
        return $penyusun->mataKuliah?->jurusan?->nama_jurusan ?? 'Lainnya';
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
    
    return view('progres-penyusunan', compact('penyusunsByJurusanWithAll'));
})->name('progres-penyusunan');

// Dashboard dengan redirect berdasarkan role
Route::get('/dashboard', function () {
    $user = Auth::user();
    
    // Jika belum login, redirect ke halaman utama
    if (!$user) {
        return redirect()->route('home');
    }
    
    // Redirect berdasarkan role pengguna
    if ($user->is_admin) {
        return redirect()->route('admin.dashboard');
    }
    
    if ($user->is_penyusun) {
        return redirect()->route('penyusun.dashboard');
    }
    
    if ($user->is_lpm) {
        return redirect()->route('lpm.dashboard');
    }
    
    if ($user->is_reviewer) {
        return redirect()->route('reviewer.dashboard');
    }
    
    // Untuk pengguna umum (tanpa role khusus)
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->group(function () {
    // Pisahkan path admin dashboard agar tidak bentrok dengan /dashboard
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Admin penyusun
    Route::get('/admin/penyusun', [\App\Http\Controllers\Admin\PenyusunApplicationAdminController::class, 'index'])->name('admin.penyusun.index');
    Route::get('/admin/penyusun/laporan/download', [\App\Http\Controllers\Admin\PenyusunApplicationAdminController::class, 'downloadLaporanValidasi'])->name('admin.penyusun.download-laporan');
    Route::get('/admin/penyusun/laporan/mk-tersedia/download', [\App\Http\Controllers\Admin\PenyusunApplicationAdminController::class, 'downloadLaporanMataKuliahTersedia'])->name('admin.penyusun.download-laporan-mk-tersedia');
    Route::get('/admin/penyusun/{application}', [\App\Http\Controllers\Admin\PenyusunApplicationAdminController::class, 'show'])->name('admin.penyusun.show');
    Route::get('/admin/penyusun/{application}/download', [\App\Http\Controllers\Admin\PenyusunApplicationAdminController::class, 'downloadDraft'])->name('admin.penyusun.download');
    Route::post('/admin/penyusun/{application}/approve', [\App\Http\Controllers\Admin\PenyusunApplicationAdminController::class, 'approve'])->name('admin.penyusun.approve');
    Route::post('/admin/penyusun/{application}/reject', [\App\Http\Controllers\Admin\PenyusunApplicationAdminController::class, 'reject'])->name('admin.penyusun.reject');
    Route::delete('/admin/penyusun/{application}', [\App\Http\Controllers\Admin\PenyusunApplicationAdminController::class, 'destroy'])->name('admin.penyusun.destroy');

    // Admin reviewer
    Route::get('/admin/reviewer', [\App\Http\Controllers\Admin\ReviewerApplicationAdminController::class, 'index'])->name('admin.reviewer.index');
    Route::get('/admin/reviewer/{application}', [\App\Http\Controllers\Admin\ReviewerApplicationAdminController::class, 'show'])->name('admin.reviewer.show');
    Route::get('/admin/reviewer/{application}/download-certification', [\App\Http\Controllers\Admin\ReviewerApplicationAdminController::class, 'downloadCertification'])->name('admin.reviewer.download-certification');
    Route::post('/admin/reviewer/{application}/approve', [\App\Http\Controllers\Admin\ReviewerApplicationAdminController::class, 'approve'])->name('admin.reviewer.approve');
    Route::post('/admin/reviewer/{application}/reject', [\App\Http\Controllers\Admin\ReviewerApplicationAdminController::class, 'reject'])->name('admin.reviewer.reject');
    Route::delete('/admin/reviewer/{application}', [\App\Http\Controllers\Admin\ReviewerApplicationAdminController::class, 'destroy'])->name('admin.reviewer.destroy');

    // Admin jurusan
    Route::resource('admin/jurusan', JurusanController::class)->names([
        'index' => 'admin.jurusan.index',
        'create' => 'admin.jurusan.create',
        'store' => 'admin.jurusan.store',
        'show' => 'admin.jurusan.show',
        'edit' => 'admin.jurusan.edit',
        'update' => 'admin.jurusan.update',
        'destroy' => 'admin.jurusan.destroy',
    ]);

    // Admin mata kuliah
    Route::resource('admin/mata-kuliah', MataKuliahController::class)->names([
        'index' => 'admin.mata-kuliah.index',
        'create' => 'admin.mata-kuliah.create',
        'store' => 'admin.mata-kuliah.store',
        'show' => 'admin.mata-kuliah.show',
        'edit' => 'admin.mata-kuliah.edit',
        'update' => 'admin.mata-kuliah.update',
        'destroy' => 'admin.mata-kuliah.destroy',
    ]);

    // Admin tahap penyusunan
    Route::get('/admin/tahap-penyusunan', [TahapPenyusunanController::class, 'index'])->name('admin.tahap-penyusunan.index');
    Route::get('/admin/tahap-penyusunan/create', [TahapPenyusunanController::class, 'create'])->name('admin.tahap-penyusunan.create');
    Route::post('/admin/tahap-penyusunan', [TahapPenyusunanController::class, 'store'])->name('admin.tahap-penyusunan.store');
    Route::get('/admin/tahap-penyusunan/{tahap}/edit', [TahapPenyusunanController::class, 'edit'])->name('admin.tahap-penyusunan.edit');
    Route::put('/admin/tahap-penyusunan/{tahap}', [TahapPenyusunanController::class, 'update'])->name('admin.tahap-penyusunan.update');
    Route::post('/admin/tahap-penyusunan/{tahap}/activate', [TahapPenyusunanController::class, 'activate'])->name('admin.tahap-penyusunan.activate');
    Route::post('/admin/tahap-penyusunan/reset', [TahapPenyusunanController::class, 'reset'])->name('admin.tahap-penyusunan.reset');

    // Admin modul
    Route::get('/admin/modul', [AdminModulController::class, 'index'])->name('admin.modul.index');
    Route::get('/admin/modul/pending', [AdminModulController::class, 'pending'])->name('admin.modul.pending');
    Route::get('/admin/modul/approved', [AdminModulController::class, 'approved'])->name('admin.modul.approved');
    Route::get('/admin/modul/rejected', [AdminModulController::class, 'rejected'])->name('admin.modul.rejected');
    Route::get('/admin/modul/{penyusunApplication}', [AdminModulController::class, 'show'])->name('admin.modul.show');
    Route::post('/admin/modul/{modul}/approve', [AdminModulController::class, 'approve'])->name('admin.modul.approve');
    Route::post('/admin/modul/{modul}/reject', [AdminModulController::class, 'reject'])->name('admin.modul.reject');
    Route::post('/admin/modul/{modul}/reject-without-note', [AdminModulController::class, 'rejectWithoutNote'])->name('admin.modul.reject-without-note');
    Route::post('/admin/modul/{modul}/revoke-approval', [AdminModulController::class, 'revokeApproval'])->name('admin.modul.revoke-approval');
    Route::get('/admin/modul/{modul}/download', [AdminModulController::class, 'download'])->name('admin.modul.download');

    // Admin final draft
    Route::get('/admin/final-draft', [AdminFinalDraftController::class, 'index'])->name('admin.final-draft.index');
    Route::get('/admin/final-draft/report/pdf', [AdminFinalDraftController::class, 'reportPdf'])->name('admin.final-draft.report.pdf');
    Route::get('/admin/final-draft/report/excel', [AdminFinalDraftController::class, 'reportExcel'])->name('admin.final-draft.report.excel');
    Route::get('/admin/final-draft/{finalDraft}', [AdminFinalDraftController::class, 'show'])->name('admin.final-draft.show');
    Route::get('/admin/final-draft/{finalDraft}/download', [AdminFinalDraftController::class, 'download'])->name('admin.final-draft.download');

    // Admin publication
    Route::get('/admin/publication', [AdminPublicationController::class, 'index'])->name('admin.publication.index');
    Route::get('/admin/publication/{publicationModul}', [AdminPublicationController::class, 'show'])->name('admin.publication.show');
    Route::get('/admin/publication/{publicationModul}/download-final-modul', [AdminPublicationController::class, 'downloadFinalModul'])->name('admin.publication.download-final-modul');
    Route::get('/admin/publication/{publicationModul}/download-sertifikat-hki', [AdminPublicationController::class, 'downloadSertifikatHki'])->name('admin.publication.download-sertifikat-hki');
    Route::post('/admin/publication/{publicationModul}/validate', [AdminPublicationController::class, 'validate'])->name('admin.publication.validate');
    Route::post('/admin/publication/{publicationModul}/reset', [AdminPublicationController::class, 'resetValidation'])->name('admin.publication.reset');

    // Admin kelola user
    Route::get('/admin/user', [UserController::class, 'index'])->name('admin.user.index');
    Route::get('/admin/user/create', [UserController::class, 'create'])->name('admin.user.create');
    Route::post('/admin/user', [UserController::class, 'store'])->name('admin.user.store');
    Route::get('/admin/user/{user}/edit', [UserController::class, 'edit'])->name('admin.user.edit');
    Route::put('/admin/user/{user}', [UserController::class, 'update'])->name('admin.user.update');
    Route::delete('/admin/user/{user}', [UserController::class, 'destroy'])->name('admin.user.destroy');
});

// Guest/public routes: pendaftaran penyusun modul
Route::get('/penyusun/daftar', [PenyusunApplicationController::class, 'create'])->name('penyusun.apply.create');
Route::post('/penyusun/daftar', [PenyusunApplicationController::class, 'store'])->name('penyusun.apply.store');

// Guest/public routes: pendaftaran reviewer modul
Route::get('/reviewer/daftar', [\App\Http\Controllers\ReviewerApplicationController::class, 'create'])->name('reviewer.apply.create');
Route::post('/reviewer/daftar', [\App\Http\Controllers\ReviewerApplicationController::class, 'store'])->name('reviewer.apply.store');

// API route untuk mengambil mata kuliah berdasarkan jurusan
Route::get('/api/mata-kuliah/{jurusanId}', function ($jurusanId) {
    // Ambil ID mata kuliah yang sudah divalidasi (approved)
    $validatedMataKuliahIds = \App\Models\PenyusunApplication::where('status', 'approved')
        ->whereNotNull('mata_kuliah_id')
        ->pluck('mata_kuliah_id')
        ->toArray();
    
    // Ambil semua mata kuliah untuk jurusan tersebut dan urutkan berdasarkan semester
    $mataKuliah = \App\Models\MataKuliah::where('jurusan_id', $jurusanId)
        ->orderBy('semester', 'asc')
        ->get();
    
    // Tambahkan informasi ketersediaan dan jumlah pendaftar pending
    $mataKuliahWithAvailability = $mataKuliah->map(function ($mk) use ($validatedMataKuliahIds) {
        $mk->is_available = !in_array($mk->id, $validatedMataKuliahIds);
        // Hitung jumlah pengajuan dengan status pending untuk mata kuliah ini
        $mk->pending_count = \App\Models\PenyusunApplication::where('mata_kuliah_id', $mk->id)
            ->where('status', 'pending')
            ->count();
        return $mk;
    });
    
    return response()->json($mataKuliahWithAvailability);
});

// Debug route untuk test upload
Route::post('/debug/upload', function (\Illuminate\Http\Request $request) {
    try {
        $file = $request->file('file');
        if (!$file) {
            return response()->json(['error' => 'No file uploaded'], 400);
        }
        
        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType = $file->getMimeType();
        $size = $file->getSize();
        
        return response()->json([
            'extension' => $extension,
            'mime_type' => $mimeType,
            'size' => $size,
            'is_valid_extension' => in_array($extension, ['doc', 'docx']),
            'file_name' => $file->getClientOriginalName()
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

// Penyusun dashboard (hanya untuk user dengan is_penyusun=true)
Route::middleware(['auth', 'penyusun'])->group(function () {
    Route::get('/penyusun/dashboard', function () {
        $user = Auth::user();
        
        // Ambil aplikasi penyusun yang sudah disetujui
        $penyusunApplications = \App\Models\PenyusunApplication::where('email', $user->email)
            ->where('status', 'approved')
            ->with(['mataKuliah', 'moduls.tahapPenyusunan', 'finalDrafts', 'publicationModuls'])
            ->get();
        
        return view('penyusun.dashboard', compact('penyusunApplications'));
    })->name('penyusun.dashboard');

    // Penyusun modul
    Route::get('/penyusun/modul', [PenyusunModulController::class, 'index'])->name('penyusun.modul.index');
    Route::get('/penyusun/modul/create/{tahap}', [PenyusunModulController::class, 'create'])->name('penyusun.modul.create');
    Route::post('/penyusun/modul/{tahap}', [PenyusunModulController::class, 'store'])->name('penyusun.modul.store');
    Route::get('/penyusun/modul/{modul}', [PenyusunModulController::class, 'show'])->name('penyusun.modul.show');
    Route::get('/penyusun/modul/{modul}/download', [PenyusunModulController::class, 'download'])->name('penyusun.modul.download');
    Route::delete('/penyusun/modul/{modul}/delete', [PenyusunModulController::class, 'destroy'])->name('penyusun.modul.destroy');

    // Penyusun final draft
    Route::get('/penyusun/final-draft', [PenyusunFinalDraftController::class, 'index'])->name('penyusun.final-draft.index');
    Route::get('/penyusun/final-draft/create', [PenyusunFinalDraftController::class, 'create'])->name('penyusun.final-draft.create');
    Route::post('/penyusun/final-draft', [PenyusunFinalDraftController::class, 'store'])->name('penyusun.final-draft.store');
    Route::get('/penyusun/final-draft/{finalDraft}', [PenyusunFinalDraftController::class, 'show'])->name('penyusun.final-draft.show');
    Route::get('/penyusun/final-draft/{finalDraft}/download', [PenyusunFinalDraftController::class, 'download'])->name('penyusun.final-draft.download');

    // Penyusun publication
    Route::get('/penyusun/publication', [PenyusunPublicationController::class, 'index'])->name('penyusun.publication.index');
    Route::get('/penyusun/publication/create/{penyusunApplication}', [PenyusunPublicationController::class, 'create'])->name('penyusun.publication.create');
    Route::post('/penyusun/publication/{penyusunApplication}', [PenyusunPublicationController::class, 'store'])->name('penyusun.publication.store');
    Route::get('/penyusun/publication/{publicationModul}', [PenyusunPublicationController::class, 'show'])->name('penyusun.publication.show');
    Route::get('/penyusun/publication/{publicationModul}/download-final-modul', [PenyusunPublicationController::class, 'downloadFinalModul'])->name('penyusun.publication.download-final-modul');
    Route::get('/penyusun/publication/{publicationModul}/download-sertifikat-hki', [PenyusunPublicationController::class, 'downloadSertifikatHki'])->name('penyusun.publication.download-sertifikat-hki');
});

// LPM dashboard dan final draft (hanya untuk user dengan is_lpm=true)
Route::middleware(['auth', 'lpm'])->group(function () {
    Route::get('/lpm/dashboard', [LpmDashboardController::class, 'index'])->name('lpm.dashboard');
    Route::get('/lpm/monitoring', [LpmMonitoringController::class, 'index'])->name('lpm.monitoring');

    // LPM final draft
    Route::get('/lpm/final-draft', [LpmFinalDraftController::class, 'index'])->name('lpm.final-draft.index');
    Route::get('/lpm/final-draft/{finalDraft}', [LpmFinalDraftController::class, 'show'])->name('lpm.final-draft.show');
    Route::get('/lpm/final-draft/{finalDraft}/download', [LpmFinalDraftController::class, 'download'])->name('lpm.final-draft.download');
    Route::post('/lpm/final-draft/{finalDraft}/validate', [LpmFinalDraftController::class, 'validate'])->name('lpm.final-draft.validate');
});

// Reviewer routes (hanya untuk user dengan is_reviewer=true)
Route::middleware(['auth', 'reviewer'])->group(function () {
    Route::get('/reviewer/dashboard', [\App\Http\Controllers\Reviewer\DashboardController::class, 'index'])->name('reviewer.dashboard');
    Route::get('/reviewer/final-draft', [\App\Http\Controllers\Reviewer\FinalDraftController::class, 'index'])->name('reviewer.final-draft.index');
    Route::get('/reviewer/final-draft/{finalDraft}', [\App\Http\Controllers\Reviewer\FinalDraftController::class, 'show'])->name('reviewer.final-draft.show');
    Route::get('/reviewer/final-draft/{finalDraft}/download', [\App\Http\Controllers\Reviewer\FinalDraftController::class, 'download'])->name('reviewer.final-draft.download');
    Route::post('/reviewer/final-draft/{finalDraft}/validate', [\App\Http\Controllers\Reviewer\FinalDraftController::class, 'validateDraft'])->name('reviewer.final-draft.validate');
});

require __DIR__.'/auth.php';
