@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Kelola Pekerjaan Review Final Draft
    </h2>
@endsection

@section('content')
<div class="py-4 sm:py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-4 sm:mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg sm:text-xl font-medium text-gray-900">Queue Review Final Draft</h3>
                    <p class="text-sm text-gray-600 mt-1">Daftar modul yang ditugaskan kepada Anda untuk ditinjau kelayakannya.</p>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Filter & Pencarian -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4 sm:mb-6">
            <div class="p-4 sm:p-6">
                <form method="GET" action="{{ route('reviewer.final-draft.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Pencarian -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                placeholder="Cari judul modul atau kode draft (FD-00001)..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>
                        
                        <!-- Filter Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Filter Status</label>
                            <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="">Semua Status</option>
                                <option value="pending_review" {{ request('status') == 'pending_review' ? 'selected' : '' }}>Menunggu Reviewer</option>
                                <option value="approved_by_reviewer" {{ request('status') == 'approved_by_reviewer' ? 'selected' : '' }}>Lolos Reviewer</option>
                                <option value="rejected_by_reviewer" {{ request('status') == 'rejected_by_reviewer' ? 'selected' : '' }}>Ditolak Reviewer</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui LPM</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak LPM</option>
                            </select>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-end space-x-3">
                            <button type="submit" class="flex-1 px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                                Filter
                            </button>
                            <a href="{{ route('reviewer.final-draft.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 text-center">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Data -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-4 sm:p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Draft</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Modul</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Upload</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($finalDrafts as $index => $draft)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $finalDrafts->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        FD-{{ str_pad($draft->id, 5, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $draft->judul_modul }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $draft->mataKuliah->nama_mata_kuliah }}
                                        <div class="text-xs text-gray-400">Smtr: {{ $draft->mataKuliah->semester }} | SKS: {{ $draft->mataKuliah->sks }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $draft->uploaded_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($draft->status === 'pending_review')
                                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Menunggu Reviewer</span>
                                        @elseif($draft->status === 'approved_by_reviewer')
                                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Lolos Reviewer</span>
                                        @elseif($draft->status === 'rejected_by_reviewer')
                                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Ditolak Reviewer</span>
                                        @elseif($draft->status === 'approved')
                                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Disetujui LPM</span>
                                        @elseif($draft->status === 'rejected')
                                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Ditolak LPM</span>
                                        @else
                                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $draft->status }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('reviewer.final-draft.show', $draft) }}" class="text-indigo-600 hover:text-indigo-900">Review & Tindakan</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-sm text-gray-500">
                                        Tidak ada final draft yang sesuai dengan kriteria filter.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $finalDrafts->links() }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
