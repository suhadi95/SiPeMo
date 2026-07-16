@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Aspek: {{ $reviewAspek->nama }}
    </h2>
@endsection

@section('content')
<div class="py-6" x-data="{ editingId: null }">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <p class="text-sm text-gray-500">Kelola pertanyaan Likert pada aspek ini. Atur urutan dengan tombol panah.</p>
            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                <a href="{{ route('admin.review-aspek.edit', $reviewAspek) }}"
                   class="inline-flex justify-center bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded text-sm">Edit Aspek</a>
                <a href="{{ route('admin.review-aspek.index') }}"
                   class="inline-flex justify-center bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm">Kembali</a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-4 sm:p-6">
                <dl class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                    <div>
                        <dt class="text-gray-500">Nama Aspek</dt>
                        <dd class="font-medium text-gray-900">{{ $reviewAspek->nama }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Urutan</dt>
                        <dd class="font-medium text-gray-900">{{ $reviewAspek->urutan }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Status</dt>
                        <dd>
                            @if($reviewAspek->is_active)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Nonaktif</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-4 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Pertanyaan (Likert 1–5)</h3>
                <form action="{{ route('admin.review-aspek.pertanyaan.store', $reviewAspek) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="teks_pertanyaan" class="block text-sm font-medium text-gray-700">Teks Pertanyaan</label>
                        <textarea name="teks_pertanyaan" id="teks_pertanyaan" rows="2" required
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 @error('teks_pertanyaan') border-red-500 @enderror"
                                  placeholder="Contoh: Materi sesuai dengan capaian pembelajaran mata kuliah">{{ old('teks_pertanyaan') }}</textarea>
                        @error('teks_pertanyaan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex items-center">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" id="is_active_new" value="1" checked
                                   class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500">
                            <label for="is_active_new" class="ml-2 text-sm text-gray-700">Aktif</label>
                        </div>
                        <button type="submit" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Tambah Pertanyaan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-4 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Pertanyaan</h3>
                @forelse($reviewAspek->pertanyaans as $index => $pertanyaan)
                    <div class="border rounded-lg p-4 mb-3" x-show="editingId !== {{ $pertanyaan->id }}">
                        <div class="flex items-start gap-3">
                            <div class="flex items-center gap-2 shrink-0 pt-0.5">
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-gray-100 text-xs font-semibold text-gray-700">{{ $pertanyaan->urutan }}</span>
                                <div class="flex flex-col gap-0.5">
                                    <form action="{{ route('admin.review-aspek.pertanyaan.move', [$reviewAspek, $pertanyaan]) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="direction" value="up">
                                        <button type="submit"
                                                @disabled($index === 0)
                                                class="p-0.5 rounded border border-gray-200 text-gray-600 hover:bg-gray-50 disabled:opacity-30 disabled:cursor-not-allowed"
                                                title="Naikkan urutan">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.review-aspek.pertanyaan.move', [$reviewAspek, $pertanyaan]) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="direction" value="down">
                                        <button type="submit"
                                                @disabled($index === $reviewAspek->pertanyaans->count() - 1)
                                                class="p-0.5 rounded border border-gray-200 text-gray-600 hover:bg-gray-50 disabled:opacity-30 disabled:cursor-not-allowed"
                                                title="Turunkan urutan">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    @if($pertanyaan->is_active)
                                        <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Nonaktif</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-900">{{ $pertanyaan->teks_pertanyaan }}</p>
                            </div>

                            <div class="flex flex-wrap gap-2 shrink-0">
                                <button type="button" @click="editingId = {{ $pertanyaan->id }}"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded text-xs">Edit</button>
                                <form action="{{ route('admin.review-aspek.pertanyaan.destroy', [$reviewAspek, $pertanyaan]) }}" method="POST"
                                      onsubmit="return confirm('Hapus atau nonaktifkan pertanyaan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded text-xs">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="border border-yellow-300 bg-yellow-50 rounded-lg p-4 mb-3" x-show="editingId === {{ $pertanyaan->id }}" x-cloak>
                        <form action="{{ route('admin.review-aspek.pertanyaan.update', [$reviewAspek, $pertanyaan]) }}" method="POST" class="space-y-3">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Teks Pertanyaan</label>
                                <textarea name="teks_pertanyaan" rows="2" required
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">{{ old('teks_pertanyaan', $pertanyaan->teks_pertanyaan) }}</textarea>
                            </div>
                            <div class="flex items-center">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" {{ $pertanyaan->is_active ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500">
                                <label class="ml-2 text-sm text-gray-700">Aktif</label>
                            </div>
                            <div class="flex justify-end gap-2">
                                <button type="button" @click="editingId = null" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-3 rounded text-xs">Batal</button>
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-xs">Simpan</button>
                            </div>
                        </form>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Belum ada pertanyaan pada aspek ini.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
