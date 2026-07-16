@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Kriteria Penilaian Reviewer
    </h2>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900">Daftar Aspek Penilaian</h3>
                <p class="text-sm text-gray-500 mt-1">Kelola aspek dan pertanyaan Likert (skala 1–5). Gunakan tombol panah untuk mengatur urutan.</p>
            </div>
            <a href="{{ route('admin.review-aspek.create') }}"
               class="w-full sm:w-auto inline-flex justify-center items-center bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                Tambah Aspek
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-4 sm:p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Urutan</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Aspek</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pertanyaan</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($aspeks as $index => $aspek)
                                <tr>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-gray-100 text-xs font-semibold text-gray-700">{{ $aspek->urutan }}</span>
                                            <div class="flex flex-col gap-0.5">
                                                <form action="{{ route('admin.review-aspek.move', $aspek) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="direction" value="up">
                                                    <button type="submit"
                                                            @disabled($index === 0)
                                                            class="p-0.5 rounded border border-gray-200 text-gray-600 hover:bg-gray-50 disabled:opacity-30 disabled:cursor-not-allowed"
                                                            title="Naikkan urutan">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.review-aspek.move', $aspek) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="direction" value="down">
                                                    <button type="submit"
                                                            @disabled($index === $aspeks->count() - 1)
                                                            class="p-0.5 rounded border border-gray-200 text-gray-600 hover:bg-gray-50 disabled:opacity-30 disabled:cursor-not-allowed"
                                                            title="Turunkan urutan">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 text-sm font-medium text-gray-900">{{ $aspek->nama }}</td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $aspek->pertanyaans_count }}</td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                        @if($aspek->is_active)
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('admin.review-aspek.show', $aspek) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-3 rounded text-xs">Kelola</a>
                                            <a href="{{ route('admin.review-aspek.edit', $aspek) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded text-xs">Edit</a>
                                            <form action="{{ route('admin.review-aspek.destroy', $aspek) }}" method="POST" class="inline" onsubmit="return confirm('Hapus atau nonaktifkan aspek ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded text-xs">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                                        Belum ada aspek penilaian. Silakan tambah aspek terlebih dahulu.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
