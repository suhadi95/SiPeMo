<x-guest-with-header-footer>
    <div class="w-full max-w-4xl mx-auto py-8">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-12 h-12 md:w-16 md:h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full mb-4">
                <svg class="w-6 h-6 md:w-8 md:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Pendaftaran Reviewer Modul</h1>
            <p class="text-sm md:text-base text-gray-600">Bergabunglah sebagai Reviewer untuk memvalidasi kualitas modul ajar</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <form method="POST" action="{{ route('reviewer.apply.store') }}" enctype="multipart/form-data" class="p-6 md:p-8" novalidate>
                @csrf

                <!-- Section: Informasi Pribadi -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 -mx-6 md:-mx-8 -mt-6 md:-mt-8 mb-6 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Informasi Pribadi & Akademik
                    </h2>
                    <p class="text-xs text-gray-600 mt-1">Lengkapi data diri Anda di bawah ini</p>
                </div>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Lengkap -->
                        <div class="space-y-2">
                            <x-input-label for="nama_reviewer" value="Nama Lengkap" class="text-sm font-medium text-gray-700" />
                            <div class="relative">
                                <x-text-input id="nama_reviewer" name="nama_reviewer" type="text" class="w-full pl-10" :value="old('nama_reviewer')" placeholder="Nama Lengkap Beserta Gelar" required />
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('nama_reviewer')" class="mt-1" />
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <x-input-label for="email" value="Email" class="text-sm font-medium text-gray-700" />
                            <div class="relative">
                                <x-text-input id="email" name="email" type="email" class="w-full pl-10" :value="old('email')" placeholder="nama@domain.com" required />
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-1" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- WhatsApp -->
                        <div class="space-y-2">
                            <x-input-label for="no_wa" value="Nomor WhatsApp" class="text-sm font-medium text-gray-700" />
                            <div class="relative">
                                <x-text-input id="no_wa" name="no_wa" type="text" class="w-full pl-10" :value="old('no_wa')" placeholder="08xxxxxxxxxx" inputmode="numeric" pattern="[0-9]*" oninput="this.value=this.value.replace(/[^0-9]/g,'')" required />
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('no_wa')" class="mt-1" />
                        </div>

                        <!-- NIDN -->
                        <div class="space-y-2">
                            <x-input-label for="nidn" value="NIDN" class="text-sm font-medium text-gray-700" />
                            <div class="relative">
                                <x-text-input id="nidn" name="nidn" type="text" class="w-full pl-10" :value="old('nidn')" placeholder="Nomor Induk Dosen Nasional" inputmode="numeric" pattern="[0-9]*" oninput="this.value=this.value.replace(/[^0-9]/g,'')" required />
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('nidn')" class="mt-1" />
                        </div>
                    </div>

                    <!-- NIP -->
                    <div class="space-y-2">
                        <x-input-label for="nip" value="NIP (Wajib bagi ASN / Pegawai Negeri)" class="text-sm font-medium text-gray-700" />
                        <div class="relative">
                            <x-text-input id="nip" name="nip" type="text" class="w-full pl-10" :value="old('nip')" placeholder="Opsional (Kosongkan jika bukan ASN)" inputmode="numeric" pattern="[0-9]*" oninput="this.value=this.value.replace(/[^0-9]/g,'')" />
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                </svg>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('nip')" class="mt-1" />
                    </div>

                    <!-- Upload Sertifikasi -->
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                        <x-input-label for="sertifikasi" value="Upload Dokumen Sertifikasi / Keahlian" class="text-base font-semibold text-gray-900 mb-2" />
                        <p class="text-xs text-gray-500 mb-4">Unggah sertifikat pendukung keahlian review modul (format PDF, JPG, JPEG, atau PNG, ukuran maksimal 5MB)</p>
                        
                        <div class="relative border-2 border-dashed border-gray-300 hover:border-blue-500 rounded-lg p-4 transition-colors">
                            <input id="sertifikasi" name="sertifikasi" type="file" accept=".pdf,.jpg,.jpeg,.png" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required onchange="updateFileName(this)" />
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="mt-1 text-sm text-gray-600" id="file-upload-text">Klik untuk memilih berkas atau seret ke sini</p>
                                <p class="text-xs text-gray-500 mt-0.5" id="file-size-text"></p>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('sertifikasi')" class="mt-2" />
                    </div>

                    <!-- Persetujuan -->
                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-0.5">
                                <input type="checkbox" id="setuju_informasi" name="setuju_informasi" value="1" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" {{ old('setuju_informasi') ? 'checked' : '' }} required />
                            </div>
                            <label for="setuju_informasi" class="ml-3 text-xs sm:text-sm text-blue-800 leading-tight">
                                Saya menyatakan bahwa seluruh informasi dan berkas sertifikasi yang saya lampirkan adalah benar dan valid. Saya bersedia meninjau dan menilai modul-modul ajar secara profesional sesuai dengan kriteria yang ditentukan oleh sistem SiPeMo.
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('setuju_informasi')" class="mt-2" />
                    </div>
                </div>

                <!-- Action Button -->
                <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                    <a href="{{ route('home') }}" class="mr-3 px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors shadow-sm">
                        Kirim Pendaftaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function updateFileName(input) {
            const textElement = document.getElementById('file-upload-text');
            const sizeElement = document.getElementById('file-size-text');
            if (input.files && input.files.length > 0) {
                const file = input.files[0];
                textElement.innerHTML = `Terpilih: <strong class="text-blue-600">${file.name}</strong>`;
                const sizeInMB = (file.size / (1024 * 1024)).toFixed(2);
                sizeElement.innerHTML = `Ukuran: ${sizeInMB} MB`;
            } else {
                textElement.innerHTML = 'Klik untuk memilih berkas atau seret ke sini';
                sizeElement.innerHTML = '';
            }
        }
    </script>
</x-guest-with-header-footer>
