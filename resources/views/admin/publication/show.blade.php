@extends('layouts.app')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    Validasi Publikasi Modul
</h2>
@endsection

@section('content')
<div class="py-4 sm:py-6">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Status Card -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 truncate">{{ $publicationModul->judul_modul }}</h3>
                        <p class="text-xs sm:text-sm text-gray-600 mt-1 break-words">{{ $publicationModul->penyusunApplication->user->name }} - {{ $publicationModul->penyusunApplication->mataKuliah->nama_mata_kuliah }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        @if($publicationModul->status == 'approved')
                        <span class="inline-flex px-2 sm:px-3 py-1 text-xs sm:text-sm font-semibold rounded-full bg-green-100 text-green-800">
                            Disetujui
                        </span>
                        @elseif($publicationModul->status == 'rejected')
                        <span class="inline-flex px-2 sm:px-3 py-1 text-xs sm:text-sm font-semibold rounded-full bg-red-100 text-red-800">
                            Ditolak
                        </span>
                        @else
                        <span class="inline-flex px-2 sm:px-3 py-1 text-xs sm:text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Menunggu Validasi
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            <!-- Informasi Modul -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6">
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-3 sm:mb-4">Informasi Modul</h3>

                    <dl class="space-y-2 sm:space-y-3">
                        <div>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500">Judul Modul</dt>
                            <dd class="text-xs sm:text-sm text-gray-900 break-words">{{ $publicationModul->judul_modul }}</dd>
                        </div>

                        @if($publicationModul->deskripsi_modul)
                        <div>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500">Deskripsi</dt>
                            <dd class="text-xs sm:text-sm text-gray-900 break-words">{{ $publicationModul->deskripsi_modul }}</dd>
                        </div>
                        @endif

                        <div>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500">Penyusun</dt>
                            <dd class="text-xs sm:text-sm text-gray-900 break-words">{{ $publicationModul->penyusunApplication->user->name }}</dd>
                        </div>

                        <div>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500">Email</dt>
                            <dd class="text-xs sm:text-sm text-gray-900 break-all">{{ $publicationModul->penyusunApplication->user->email }}</dd>
                        </div>

                        <div>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500">Mata Kuliah</dt>
                            <dd class="text-xs sm:text-sm text-gray-900 break-words">{{ $publicationModul->penyusunApplication->mataKuliah->nama_mata_kuliah }}</dd>
                        </div>

                        <div>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500">Jurusan</dt>
                            <dd class="text-xs sm:text-sm text-gray-900 break-words">{{ $publicationModul->penyusunApplication->mataKuliah->jurusan->nama_jurusan }}</dd>
                        </div>

                        <div>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500">Tanggal Upload</dt>
                            <dd class="text-xs sm:text-sm text-gray-900">{{ $publicationModul->uploaded_at->format('d M Y, H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>


            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Informasi Pribadi -->
                <div class="p-4 sm:p-6">
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-3 sm:mb-4">Informasi Pribadi</h3>

                    <dl class="space-y-2 sm:space-y-3">
                        @if($publicationModul->nik)
                        <div>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500">NIK</dt>
                            <dd class="text-xs sm:text-sm text-gray-900 font-mono break-all">{{ $publicationModul->nik }}</dd>
                        </div>
                        @endif

                        @if($publicationModul->npwp)
                        <div>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500">NPWP</dt>
                            <dd class="text-xs sm:text-sm text-gray-900 font-mono break-all">{{ $publicationModul->npwp }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                <!-- Informasi Rekening -->
                <div class="p-4 sm:p-6">
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-3 sm:mb-4">Informasi Rekening</h3>

                    <dl class="space-y-2 sm:space-y-3">
                        <div>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500">Nama Bank</dt>
                            <dd class="text-xs sm:text-sm text-gray-900 break-words">{{ $publicationModul->nama_bank }}</dd>
                        </div>

                        <div>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500">Nomor Rekening</dt>
                            <dd class="text-xs sm:text-sm text-gray-900 font-mono break-all">{{ $publicationModul->nomor_rekening }}</dd>
                        </div>

                        <div>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500">Nama Pemilik Rekening</dt>
                            <dd class="text-xs sm:text-sm text-gray-900 break-words">{{ $publicationModul->nama_pemilik_rekening }}</dd>
                        </div>
                    </dl>

                    <!-- Transfer Info -->
                    <div class="mt-4 sm:mt-6 p-3 sm:p-4 bg-blue-50 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="text-xs sm:text-sm text-blue-800">
                                <p class="font-medium">Transfer dilakukan di luar sistem</p>
                                <p class="text-xs mt-1">Pastikan informasi rekening sudah benar sebelum melakukan transfer</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mt-4 sm:mt-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

            </div>
        </div>

        <!-- File Downloads -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4 sm:mt-6">
            <div class="p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-3 sm:mb-4">File Upload</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                    <div class="border border-gray-200 rounded-lg p-3 sm:p-4">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xs sm:text-sm font-medium text-gray-900">Final Modul</h4>
                                <p class="text-xs sm:text-sm text-gray-500 truncate">{{ $publicationModul->final_modul_file_name }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="{{ route('admin.publication.download-final-modul', $publicationModul) }}"
                                    class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 sm:px-4 rounded text-xs sm:text-sm w-full sm:w-auto text-center">
                                    Download
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-3 sm:p-4">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xs sm:text-sm font-medium text-gray-900">Sertifikat HKI</h4>
                                <p class="text-xs sm:text-sm text-gray-500 truncate">{{ $publicationModul->sertifikat_hki_file_name }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="{{ route('admin.publication.download-sertifikat-hki', $publicationModul) }}"
                                    class="inline-block bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-3 sm:px-4 rounded text-xs sm:text-sm w-full sm:w-auto text-center">
                                    Download
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Validasi Form -->
        @if($publicationModul->status == 'pending')
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4 sm:mt-6">
            <div class="p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-3 sm:mb-4">Validasi Publikasi</h3>

                <form method="POST" action="{{ route('admin.publication.validate', $publicationModul) }}">
                    @csrf

                    <div class="space-y-3 sm:space-y-4">
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Status Validasi</label>
                            <div class="space-y-2">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="status" value="approved" class="form-radio text-green-600 w-4 h-4">
                                    <span class="ml-2 text-xs sm:text-sm text-gray-700">Setujui (Transfer akan dilakukan)</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="status" value="rejected" class="form-radio text-red-600 w-4 h-4">
                                    <span class="ml-2 text-xs sm:text-sm text-gray-700">Tolak</span>
                                </label>
                            </div>
                            @error('status')
                            <p class="mt-2 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="catatan_admin" class="block text-xs sm:text-sm font-medium text-gray-700">Catatan Admin</label>
                            <textarea name="catatan_admin" id="catatan_admin" rows="3"
                                placeholder="Berikan catatan jika diperlukan..."
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs sm:text-sm">{{ old('catatan_admin') }}</textarea>
                            @error('catatan_admin')
                            <p class="mt-2 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end space-y-2 sm:space-y-0 sm:space-x-3">
                            <a href="{{ route('admin.publication.index') }}"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded text-xs sm:text-sm text-center">
                                Batal
                            </a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-xs sm:text-sm">
                                Validasi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @else
        <!-- Status Validasi -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4 sm:mt-6">
            <div class="p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-3 sm:mb-4">Status Validasi</h3>

                <dl class="space-y-2 sm:space-y-3">
                    <div>
                        <dt class="text-xs sm:text-sm font-medium text-gray-500">Status</dt>
                        <dd class="text-xs sm:text-sm text-gray-900">
                            @if($publicationModul->status == 'approved')
                            <span class="text-green-600 font-medium">Disetujui</span>
                            @elseif($publicationModul->status == 'rejected')
                            <span class="text-red-600 font-medium">Ditolak</span>
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs sm:text-sm font-medium text-gray-500">Divalidasi Oleh</dt>
                        <dd class="text-xs sm:text-sm text-gray-900 break-words">{{ $publicationModul->validator->name ?? 'Admin' }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs sm:text-sm font-medium text-gray-500">Tanggal Validasi</dt>
                        <dd class="text-xs sm:text-sm text-gray-900">{{ $publicationModul->validated_at->format('d M Y, H:i') }}</dd>
                    </div>

                    @if($publicationModul->catatan_admin)
                    <div>
                        <dt class="text-xs sm:text-sm font-medium text-gray-500">Catatan Admin</dt>
                        <dd class="text-xs sm:text-sm text-gray-900 break-words">{{ $publicationModul->catatan_admin }}</dd>
                    </div>
                    @endif
                </dl>

                <!-- Reset Button -->
                <div class="mt-4 sm:mt-6">
                    <form method="POST" action="{{ route('admin.publication.reset', $publicationModul) }}"
                        onsubmit="return confirm('Apakah Anda yakin ingin mereset validasi publikasi ini?')">
                        @csrf
                        <button type="submit"
                            class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded text-xs sm:text-sm w-full sm:w-auto">
                            Reset Validasi
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex items-center justify-end mt-4 sm:mt-6">
            <a href="{{ route('admin.publication.index') }}"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded text-xs sm:text-sm">
                Kembali
            </a>
        </div>
    </div>
</div>
@endsection