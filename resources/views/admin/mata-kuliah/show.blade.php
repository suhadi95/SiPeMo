@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Mata Kuliah: {{ $mataKuliah->nama_mata_kuliah }}
        </h2>
        <div class="flex space-x-2">
            <a href="{{ route('admin.mata-kuliah.edit', $mataKuliah) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                Edit
            </a>
            <a href="{{ route('admin.mata-kuliah.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
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
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Mata Kuliah</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Kode Mata Kuliah</dt>
                                <dd class="text-sm text-gray-900">{{ $mataKuliah->kode_mata_kuliah }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nama Mata Kuliah</dt>
                                <dd class="text-sm text-gray-900">{{ $mataKuliah->nama_mata_kuliah }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Jurusan</dt>
                                <dd class="text-sm text-gray-900">{{ $mataKuliah->jurusan->nama_jurusan }} ({{ $mataKuliah->jurusan->kode_jurusan }})</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Semester</dt>
                                <dd class="text-sm text-gray-900">Semester {{ $mataKuliah->semester }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">SKS</dt>
                                <dd class="text-sm text-gray-900">{{ $mataKuliah->sks }} SKS</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Deskripsi</dt>
                                <dd class="text-sm text-gray-900">{{ $mataKuliah->deskripsi ?: 'Tidak ada deskripsi' }}</dd>
                            </div>
                        </dl>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Jurusan</h3>
                        <div class="border rounded-lg p-4">
                            <h4 class="font-medium text-gray-900">{{ $mataKuliah->jurusan->nama_jurusan }}</h4>
                            <p class="text-sm text-gray-600 mt-1">{{ $mataKuliah->jurusan->kode_jurusan }}</p>
                            @if($mataKuliah->jurusan->deskripsi)
                                <p class="text-sm text-gray-700 mt-2">{{ $mataKuliah->jurusan->deskripsi }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
