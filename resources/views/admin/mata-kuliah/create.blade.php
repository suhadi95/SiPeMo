@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Mata Kuliah
        </h2>
        <a href="{{ route('admin.mata-kuliah.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form action="{{ route('admin.mata-kuliah.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="jurusan_id" class="block text-sm font-medium text-gray-700">Jurusan</label>
                            <select name="jurusan_id" id="jurusan_id" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 @error('jurusan_id') border-red-500 @enderror" 
                                    required>
                                <option value="">Pilih Jurusan</option>
                                @foreach($jurusan as $j)
                                    <option value="{{ $j->id }}" {{ old('jurusan_id') == $j->id ? 'selected' : '' }}>
                                        {{ $j->nama_jurusan }} ({{ $j->kode_jurusan }})
                                    </option>
                                @endforeach
                            </select>
                            @error('jurusan_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="kode_mata_kuliah" class="block text-sm font-medium text-gray-700">Kode Mata Kuliah</label>
                            <input type="text" name="kode_mata_kuliah" id="kode_mata_kuliah" value="{{ old('kode_mata_kuliah') }}" 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 @error('kode_mata_kuliah') border-red-500 @enderror" 
                                   required>
                            @error('kode_mata_kuliah')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nama_mata_kuliah" class="block text-sm font-medium text-gray-700">Nama Mata Kuliah</label>
                            <input type="text" name="nama_mata_kuliah" id="nama_mata_kuliah" value="{{ old('nama_mata_kuliah') }}" 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 @error('nama_mata_kuliah') border-red-500 @enderror" 
                                   required>
                            @error('nama_mata_kuliah')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="semester" class="block text-sm font-medium text-gray-700">Semester</label>
                                <select name="semester" id="semester" 
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 @error('semester') border-red-500 @enderror" 
                                        required>
                                    <option value="">Pilih Semester</option>
                                    @for($i = 1; $i <= 8; $i++)
                                        <option value="{{ $i }}" {{ old('semester') == $i ? 'selected' : '' }}>
                                            Semester {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                                @error('semester')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="sks" class="block text-sm font-medium text-gray-700">SKS</label>
                                <select name="sks" id="sks" 
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 @error('sks') border-red-500 @enderror" 
                                        required>
                                    <option value="">Pilih SKS</option>
                                    <option value="2" {{ old('sks') == '2' ? 'selected' : '' }}>2 SKS</option>
                                    <option value="3" {{ old('sks') == '3' ? 'selected' : '' }}>3 SKS</option>
                                </select>
                                @error('sks')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" rows="3" 
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('admin.mata-kuliah.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Batal
                        </a>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
