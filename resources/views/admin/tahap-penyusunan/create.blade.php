@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Buat Periode Tahap Penyusunan') }}
    </h2>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-4 sm:p-6 text-gray-900">
                @if($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.tahap-penyusunan.store') }}">
                    @csrf

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Periode</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-3">
                                <label for="nama_periode" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Periode
                                </label>
                                <input type="text"
                                       id="nama_periode"
                                       name="nama_periode"
                                       value="{{ old('nama_periode') }}"
                                       placeholder="Contoh: Periode Semester Ganjil 2024/2025"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                                @error('nama_periode')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Mulai
                                </label>
                                <input type="date"
                                       id="tanggal_mulai"
                                       name="tanggal_mulai"
                                       value="{{ old('tanggal_mulai') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                                @error('tanggal_mulai')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Selesai
                                </label>
                                <input type="date"
                                       id="tanggal_selesai"
                                       name="tanggal_selesai"
                                       value="{{ old('tanggal_selesai') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                                @error('tanggal_selesai')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="jumlah_tahap" class="block text-sm font-medium text-gray-700 mb-2">
                                    Jumlah Tahap
                                </label>
                                <input type="number"
                                       id="jumlah_tahap"
                                       name="jumlah_tahap"
                                       min="1"
                                       max="10"
                                       value="{{ old('jumlah_tahap', 4) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                                <p class="mt-1 text-xs text-gray-500">Minimal 1, maksimal 10 tahap.</p>
                                @error('jumlah_tahap')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Deskripsi Tiap Tahap</h3>
                        <div class="bg-blue-50 p-4 rounded-lg mb-4">
                            <p class="text-sm text-blue-800">
                                Tentukan berapa tahap yang digunakan dan isi deskripsi tiap tahap (apa yang harus dikumpulkan penyusun).
                                Tanggal tiap tahap akan dibagi rata dari rentang tanggal periode, dan dapat diedit setelah periode dibuat.
                            </p>
                        </div>

                        <div id="deskripsi-tahap-container" class="space-y-4">
                            {{-- Diisi oleh JavaScript --}}
                        </div>
                        @error('deskripsi_tahap')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2 sm:gap-4">
                        <a href="{{ route('admin.tahap-penyusunan.index') }}"
                           class="inline-flex justify-center bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Batal
                        </a>
                        <button type="submit"
                                class="inline-flex justify-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Buat Periode Tahap Penyusunan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const jumlahInput = document.getElementById('jumlah_tahap');
    const container = document.getElementById('deskripsi-tahap-container');
    const oldValues = @json(old('deskripsi_tahap', []));

    function renderDeskripsiFields() {
        let jumlah = parseInt(jumlahInput.value, 10);
        if (isNaN(jumlah) || jumlah < 1) jumlah = 1;
        if (jumlah > 10) jumlah = 10;
        jumlahInput.value = jumlah;

        const currentValues = [];
        container.querySelectorAll('textarea[name="deskripsi_tahap[]"]').forEach((el) => {
            currentValues.push(el.value);
        });

        container.innerHTML = '';

        for (let i = 1; i <= jumlah; i++) {
            const value = currentValues[i - 1] ?? oldValues[i - 1] ?? '';

            const wrapper = document.createElement('div');
            wrapper.className = 'border border-gray-200 rounded-lg p-4';

            const label = document.createElement('label');
            label.className = 'block text-sm font-medium text-gray-700 mb-2';
            label.innerHTML = 'Deskripsi Tahap ' + i + ' <span class="text-red-500">*</span>';

            const textarea = document.createElement('textarea');
            textarea.name = 'deskripsi_tahap[]';
            textarea.rows = 2;
            textarea.required = true;
            textarea.className = 'w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent';
            textarea.placeholder = 'Contoh: Penyusunan modul bab 1–2 / materi yang harus dikumpulkan';
            textarea.value = value;

            const hint = document.createElement('p');
            hint.className = 'mt-1 text-xs text-gray-500';
            hint.textContent = 'Jelaskan apa yang harus dikumpulkan penyusun pada tahap ini.';

            wrapper.appendChild(label);
            wrapper.appendChild(textarea);
            wrapper.appendChild(hint);
            container.appendChild(wrapper);
        }
    }

    jumlahInput.addEventListener('input', renderDeskripsiFields);
    jumlahInput.addEventListener('change', renderDeskripsiFields);
    renderDeskripsiFields();
})();
</script>
@endsection
