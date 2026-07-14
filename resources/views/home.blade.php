<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SiPeMo — Sistem Penyusunan Modul</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white text-gray-900">
    <div class="min-h-screen flex flex-col">
        <header class="w-full border-b border-green-100">
            <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                    <div class="w-9 h-9 rounded-full bg-green-600 flex items-center justify-center text-white font-semibold">S</div>
                    <div>
                        <div class="font-semibold text-gray-900">SiPeMo</div>
                        <div class="text-xs text-gray-500 -mt-0.5">Sistem Penyusunan Modul</div>
                    </div>
                </a>
                <div class="flex items-center gap-3">
                    @if (Route::has('login'))
                    @auth
                    <a href="{{ route(auth()->user()->getDashboardRoute()) }}" class="px-4 py-2 rounded-full bg-green-600 text-white hover:bg-green-700 transition">Dashboard</a>
                    @else
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="px-4 py-2 rounded-full bg-green-50 text-green-700 ring-1 ring-green-200 hover:bg-green-100 transition flex items-center gap-1">
                            Daftar
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl ring-1 ring-black ring-opacity-5 z-50 py-1 border border-gray-100">
                            <a href="{{ route('penyusun.apply.create') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-800 transition">
                                Penyusun Modul
                            </a>
                            <a href="{{ route('reviewer.apply.create') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-800 transition">
                                Reviewer Modul
                            </a>
                        </div>
                    </div>
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-full bg-green-600 text-white hover:bg-green-700 transition">Masuk</a>
                    @endauth
                    @endif
                </div>
            </div>
        </header>

        <main class="flex-1">
            <section class="max-w-7xl mx-auto px-6 py-16 grid md:grid-cols-2 gap-10 items-center">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Selamat datang di SiPeMo</h1>
                    <p class="mt-4 text-gray-600 leading-relaxed">Sistem Penyusunan Modul (SiPeMo) adalah platform digital untuk mengelola proses penyusunan modul pembelajaran secara terstruktur dan efisien. Sistem ini mendukung tiga peran pengguna: Admin untuk validasi dan manajemen sistem, Penyusun untuk mengupload modul secara bertahap, dan LPM untuk monitoring dan evaluasi.</p>
                    <div class="mt-8 flex flex-col sm:flex-row items-start sm:items-center gap-3">
                        <a href="{{ route('progres-penyusunan') }}" class="px-5 py-2.5 rounded-full bg-green-600 text-white hover:bg-green-700 transition shadow-sm">Progres Penyusunan</a>
                        <a href="{{ route('penyusun.apply.create') }}" class="px-5 py-2.5 rounded-full bg-green-50 text-green-700 ring-1 ring-green-200 hover:bg-green-100 transition shadow-sm">Daftar Penyusun</a>
                        <a href="{{ route('reviewer.apply.create') }}" class="px-5 py-2.5 rounded-full bg-blue-50 text-blue-700 ring-1 ring-blue-200 hover:bg-blue-100 transition shadow-sm">Daftar Reviewer</a>
                    </div>
                </div>
                <div class="relative">
					<div class="aspect-video rounded-2xl ring-1 ring-green-100 shadow-sm relative overflow-hidden">
						<img src="{{ asset('bg-sipemo.jpg') }}" alt="Background SiPeMo" class="absolute inset-0 w-full h-full object-cover">
					</div>
                    <div class="absolute -bottom-6 -left-6 w-24 h-24 bg-green-200 rounded-xl blur-xl opacity-60"></div>
                    <div class="absolute -top-6 -right-6 w-24 h-24 bg-emerald-200 rounded-xl blur-xl opacity-60"></div>
                </div>
            </section>

            <section id="fitur" class="bg-green-50/40 border-t border-b border-green-100">
                <div class="max-w-7xl mx-auto px-6 py-12">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Fitur Utama SiPeMo</h2>
                        <p class="text-gray-600">Platform lengkap untuk mengelola penyusunan modul pembelajaran</p>
                    </div>
                    <div class="grid md:grid-cols-2 gap-6 items-stretch">
                        <div class="bg-white rounded-xl ring-1 ring-green-100 p-5 shadow-sm h-full">
                            <div class="w-9 h-9 rounded-full bg-green-600 text-white flex items-center justify-center font-semibold">1</div>
                            <h3 class="mt-4 font-semibold">Pendaftaran Penyusun</h3>
                            <p class="text-sm text-gray-600 mt-1">Dosen dapat mendaftar sebagai penyusun modul untuk mata kuliah yang tersedia dengan sistem aplikasi yang terstruktur.</p>
                        </div>
                        <div class="bg-white rounded-xl ring-1 ring-green-100 p-5 shadow-sm h-full">
                            <div class="w-9 h-9 rounded-full bg-green-600 text-white flex items-center justify-center font-semibold">2</div>
                            <h3 class="mt-4 font-semibold">Penyusunan Bertahap</h3>
                            <p class="text-sm text-gray-600 mt-1">Upload modul secara bertahap dengan sistem otomatis yang memungkinkan akses fleksibel sesuai jadwal yang ditentukan.</p>
                        </div>
                        <div class="bg-white rounded-xl ring-1 ring-green-100 p-5 shadow-sm h-full">
                            <div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold">3</div>
                            <h3 class="mt-4 font-semibold">Final Draft & Publikasi</h3>
                            <p class="text-sm text-gray-600 mt-1">Proses finalisasi modul dengan draft akhir dan publikasi untuk penggunaan pembelajaran.</p>
                        </div>
                        <div class="bg-white rounded-xl ring-1 ring-green-100 p-5 shadow-sm h-full">
                            <div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold">4</div>
                            <h3 class="mt-4 font-semibold">Monitoring Real-time</h3>
                            <p class="text-sm text-gray-600 mt-1">Dashboard monitoring untuk melacak progres penyusunan modul secara real-time dengan status yang informatif.</p>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="px-6 py-6 text-center text-sm text-gray-500">
        © <a href="{{ url('/') }}" class="hover:underline">SiPeMo</a> {{ date('Y') }} – Developed by <a href="https://suhadip.com" target="_blank" rel="noopener noreferrer" class="hover:underline">Suhadi Parman</a> | All rights reserved.
        </footer>
    </div>

    <!-- Modal Konfirmasi Pendaftaran -->
    @if(session('show_modal'))
    <div id="confirmationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 scale-100">
            <!-- Header Modal -->
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4 rounded-t-2xl">
                <div class="flex items-center justify-center">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white">Pendaftaran Berhasil!</h3>
                </div>
            </div>

            <!-- Body Modal -->
            <div class="px-6 py-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Terima Kasih!</h4>
                    <p class="text-gray-600 mb-4">
                        Pendaftaran Anda sebagai penyusun modul telah berhasil dikirim dan sedang dalam proses review.
                    </p>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h5 class="text-sm font-medium text-blue-800 mb-1">Informasi Selanjutnya</h5>
                                <p class="text-sm text-blue-700">
                                    Silakan tunggu pengumuman hasil review melalui grup WhatsApp yang telah ditentukan. 
                                    Tim kami akan menghubungi Anda dalam waktu 3-5 hari kerja.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Modal -->
            <div class="px-6 py-4 bg-gray-50 rounded-b-2xl">
                <button onclick="closeModal()" class="w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold py-3 px-4 rounded-lg hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200">
                    <div class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Mengerti
                    </div>
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Konfirmasi Pendaftaran Reviewer -->
    @if(session('show_modal_reviewer'))
    <div id="confirmationModalReviewer" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 scale-100">
            <!-- Header Modal -->
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4 rounded-t-2xl">
                <div class="flex items-center justify-center">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white">Pendaftaran Reviewer Berhasil!</h3>
                </div>
            </div>

            <!-- Body Modal -->
            <div class="px-6 py-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Terima Kasih!</h4>
                    <p class="text-gray-600 mb-4">
                        Pendaftaran Anda sebagai reviewer modul telah berhasil dikirim dan sedang dalam proses review oleh Admin.
                    </p>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h5 class="text-sm font-medium text-blue-800 mb-1">Informasi Selanjutnya</h5>
                                <p class="text-sm text-blue-700">
                                    Silakan tunggu proses verifikasi akun oleh Admin. Akun reviewer Anda akan aktif setelah disetujui, dan Anda dapat login menggunakan email dan password NIDN Anda.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Modal -->
            <div class="px-6 py-4 bg-gray-50 rounded-b-2xl">
                <button onclick="closeModalReviewer()" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold py-3 px-4 rounded-lg hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">
                    <div class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Mengerti
                    </div>
                </button>
            </div>
        </div>
    </div>
    @endif

    <script>
        function closeModal() {
            const modal = document.getElementById('confirmationModal');
            if (modal) {
                modal.style.opacity = '0';
                modal.style.transform = 'scale(0.95)';
                setTimeout(function() {
                    modal.remove();
                }, 300);
            }
        }

        function closeModalReviewer() {
            const modal = document.getElementById('confirmationModalReviewer');
            if (modal) {
                modal.style.opacity = '0';
                modal.style.transform = 'scale(0.95)';
                setTimeout(function() {
                    modal.remove();
                }, 300);
            }
        }

        document.addEventListener('click', function(e) {
            const modal = document.getElementById('confirmationModal');
            if (modal && e.target === modal) {
                closeModal();
            }
            const modalRev = document.getElementById('confirmationModalReviewer');
            if (modalRev && e.target === modalRev) {
                closeModalReviewer();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
                closeModalReviewer();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('confirmationModal');
            if (modal) {
                setTimeout(function() {
                    closeModal();
                }, 10000);
            }
            const modalRev = document.getElementById('confirmationModalReviewer');
            if (modalRev) {
                setTimeout(function() {
                    closeModalReviewer();
                }, 10000);
            }
        });
    </script>
</body>

</html>