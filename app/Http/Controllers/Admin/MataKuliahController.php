<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\MataKuliah;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MataKuliah::with('jurusan');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_mata_kuliah', 'like', "%{$search}%")
                  ->orWhere('kode_mata_kuliah', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jurusan_id')) {
            $query->where('jurusan_id', $request->jurusan_id);
        }

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        $perPage = (int) $request->get('per_page', 15);
        $allowedPerPage = [15, 30, 60, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 15;
        }

        $mataKuliah = $query->orderBy('semester', 'asc')
            ->orderBy('nama_mata_kuliah')
            ->paginate($perPage)
            ->withQueryString();

        $jurusans = Jurusan::orderBy('nama_jurusan')->get();

        return view('admin.mata-kuliah.index', compact('mataKuliah', 'jurusans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jurusan = Jurusan::all();
        return view('admin.mata-kuliah.create', compact('jurusan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jurusan_id' => 'required|exists:jurusans,id',
            'nama_mata_kuliah' => 'required|string|max:255',
            'kode_mata_kuliah' => 'required|string|max:10|unique:mata_kuliahs',
            'semester' => 'required|integer|min:1|max:8',
            'sks' => 'required|integer|in:2,3',
            'deskripsi' => 'nullable|string',
        ]);

        MataKuliah::create($request->all());

        return redirect()->route('admin.mata-kuliah.index')
            ->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MataKuliah $mataKuliah)
    {
        $mataKuliah->load('jurusan');
        return view('admin.mata-kuliah.show', compact('mataKuliah'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MataKuliah $mataKuliah)
    {
        $jurusan = Jurusan::all();
        return view('admin.mata-kuliah.edit', compact('mataKuliah', 'jurusan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MataKuliah $mataKuliah)
    {
        $request->validate([
            'jurusan_id' => 'required|exists:jurusans,id',
            'nama_mata_kuliah' => 'required|string|max:255',
            'kode_mata_kuliah' => 'required|string|max:10|unique:mata_kuliahs,kode_mata_kuliah,' . $mataKuliah->id,
            'semester' => 'required|integer|min:1|max:8',
            'sks' => 'required|integer|in:2,3',
            'deskripsi' => 'nullable|string',
        ]);

        $mataKuliah->update($request->all());

        return redirect()->route('admin.mata-kuliah.index')
            ->with('success', 'Mata kuliah berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MataKuliah $mataKuliah)
    {
        // Dapatkan semua PenyusunApplication yang mata_kuliah_id-nya sama dengan $mataKuliah->id
        $applications = \App\Models\PenyusunApplication::where('mata_kuliah_id', $mataKuliah->id)->get();

        foreach ($applications as $application) {
            // Hapus akun penyusun jika ada (berdasarkan email)
            if ($application->email) {
                $user = \App\Models\User::where('email', $application->email)->first();
                if ($user) {
                    $user->delete();
                }
            }
            
            // Hapus application (cascade deletes associated moduls, final_drafts, dll)
            $application->delete();
        }

        // Hapus mata kuliah
        $mataKuliah->delete();

        return redirect()->route('admin.mata-kuliah.index')
            ->with('success', 'Mata kuliah beserta proses penyusunan dan akun penyusunnya berhasil dihapus.');
    }
}
