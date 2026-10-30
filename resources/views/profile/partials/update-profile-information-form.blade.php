<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informasi Profil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Perbarui data identitas profil dan alamat email akun Anda.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Alamat Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Alamat email Anda belum terverifikasi.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Profil Spesifik Penyusun --}}
        @if($user->is_penyusun && isset($penyusunApp))
            <div class="pt-4 border-t border-gray-200 space-y-4">
                <h3 class="text-sm font-semibold text-gray-800">Data Penyusun Bahan Ajar</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="nidn" value="NIDN" />
                        <x-text-input id="nidn" name="nidn" type="text" class="mt-1 block w-full" :value="old('nidn', $penyusunApp->nidn)" placeholder="Nomor Induk Dosen Nasional" />
                        <x-input-error class="mt-2" :messages="$errors->get('nidn')" />
                    </div>

                    <div>
                        <x-input-label for="nip" value="NIP" />
                        <x-text-input id="nip" name="nip" type="text" class="mt-1 block w-full" :value="old('nip', $penyusunApp->nip)" placeholder="NIP (jika ASN)" />
                        <x-input-error class="mt-2" :messages="$errors->get('nip')" />
                    </div>
                </div>

                <div>
                    <x-input-label for="no_wa" value="Nomor WhatsApp" />
                    <x-text-input id="no_wa" name="no_wa" type="text" class="mt-1 block w-full" :value="old('no_wa', $penyusunApp->no_wa)" placeholder="Contoh: 08123456789" />
                    <x-input-error class="mt-2" :messages="$errors->get('no_wa')" />
                </div>

                <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg text-xs space-y-1">
                    <div><strong>Judul Modul Terdaftar:</strong> {{ $penyusunApp->judul_bahan_ajar }}</div>
                    <div><strong>Mata Kuliah:</strong> {{ $penyusunApp->mata_kuliah }} ({{ $penyusunApp->jurusan }})</div>
                </div>
            </div>
        @endif

        {{-- Profil Spesifik Reviewer --}}
        @if($user->is_reviewer && isset($reviewerApp))
            <div class="pt-4 border-t border-gray-200 space-y-4">
                <h3 class="text-sm font-semibold text-gray-800">Data Reviewer Modul</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="nidn" value="NIDN" />
                        <x-text-input id="nidn" name="nidn" type="text" class="mt-1 block w-full" :value="old('nidn', $reviewerApp->nidn)" placeholder="Nomor Induk Dosen Nasional" />
                        <x-input-error class="mt-2" :messages="$errors->get('nidn')" />
                    </div>

                    <div>
                        <x-input-label for="nip" value="NIP" />
                        <x-text-input id="nip" name="nip" type="text" class="mt-1 block w-full" :value="old('nip', $reviewerApp->nip)" placeholder="NIP (jika ASN)" />
                        <x-input-error class="mt-2" :messages="$errors->get('nip')" />
                    </div>
                </div>

                <div>
                    <x-input-label for="no_wa" value="Nomor WhatsApp" />
                    <x-text-input id="no_wa" name="no_wa" type="text" class="mt-1 block w-full" :value="old('no_wa', $reviewerApp->no_wa)" placeholder="Contoh: 08123456789" />
                    <x-input-error class="mt-2" :messages="$errors->get('no_wa')" />
                </div>

                <div class="pt-2 border-t border-gray-100">
                    <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wide mb-3">Informasi Rekening Bank (Honorarium)</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <x-input-label for="nama_bank" value="Nama Bank" />
                            <x-text-input id="nama_bank" name="nama_bank" type="text" class="mt-1 block w-full" :value="old('nama_bank', $reviewerApp->nama_bank)" placeholder="BSI, BRI, BCA, dll." />
                            <x-input-error class="mt-2" :messages="$errors->get('nama_bank')" />
                        </div>

                        <div>
                            <x-input-label for="nomor_rekening" value="Nomor Rekening" />
                            <x-text-input id="nomor_rekening" name="nomor_rekening" type="text" class="mt-1 block w-full" :value="old('nomor_rekening', $reviewerApp->nomor_rekening)" placeholder="Nomor rekening" />
                            <x-input-error class="mt-2" :messages="$errors->get('nomor_rekening')" />
                        </div>

                        <div>
                            <x-input-label for="nama_pemilik_rekening" value="Pemilik Rekening" />
                            <x-text-input id="nama_pemilik_rekening" name="nama_pemilik_rekening" type="text" class="mt-1 block w-full" :value="old('nama_pemilik_rekening', $reviewerApp->nama_pemilik_rekening)" placeholder="Sesuai buku tabungan" />
                            <x-input-error class="mt-2" :messages="$errors->get('nama_pemilik_rekening')" />
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="flex items-center gap-4 pt-2">
            <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-sm text-green-600 font-medium"
                >{{ __('Profil berhasil diperbarui.') }}</p>
            @endif
        </div>
    </form>
</section>
