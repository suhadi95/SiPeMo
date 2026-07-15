<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Jurusan::withCount('mataKuliah');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_jurusan', 'like', "%{$search}%")
                  ->orWhere('kode_jurusan', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->get('per_page', 15);
        $allowedPerPage = [15, 30, 60, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 15;
        }

        $jurusan = $query->orderBy('nama_jurusan')->paginate($perPage)->withQueryString();

        return view('admin.jurusan.index', compact('jurusan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.jurusan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_jurusan' => 'required|string|max:255',
            'kode_jurusan' => 'required|string|max:10|unique:jurusans',
            'deskripsi' => 'nullable|string',
        ]);

        Jurusan::create($request->all());

        return redirect()->route('admin.jurusan.index')
            ->with('success', 'Jurusan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Jurusan $jurusan)
    {
        $jurusan->load('mataKuliah');
        return view('admin.jurusan.show', compact('jurusan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jurusan $jurusan)
    {
        return view('admin.jurusan.edit', compact('jurusan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jurusan $jurusan)
    {
        $request->validate([
            'nama_jurusan' => 'required|string|max:255',
            'kode_jurusan' => 'required|string|max:10|unique:jurusans,kode_jurusan,' . $jurusan->id,
            'deskripsi' => 'nullable|string',
        ]);

        $jurusan->update($request->all());

        return redirect()->route('admin.jurusan.index')
            ->with('success', 'Jurusan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jurusan $jurusan)
    {
        $jurusan->delete();

        return redirect()->route('admin.jurusan.index')
            ->with('success', 'Jurusan berhasil dihapus.');
    }
}
