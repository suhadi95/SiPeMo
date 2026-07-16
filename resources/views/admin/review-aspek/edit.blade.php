@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Aspek Penilaian</h2>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-end mb-4">
            <a href="{{ route('admin.review-aspek.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm">Kembali</a>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-4 sm:p-6">
                <form action="{{ route('admin.review-aspek.update', $reviewAspek) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-6">
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama Aspek</label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama', $reviewAspek->nama) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 @error('nama') border-red-500 @enderror"
                                   required>
                            @error('nama')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <p class="text-xs text-gray-500">Urutan saat ini: <strong>{{ $reviewAspek->urutan }}</strong>. Ubah urutan lewat tombol panah di halaman daftar aspek.</p>
                        <div class="flex items-center">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $reviewAspek->is_active) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500">
                            <label for="is_active" class="ml-2 text-sm text-gray-700">Aktif (tampil di form reviewer)</label>
                        </div>
                    </div>
                    <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-2">
                        <a href="{{ route('admin.review-aspek.index') }}" class="inline-flex justify-center bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Batal</a>
                        <button type="submit" class="inline-flex justify-center bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
