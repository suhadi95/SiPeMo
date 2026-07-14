@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Jurusan: {{ $jurusan->nama_jurusan }}
        </h2>
        <div class="flex space-x-2">
            <a href="{{ route('admin.jurusan.edit', $jurusan) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                Edit
            </a>
            <a href="{{ route('admin.jurusan.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Jurusan</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Kode Jurusan</dt>
                                <dd class="text-sm text-gray-900">{{ $jurusan->kode_jurusan }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nama Jurusan</dt>
                                <dd class="text-sm text-gray-900">{{ $jurusan->nama_jurusan }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Deskripsi</dt>
                                <dd class="text-sm text-gray-900">{{ $jurusan->deskripsi ?: 'Tidak ada deskripsi' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Jumlah Mata Kuliah</dt>
                                <dd class="text-sm text-gray-900">{{ $jurusan->mataKuliah->count() }}</dd>
                            </div>
                        </dl>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Mata Kuliah</h3>
                        @if($jurusan->mataKuliah->count() > 0)
                            <div class="space-y-2">
                                @foreach($jurusan->mataKuliah as $mk)
                                    <div class="border rounded-lg p-3">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="font-medium text-gray-900">{{ $mk->nama_mata_kuliah }}</h4>
                                                <p class="text-sm text-gray-600">{{ $mk->kode_mata_kuliah }} - Semester {{ $mk->semester }} - {{ $mk->sks }} SKS</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">Belum ada mata kuliah untuk jurusan ini.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
