<x-guest-with-header-footer>
    <div class="w-full max-w-4xl mx-auto">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-12 h-12 md:w-16 md:h-16 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full mb-4">
                <svg class="w-6 h-6 md:w-8 md:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Pendaftaran Penyusun Modul</h1>
            <p class="text-sm md:text-base text-gray-600">Bergabunglah dengan tim penyusun modul pembelajaran kami</p>
        </div>

        <!-- Progress Steps -->
        <div class="mb-8">
            <!-- Desktop Progress Steps -->
            <div class="hidden md:flex items-center justify-center space-x-4">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-green-600 text-white rounded-full text-sm font-semibold step-indicator" data-step="1" style="background-color: #059669;">1</div>
                    <span class="ml-2 text-sm font-medium text-gray-700 step-label" data-step="1" style="color: #374151;">Informasi Pribadi</span>
                </div>
                <div class="flex-1 h-0.5 bg-gray-200 step-line" data-step="1"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-gray-300 text-gray-600 rounded-full text-sm font-semibold step-indicator" data-step="2">2</div>
                    <span class="ml-2 text-sm font-medium text-gray-500 step-label" data-step="2">Informasi Akademik</span>
                </div>
                <div class="flex-1 h-0.5 bg-gray-200 step-line" data-step="2"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-gray-300 text-gray-600 rounded-full text-sm font-semibold step-indicator" data-step="3">3</div>
                    <span class="ml-2 text-sm font-medium text-gray-500 step-label" data-step="3">Upload Dokumen</span>
                </div>
                <div class="flex-1 h-0.5 bg-gray-200 step-line" data-step="3"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-gray-300 text-gray-600 rounded-full text-sm font-semibold step-indicator" data-step="4">4</div>
                    <span class="ml-2 text-sm font-medium text-gray-500 step-label" data-step="4">Persetujuan</span>
                </div>
            </div>
            
            <!-- Mobile Progress Steps -->
            <div class="md:hidden">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-2">
                        <div class="flex items-center justify-center w-8 h-8 bg-green-600 text-white rounded-full text-xs font-semibold step-indicator" data-step="1" style="background-color: #059669;">1</div>
                        <span class="text-xs font-medium text-gray-700 step-label" data-step="1" style="color: #374151;">Pribadi</span>
                    </div>
                    <div class="flex-1 h-0.5 bg-gray-200 step-line" data-step="1"></div>
                    <div class="flex items-center space-x-2">
                        <div class="flex items-center justify-center w-8 h-8 bg-gray-300 text-gray-600 rounded-full text-xs font-semibold step-indicator" data-step="2">2</div>
                        <span class="text-xs font-medium text-gray-500 step-label" data-step="2">Akademik</span>
                    </div>
                    <div class="flex-1 h-0.5 bg-gray-200 step-line" data-step="2"></div>
                    <div class="flex items-center space-x-2">
                        <div class="flex items-center justify-center w-8 h-8 bg-gray-300 text-gray-600 rounded-full text-xs font-semibold step-indicator" data-step="3">3</div>
                        <span class="text-xs font-medium text-gray-500 step-label" data-step="3">Upload</span>
                    </div>
                    <div class="flex-1 h-0.5 bg-gray-200 step-line" data-step="3"></div>
                    <div class="flex items-center space-x-2">
                        <div class="flex items-center justify-center w-8 h-8 bg-gray-300 text-gray-600 rounded-full text-xs font-semibold step-indicator" data-step="4">4</div>
                        <span class="text-xs font-medium text-gray-500 step-label" data-step="4">Setuju</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <form method="POST" action="{{ route('penyusun.apply.store') }}" enctype="multipart/form-data" id="multiStepForm" class="p-4 md:p-8" novalidate>
                @csrf

                <!-- Step 1: Personal Information -->
                <div class="step-content" data-step="1" style="display: block;">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-4 md:px-8 py-4 md:py-6 -mx-4 md:-mx-8 -mt-4 md:-mt-8 mb-6 md:mb-8 border-b border-gray-100">
                        <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Informasi Pribadi
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">Lengkapi data pribadi Anda</p>
                    </div>

                    <div class="space-y-4 md:space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                        <div class="space-y-2">
                            <x-input-label for="nama_penyusun" value="Nama Lengkap" class="text-sm font-medium text-gray-700" />
                            <div class="relative">
                                <x-text-input id="nama_penyusun" name="nama_penyusun" type="text" class="w-full pl-10" :value="old('nama_penyusun')" required />
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('nama_penyusun')" class="mt-1" />
                        </div>

                        <div class="space-y-2">
                            <x-input-label for="email" value="Email" class="text-sm font-medium text-gray-700" />
                            <div class="relative">
                                <x-text-input id="email" name="email" type="email" class="w-full pl-10" :value="old('email')" required />
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-1" />
                        </div>
                    </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
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

                        <div class="space-y-2">
                            <x-input-label for="nip" value="NIP (wajib bagi ASN)" class="text-sm font-medium text-gray-700" />
                            <div class="relative">
                                <x-text-input id="nip" name="nip" type="text" class="w-full pl-10" :value="old('nip')" placeholder="Opsional" inputmode="numeric" pattern="[0-9]*" oninput="this.value=this.value.replace(/[^0-9]/g,'')" />
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                    </svg>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('nip')" class="mt-1" />
                        </div>
                    </div>

                    <div class="space-y-2">
                        <x-input-label for="nidn" value="NIDN" class="text-sm font-medium text-gray-700" />
                        <div class="relative">
                            <x-text-input id="nidn" name="nidn" type="text" class="w-full pl-10" :value="old('nidn')" placeholder="Wajib" inputmode="numeric" pattern="[0-9]*" oninput="this.value=this.value.replace(/[^0-9]/g,'')" required />
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('nidn')" class="mt-1" />
                        </div>
                    </div>
                </div>

                <!-- Step 2: Academic Information -->
                <div class="step-content" data-step="2" style="display: none;">
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-4 md:px-8 py-4 md:py-6 -mx-4 md:-mx-8 -mt-4 md:-mt-8 mb-6 md:mb-8 border-b border-gray-100">
                        <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            Informasi Akademik
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">Pilih jurusan dan mata kuliah yang akan disusun</p>
                    </div>

                    <div class="space-y-4 md:space-y-6">
                        <div class="space-y-2">
                            <x-input-label for="jurusan_id" value="Jurusan" class="text-sm font-medium text-gray-700" />
                            <div class="relative">
                                <select id="jurusan_id" name="jurusan_id" class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                                    <option value="">Pilih Jurusan</option>
                                    @foreach($jurusan as $j)
                                        <option value="{{ $j->id }}" {{ old('jurusan_id') == $j->id ? 'selected' : '' }}>
                                            {{ $j->nama_jurusan }} ({{ $j->kode_jurusan }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('jurusan_id')" class="mt-1" />
                        </div>

                        <!-- Mata Kuliah List -->
                        <div id="mata-kuliah-list" class="hidden">
                            <x-input-label value="Pilih Mata Kuliah" class="text-sm font-medium text-gray-700 mb-3" />
                            <div id="mata-kuliah-container" class="space-y-2 md:space-y-3">
                            </div>
                            <input type="hidden" id="mata_kuliah_id" name="mata_kuliah_id" value="">
                            <x-input-error :messages="$errors->get('mata_kuliah_id')" class="mt-1" />
                        </div>
                    </div>
                </div>

                <!-- Step 3: Document Upload -->
                <div class="step-content" data-step="3" style="display: none;">
                    <div class="bg-gradient-to-r from-purple-50 to-purple-100 px-4 md:px-8 py-4 md:py-6 -mx-4 md:-mx-8 -mt-4 md:-mt-8 mb-6 md:mb-8 border-b border-gray-100">
                        <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                            <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            Upload Dokumen
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">Upload draft dan masukkan judul bahan ajar</p>
                    </div>

                    <div class="space-y-4 md:space-y-6">
                        <div class="space-y-2">
                            <x-input-label for="judul_bahan_ajar" value="Judul Bahan Ajar" class="text-sm font-medium text-gray-700" />
                            <div class="relative">
                                <x-text-input id="judul_bahan_ajar" name="judul_bahan_ajar" type="text" class="w-full pl-10" :value="old('judul_bahan_ajar')" required placeholder="Masukkan judul bahan ajar yang akan disusun" />
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('judul_bahan_ajar')" class="mt-1" />
                    </div>

                    <div class="space-y-2">
                        <x-input-label for="draft" value="Draft/Konsep Bahan Ajar (Opsional)" class="text-sm font-medium text-gray-700" />
                        <div class="relative">
                                <input id="draft" name="draft" type="file" accept=".doc,.docx" class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors hover:border-gray-400" />
                            </div>
                            <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <p class="text-xs text-blue-800 font-medium mb-1">📄 Informasi Dokumen:</p>
                                <p class="text-xs text-blue-700 leading-relaxed">
                                    Dokumen ini <strong>bersifat opsional</strong> dan berisi pemaparan gambaran umum modul yang akan dibuat beserta pembahasan tiap bab-nya. Format: .doc atau .docx (Maksimal 1MB)
                                </p>
                            </div>
                            <x-input-error :messages="$errors->get('draft')" class="mt-1" />
                        </div>
                    </div>
                </div>

                <!-- Step 4: Agreement -->
                <div class="step-content" data-step="4" style="display: none;">
                    <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 px-4 md:px-8 py-4 md:py-6 -mx-4 md:-mx-8 -mt-4 md:-mt-8 mb-6 md:mb-8 border-b border-gray-100">
                        <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                            <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Persetujuan
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">Baca dan setujui ketentuan berikut</p>
                    </div>

                    <div class="space-y-4 md:space-y-6">
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 md:p-6 space-y-3 md:space-y-4">

                            <!-- Gabungan persetujuan pelaksanaan -->
                            <div class="flex items-start space-x-3">
                                <input id="setuju_pelaksanaan" name="setuju_pelaksanaan" type="checkbox" value="1" class="mt-1 h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded" required />
                                <label for="setuju_pelaksanaan" class="text-xs md:text-sm text-gray-700 leading-relaxed">
                                    <span class="font-medium">
                                        Saya siap menyusun modul sesuai template dan menyelesaikan modul sesuai waktu yang ditetapkan.
                                    </span>
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('setuju_pelaksanaan')" class="mt-1" />

                            <!-- Gabungan persetujuan jumlah modul -->
                            <div class="flex items-start space-x-3">
                                <input id="setuju_jml_modul" name="setuju_jml_modul" type="checkbox" value="1" class="mt-1 h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded" required />
                                <label for="setuju_jml_modul" class="text-xs md:text-sm text-gray-700 leading-relaxed">
                                    <span class="font-medium">
                                        Saya akan menyelesaikan 6 modul untuk mata kuliah dengan 2 SKS, dan 9 modul untuk mata kuliah dengan 3 SKS.
                                    </span>
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('setuju_jml_modul')" class="mt-1" />

                            <!-- Persetujuan informasi benar -->
                            <div class="flex items-start space-x-3">
                                <input id="setuju_informasi" name="setuju_informasi" type="checkbox" value="1" class="mt-1 h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded" required />
                                <label for="setuju_informasi" class="text-xs md:text-sm text-gray-700 leading-relaxed">
                                    <span class="font-medium">
                                        Semua informasi yang saya berikan adalah benar dan dapat dipertanggungjawabkan.
                                    </span>
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('setuju_informasi')" class="mt-1" />

                            <!-- Persetujuan belum pernah dibiayai & tidak menerima pembiayaan lain -->
                            <div class="flex items-start space-x-3">
                                <input id="setuju_pembiayaan" name="setuju_pembiayaan" type="checkbox" value="1" class="mt-1 h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded" required />
                                <label for="setuju_pembiayaan" class="text-xs md:text-sm text-gray-700 leading-relaxed">
                                    <span class="font-medium">
                                        Saya menyatakan <u>tidak sedang menerima pembiayaan</u> penyusunan modul dari sumber manapun dan modul yang dikembangkan <u>belum pernah mendapatkan pembiayaan apapun sebelumnya</u>.
                                    </span>
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('setuju_pembiayaan')" class="mt-1" />

                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex flex-col sm:flex-row justify-between pt-4 md:pt-6 border-t border-gray-200 mt-6 md:mt-8 gap-4 sm:gap-0">
                    <button type="button" id="prevBtn" class="hidden bg-gray-500 text-white font-semibold py-3 px-4 md:px-6 rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 w-full sm:w-auto">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Sebelumnya
                        </div>
                    </button>
                    
                    <div class="flex-1"></div>
                    
                    <button type="button" id="nextBtn" class="bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold py-3 px-4 md:px-6 rounded-lg hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 w-full sm:w-auto">
                        <div class="flex items-center">
                            Selanjutnya
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </button>
                    
                    <button type="submit" id="submitBtn" class="hidden bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold py-3 px-4 md:px-6 rounded-lg hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 w-full sm:w-auto">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            Kirim Pendaftaran
                        </div>
                    </button>
                </div>

                <!-- Inline error container (client-side) -->
                <div id="inline-errors" class="hidden mt-4">
                    <div class="rounded-lg border border-red-200 bg-red-50 p-4">
                        <p class="text-sm font-semibold text-red-800 mb-2">Mohon periksa input Anda:</p>
                        <ul id="inline-errors-list" class="list-disc pl-5 text-sm text-red-700 space-y-1"></ul>
                    </div>
                </div>
                
                @if ($errors->any())
                <div class="mt-4">
                    <div class="rounded-lg border border-red-200 bg-red-50 p-4">
                        <p class="text-sm font-semibold text-red-800 mb-2">Mohon periksa input Anda:</p>
                        <ul class="list-disc pl-5 text-sm text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentStep = 1;
            const totalSteps = 4;
            
            const nextBtn = document.getElementById('nextBtn');
            const prevBtn = document.getElementById('prevBtn');
            const submitBtn = document.getElementById('submitBtn');
            const form = document.getElementById('multiStepForm');
            const inlineErrorsBox = document.getElementById('inline-errors');
            const inlineErrorsList = document.getElementById('inline-errors-list');
            
            function showStep(step) {
                document.querySelectorAll('.step-content').forEach((content, index) => {
                    const stepNumber = index + 1;
                    if (stepNumber === step) {
                        content.style.display = 'block';
                    } else {
                        content.style.display = 'none';
                    }
                });
                
                updateProgressIndicators(step);
                updateNavigationButtons(step);
            }
            
            function updateProgressIndicators(step) {
                document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
                    const stepNumber = index + 1;
                    if (stepNumber < step) {
                        // Completed steps
                        indicator.classList.remove('bg-gray-300', 'text-gray-600', 'bg-green-600', 'text-white');
                        indicator.classList.add('bg-green-600', 'text-white');
                        indicator.innerHTML = '✓';
                    } else if (stepNumber === step) {
                        // Current step
                        indicator.classList.remove('bg-gray-300', 'text-gray-600', 'bg-green-600', 'text-white');
                        indicator.classList.add('bg-green-600', 'text-white');
                        indicator.innerHTML = stepNumber;
                    } else {
                        // Future steps
                        indicator.classList.remove('bg-green-600', 'text-white');
                        indicator.classList.add('bg-gray-300', 'text-gray-600');
                        indicator.innerHTML = stepNumber;
                    }
                });
                
                document.querySelectorAll('.step-line').forEach((line, index) => {
                    const stepNumber = index + 1;
                    if (stepNumber < step) {
                        line.classList.remove('bg-gray-200');
                        line.classList.add('bg-green-500');
                    } else {
                        line.classList.remove('bg-green-500');
                        line.classList.add('bg-gray-200');
                    }
                });
                
                document.querySelectorAll('.step-label').forEach((label, index) => {
                    const stepNumber = index + 1;
                    if (stepNumber <= step) {
                        label.classList.remove('text-gray-500');
                        label.classList.add('text-gray-700');
                    } else {
                        label.classList.remove('text-gray-700');
                        label.classList.add('text-gray-500');
                    }
                });
            }
            
            function updateNavigationButtons(step) {
                if (step === 1) {
                    prevBtn.classList.add('hidden');
                } else {
                    prevBtn.classList.remove('hidden');
                }
                
                if (step === totalSteps) {
                    nextBtn.classList.add('hidden');
                    submitBtn.classList.remove('hidden');
                } else {
                    nextBtn.classList.remove('hidden');
                    submitBtn.classList.add('hidden');
                }
            }
            
            nextBtn.addEventListener('click', function() {
                if (validateCurrentStep()) {
                    currentStep++;
                    showStep(currentStep);
                }
            });
            
            prevBtn.addEventListener('click', function() {
                currentStep--;
                showStep(currentStep);
            });
            
            function validateCurrentStep() {
                const currentStepContent = document.querySelector(`[data-step="${currentStep}"]`);
                const requiredFields = currentStepContent.querySelectorAll('[required]');
                let isValid = true;
                const messages = [];
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                        messages.push(`${getFieldLabel(field)} wajib diisi.`);
                        isValid = false;
                    } else {
                        field.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                    }
                });
                
                if (currentStep === 2) {
                    const selectedMataKuliah = document.getElementById('mata_kuliah_id').value;
                    if (!selectedMataKuliah) {
                        messages.push('Mata kuliah yang akan disusun wajib dipilih.');
                        isValid = false;
                    }
                }
                
                if (currentStep === 4) {
                    const agreementCheckboxes = currentStepContent.querySelectorAll('input[type="checkbox"][required]');
                    agreementCheckboxes.forEach(checkbox => {
                        if (!checkbox.checked) {
                            checkbox.classList.add('border-red-500');
                            messages.push(`${getFieldLabel(checkbox)} harus disetujui.`);
                            isValid = false;
                        } else {
                            checkbox.classList.remove('border-red-500');
                        }
                    });
                }
                
                if (!isValid) renderInlineErrors(messages);
                
                return isValid;
            }

            function getFieldLabel(field) {
                if (field.id) {
                    const lbl = form.querySelector(`label[for="${field.id}"]`);
                    if (lbl) return lbl.textContent.trim();
                }
                return (field.getAttribute('name') || field.id || 'Field').replaceAll('_', ' ');
            }

            function renderInlineErrors(messages) {
                if (!messages || messages.length === 0) return;
                inlineErrorsList.innerHTML = '';
                messages.forEach(msg => {
                    const li = document.createElement('li');
                    li.textContent = msg;
                    inlineErrorsList.appendChild(li);
                });
                inlineErrorsBox.classList.remove('hidden');
                inlineErrorsBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }

            form.addEventListener('submit', function(e) {
                // Kumpulkan semua error agar tampil di bawah tombol
                const messages = [];
                let ok = true;
                const allRequired = form.querySelectorAll('[required]');
                allRequired.forEach(field => {
                    const isCheckbox = field.type === 'checkbox';
                    if ((isCheckbox && !field.checked) || (!isCheckbox && !String(field.value).trim())) {
                        ok = false;
                        messages.push(`${getFieldLabel(field)} ${isCheckbox ? 'harus disetujui' : 'wajib diisi'}.`);
                    }
                });
                const mkId = document.getElementById('mata_kuliah_id').value;
                if (!mkId) {
                    ok = false;
                    messages.push('Mata kuliah yang akan disusun wajib dipilih.');
                }
                if (!ok) {
                    e.preventDefault();
                    renderInlineErrors(messages);
                }
            });
            
            const jurusanSelect = document.getElementById('jurusan_id');
            const mataKuliahList = document.getElementById('mata-kuliah-list');
            const mataKuliahContainer = document.getElementById('mata-kuliah-container');
            const mataKuliahIdInput = document.getElementById('mata_kuliah_id');
            
            jurusanSelect.addEventListener('change', function() {
                const jurusanId = this.value;
                
                if (jurusanId) {
                    mataKuliahContainer.innerHTML = '<div class="text-center py-4"><div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div><p class="mt-2 text-sm text-gray-600">Memuat mata kuliah...</p></div>';
                    mataKuliahList.classList.remove('hidden');
                    
                    // Fetch mata kuliah for selected jurusan
                    fetch(`/api/mata-kuliah/${jurusanId}`)
                        .then(response => response.json())
                        .then(data => {
                            mataKuliahContainer.innerHTML = '';
                            
                            if (data.length === 0) {
                                mataKuliahContainer.innerHTML = '<div class="text-center py-4 text-gray-500">Tidak ada mata kuliah tersedia untuk jurusan ini.</div>';
                                return;
                            }
                            
                            // Get approved mata kuliah IDs
                            const approvedMataKuliahIds = data.filter(mk => !mk.is_available).map(mk => mk.id);
                            
                            data.forEach(mk => {
                                const isAvailable = mk.is_available !== false;
                                const card = document.createElement('div');
                                card.className = `border rounded-lg p-4 cursor-pointer transition-all duration-200 ${
                                    isAvailable 
                                        ? 'border-gray-200 hover:border-blue-300 hover:bg-blue-50' 
                                        : 'border-red-200 bg-red-50 opacity-60 cursor-not-allowed'
                                }`;
                                
                                card.innerHTML = `
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">${mk.nama_mata_kuliah}</h4>
                                            <p class="text-sm text-gray-600">${mk.kode_mata_kuliah} - Semester ${mk.semester} - ${mk.sks} SKS</p>
                                        </div>
                                        <div class="flex flex-col items-end space-y-1 md:flex-row md:items-center md:space-x-2 md:space-y-0 md:ml-3">
                                            ${isAvailable && typeof mk.pending_count !== 'undefined' 
                                                ? `<span class=\"inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800\">${mk.pending_count} pendaftar</span>`
                                                : ''
                                            }
                                            ${isAvailable 
                                                ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Tersedia</span>'
                                                : '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Tidak Tersedia</span>'
                                            }
                                        </div>
                                    </div>
                                `;
                                
                                if (isAvailable) {
                                    card.addEventListener('click', function() {
                                        document.querySelectorAll('.border-blue-500').forEach(el => {
                                            el.classList.remove('border-blue-500', 'bg-blue-100');
                                        });
                                        
                                        this.classList.add('border-blue-500', 'bg-blue-100');
                                        mataKuliahIdInput.value = mk.id;
                                    });
                                }
                                
                                mataKuliahContainer.appendChild(card);
                            });
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            mataKuliahContainer.innerHTML = '<div class="text-center py-4 text-red-500">Error memuat data mata kuliah.</div>';
                        });
                } else {
                    mataKuliahList.classList.add('hidden');
                    mataKuliahIdInput.value = '';
                }
            });
            
            const fileInput = document.getElementById('draft');
            fileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    if (file.size > 1 * 1024 * 1024) {
                        alert('Ukuran file terlalu besar. Maksimal 1MB.');
                        this.value = '';
                        return;
                    }
                    
                    const allowedTypes = ['.doc', '.docx'];
                    const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
                    if (!allowedTypes.includes(fileExtension)) {
                        alert('Format file tidak didukung. Gunakan file .doc atau .docx.');
                        this.value = '';
                        return;
                    }
                }
            });
            
            const inputs = form.querySelectorAll('input, select');
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.hasAttribute('required') && !this.value.trim()) {
                        this.classList.add('border-red-500');
                    } else {
                        this.classList.remove('border-red-500');
                    }
                });
                
                input.addEventListener('input', function() {
                    if (this.classList.contains('border-red-500') && this.value.trim()) {
                        this.classList.remove('border-red-500');
                    }
                });
            });
            
            const firstStep = document.querySelector('[data-step="1"]');
            if (firstStep && firstStep.style.display === 'none') {
                showStep(1);
            }
        });
    </script>
</x-guest-with-header-footer>
