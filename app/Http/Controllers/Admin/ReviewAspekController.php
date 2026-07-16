<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReviewAspek;
use App\Models\ReviewPertanyaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewAspekController extends Controller
{
    public function index()
    {
        $aspeks = ReviewAspek::withCount('pertanyaans')
            ->ordered()
            ->get();

        return view('admin.review-aspek.index', compact('aspeks'));
    }

    public function create()
    {
        return view('admin.review-aspek.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $maxUrutan = ReviewAspek::max('urutan') ?? 0;

        ReviewAspek::create([
            'nama' => $validated['nama'],
            'urutan' => $maxUrutan + 1,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.review-aspek.index')
            ->with('success', 'Aspek penilaian berhasil ditambahkan.');
    }

    public function show(ReviewAspek $reviewAspek)
    {
        $reviewAspek->load(['pertanyaans' => fn ($q) => $q->ordered()]);

        return view('admin.review-aspek.show', compact('reviewAspek'));
    }

    public function edit(ReviewAspek $reviewAspek)
    {
        return view('admin.review-aspek.edit', compact('reviewAspek'));
    }

    public function update(Request $request, ReviewAspek $reviewAspek)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $reviewAspek->update([
            'nama' => $validated['nama'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.review-aspek.index')
            ->with('success', 'Aspek penilaian berhasil diperbarui.');
    }

    public function destroy(ReviewAspek $reviewAspek)
    {
        $hasUsedPertanyaan = $reviewAspek->pertanyaans()
            ->whereHas('answers')
            ->exists();

        if ($hasUsedPertanyaan) {
            $reviewAspek->update(['is_active' => false]);
            $reviewAspek->pertanyaans()->update(['is_active' => false]);

            return redirect()->route('admin.review-aspek.index')
                ->with('success', 'Aspek sudah pernah dipakai dalam penilaian. Aspek dan pertanyaannya dinonaktifkan.');
        }

        $reviewAspek->delete();
        $this->renumberAspeks();

        return redirect()->route('admin.review-aspek.index')
            ->with('success', 'Aspek penilaian berhasil dihapus.');
    }

    public function move(Request $request, ReviewAspek $reviewAspek)
    {
        $request->validate([
            'direction' => 'required|in:up,down',
        ]);

        $this->swapOrder(
            ReviewAspek::ordered()->get(),
            $reviewAspek->id,
            $request->direction,
            ReviewAspek::class
        );

        return redirect()->route('admin.review-aspek.index')
            ->with('success', 'Urutan aspek berhasil diperbarui.');
    }

    public function storePertanyaan(Request $request, ReviewAspek $reviewAspek)
    {
        $validated = $request->validate([
            'teks_pertanyaan' => 'required|string|max:2000',
            'is_active' => 'nullable|boolean',
        ]);

        $maxUrutan = $reviewAspek->pertanyaans()->max('urutan') ?? 0;

        $reviewAspek->pertanyaans()->create([
            'teks_pertanyaan' => $validated['teks_pertanyaan'],
            'urutan' => $maxUrutan + 1,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.review-aspek.show', $reviewAspek)
            ->with('success', 'Pertanyaan berhasil ditambahkan.');
    }

    public function updatePertanyaan(Request $request, ReviewAspek $reviewAspek, ReviewPertanyaan $pertanyaan)
    {
        if ($pertanyaan->review_aspek_id !== $reviewAspek->id) {
            abort(404);
        }

        $validated = $request->validate([
            'teks_pertanyaan' => 'required|string|max:2000',
            'is_active' => 'nullable|boolean',
        ]);

        $pertanyaan->update([
            'teks_pertanyaan' => $validated['teks_pertanyaan'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.review-aspek.show', $reviewAspek)
            ->with('success', 'Pertanyaan berhasil diperbarui.');
    }

    public function destroyPertanyaan(ReviewAspek $reviewAspek, ReviewPertanyaan $pertanyaan)
    {
        if ($pertanyaan->review_aspek_id !== $reviewAspek->id) {
            abort(404);
        }

        if ($pertanyaan->hasBeenUsed()) {
            $pertanyaan->update(['is_active' => false]);

            return redirect()->route('admin.review-aspek.show', $reviewAspek)
                ->with('success', 'Pertanyaan sudah pernah dipakai dalam penilaian. Pertanyaan dinonaktifkan.');
        }

        $pertanyaan->delete();
        $this->renumberPertanyaans($reviewAspek);

        return redirect()->route('admin.review-aspek.show', $reviewAspek)
            ->with('success', 'Pertanyaan berhasil dihapus.');
    }

    public function movePertanyaan(Request $request, ReviewAspek $reviewAspek, ReviewPertanyaan $pertanyaan)
    {
        if ($pertanyaan->review_aspek_id !== $reviewAspek->id) {
            abort(404);
        }

        $request->validate([
            'direction' => 'required|in:up,down',
        ]);

        $this->swapOrder(
            $reviewAspek->pertanyaans()->ordered()->get(),
            $pertanyaan->id,
            $request->direction,
            ReviewPertanyaan::class
        );

        return redirect()->route('admin.review-aspek.show', $reviewAspek)
            ->with('success', 'Urutan pertanyaan berhasil diperbarui.');
    }

    private function swapOrder($items, int $id, string $direction, string $modelClass): void
    {
        $ids = $items->pluck('id')->values()->all();
        $index = array_search($id, $ids, true);

        if ($index === false) {
            return;
        }

        $swapWith = $direction === 'up' ? $index - 1 : $index + 1;
        if ($swapWith < 0 || $swapWith >= count($ids)) {
            return;
        }

        [$ids[$index], $ids[$swapWith]] = [$ids[$swapWith], $ids[$index]];

        DB::transaction(function () use ($ids, $modelClass) {
            foreach ($ids as $i => $itemId) {
                $modelClass::whereKey($itemId)->update(['urutan' => $i + 1]);
            }
        });
    }

    private function renumberAspeks(): void
    {
        $ids = ReviewAspek::ordered()->pluck('id');
        foreach ($ids as $i => $id) {
            ReviewAspek::whereKey($id)->update(['urutan' => $i + 1]);
        }
    }

    private function renumberPertanyaans(ReviewAspek $reviewAspek): void
    {
        $ids = $reviewAspek->pertanyaans()->ordered()->pluck('id');
        foreach ($ids as $i => $id) {
            ReviewPertanyaan::whereKey($id)->update(['urutan' => $i + 1]);
        }
    }
}
