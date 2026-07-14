<nav x-data="{ open: false }" class="bg-white border-b border-green-100 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-6 py-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route(auth()->user()->getDashboardRoute()) }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                        <div class="w-9 h-9 rounded-full bg-green-600 flex items-center justify-center text-white font-semibold">S</div>
                        <div>
                            <div class="font-semibold text-gray-900">SiPeMo</div>
                            <div class="text-xs text-gray-500 -mt-0.5">Sistem Penyusunan Modul</div>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route(auth()->user()->getDashboardRoute())" :active="request()->routeIs(['dashboard', 'admin.dashboard', 'penyusun.dashboard', 'lpm.dashboard'])">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    
                    @if(auth()->user()->is_admin)
                        <!-- Kelola Data Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" 
                                    class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:border-gray-300 border-b-2 border-transparent transition-colors duration-200 {{ request()->routeIs(['admin.jurusan.*', 'admin.mata-kuliah.*', 'admin.user.*']) ? 'border-green-500 text-gray-900' : '' }}">
                                {{ __('Kelola Data') }}
                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" 
                                 class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                                <div class="py-1">
                                    <a href="{{ route('admin.jurusan.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.jurusan.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                                        {{ __('Kelola Jurusan') }}
                                    </a>
                                    <a href="{{ route('admin.mata-kuliah.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.mata-kuliah.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                                        {{ __('Kelola Mata Kuliah') }}
                                    </a>
                                    <a href="{{ route('admin.user.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.user.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                                        {{ __('Kelola User') }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Penyusunan Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" 
                                    class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:border-gray-300 border-b-2 border-transparent transition-colors duration-200 {{ request()->routeIs(['admin.penyusun.*', 'admin.reviewer.*', 'admin.tahap-penyusunan.*', 'admin.modul.*']) ? 'border-green-500 text-gray-900' : '' }}">
                                {{ __('Penyusunan') }}
                                @isset($navbarNotifications)
                                    @php 
                                        $revPending = \App\Models\ReviewerApplication::where('status', 'pending')->count();
                                        $pendingSum = ($navbarNotifications['pendingApplications'] ?? 0) + ($navbarNotifications['modulDalamProses'] ?? 0) + $revPending; 
                                    @endphp
                                    @if($pendingSum > 0)
                                        <span class="ml-2 inline-flex items-center justify-center rounded-full bg-orange-100 text-orange-800 text-xs font-medium px-2 py-0.5">{{ $pendingSum }}</span>
                                    @endif
                                @endisset
                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" 
                                 class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                                <div class="py-1">
                                    <a href="{{ route('admin.penyusun.index') }}" class="flex items-center justify-between px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.penyusun.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                                        {{ __('Kelola Penyusun') }}
                                        @isset($navbarNotifications)
                                            @if(($navbarNotifications['pendingApplications'] ?? 0) > 0)
                                                <span class="ml-2 inline-flex items-center justify-center rounded-full bg-orange-100 text-orange-800 text-xs font-medium px-2 py-0.5">{{ $navbarNotifications['pendingApplications'] }}</span>
                                            @endif
                                        @endisset
                                    </a>
                                    <a href="{{ route('admin.reviewer.index') }}" class="flex items-center justify-between px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.reviewer.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                                        {{ __('Kelola Reviewer') }}
                                        @php $revPending = \App\Models\ReviewerApplication::where('status', 'pending')->count(); @endphp
                                        @if($revPending > 0)
                                            <span class="ml-2 inline-flex items-center justify-center rounded-full bg-orange-100 text-orange-800 text-xs font-medium px-2 py-0.5">{{ $revPending }}</span>
                                        @endif
                                    </a>
                                    <a href="{{ route('admin.tahap-penyusunan.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.tahap-penyusunan.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                                        {{ __('Periode Penyusunan') }}
                                    </a>
                                    <a href="{{ route('admin.modul.index') }}" class="flex items-center justify-between px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.modul.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                                        {{ __('Penyusunan Modul') }}
                                        @isset($navbarNotifications)
                                            @if(($navbarNotifications['modulDalamProses'] ?? 0) > 0)
                                                <span class="ml-2 inline-flex items-center justify-center rounded-full bg-orange-100 text-orange-800 text-xs font-medium px-2 py-0.5">{{ $navbarNotifications['modulDalamProses'] }}</span>
                                            @endif
                                        @endisset
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Publikasi Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" 
                                    class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:border-gray-300 border-b-2 border-transparent transition-colors duration-200 {{ request()->routeIs(['admin.final-draft.*', 'admin.publication.*']) ? 'border-green-500 text-gray-900' : '' }}">
                                {{ __('Publikasi') }}
                                @isset($navbarNotifications)
                                    @php $pendingSum = ($navbarNotifications['finalModulPending'] ?? 0) + ($navbarNotifications['publikasiModulPending'] ?? 0); @endphp
                                    @if($pendingSum > 0)
                                        <span class="ml-2 inline-flex items-center justify-center rounded-full bg-orange-100 text-orange-800 text-xs font-medium px-2 py-0.5">{{ $pendingSum }}</span>
                                    @endif
                                @endisset
                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" 
                                 class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                                <div class="py-1">
                                    <a href="{{ route('admin.final-draft.index') }}" class="flex items-center justify-between px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.final-draft.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                                        {{ __('Final Draft') }}
                                        @isset($navbarNotifications)
                                            @if(($navbarNotifications['finalModulPending'] ?? 0) > 0)
                                                <span class="ml-2 inline-flex items-center justify-center rounded-full bg-orange-100 text-orange-800 text-xs font-medium px-2 py-0.5">{{ $navbarNotifications['finalModulPending'] }}</span>
                                            @endif
                                        @endisset
                                    </a>
                                    <a href="{{ route('admin.publication.index') }}" class="flex items-center justify-between px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.publication.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                                        {{ __('Publikasi') }}
                                        @isset($navbarNotifications)
                                            @if(($navbarNotifications['publikasiModulPending'] ?? 0) > 0)
                                                <span class="ml-2 inline-flex items-center justify-center rounded-full bg-orange-100 text-orange-800 text-xs font-medium px-2 py-0.5">{{ $navbarNotifications['publikasiModulPending'] }}</span>
                                            @endif
                                        @endisset
                                    </a>
                                </div>
                            </div>
                        </div>

                    @endif
                    
                    @if(auth()->user()->is_penyusun)
                        <x-nav-link :href="route('penyusun.modul.index')" :active="request()->routeIs('penyusun.modul.*')">
                            {{ __('Penyusunan Modul') }}
                            @isset($navbarNotifications)
                                @if(($navbarNotifications['penyusunUploadModulAvailable'] ?? 0) > 0)
                                    <span class="ml-2 inline-flex items-center justify-center rounded-full bg-orange-100 text-orange-800 text-[10px] font-medium px-1.5 py-0.5">{{ $navbarNotifications['penyusunUploadModulAvailable'] }}</span>
                                @endif
                            @endisset
                        </x-nav-link>
                        <x-nav-link :href="route('penyusun.final-draft.index')" :active="request()->routeIs('penyusun.final-draft.*')">
                            {{ __('Final Draft') }}
                            @isset($navbarNotifications)
                                @if(($navbarNotifications['penyusunFinalDraftReady'] ?? 0) > 0)
                                    <span class="ml-2 inline-flex items-center justify-center rounded-full bg-orange-100 text-orange-800 text-[10px] font-medium px-1.5 py-0.5">{{ $navbarNotifications['penyusunFinalDraftReady'] }}</span>
                                @endif
                            @endisset
                        </x-nav-link>
                        <x-nav-link :href="route('penyusun.publication.index')" :active="request()->routeIs('penyusun.publication.*')">
                            {{ __('Publikasi') }}
                            @isset($navbarNotifications)
                                @if(($navbarNotifications['penyusunPublicationReady'] ?? 0) > 0)
                                    <span class="ml-2 inline-flex items-center justify-center rounded-full bg-orange-100 text-orange-800 text-[10px] font-medium px-1.5 py-0.5">{{ $navbarNotifications['penyusunPublicationReady'] }}</span>
                                @endif
                            @endisset
                        </x-nav-link>
                    @endif
                    
                    @if(auth()->user()->is_lpm)
                        <x-nav-link :href="route('lpm.monitoring')" :active="request()->routeIs('lpm.monitoring')">
                            {{ __('Monitoring') }}
                        </x-nav-link>
                        <x-nav-link :href="route('lpm.final-draft.index')" :active="request()->routeIs('lpm.final-draft.*')">
                            {{ __('Final Draft') }}
                        </x-nav-link>
                    @endif

                    @if(auth()->user()->is_reviewer)
                        <x-nav-link :href="route('reviewer.final-draft.index')" :active="request()->routeIs('reviewer.final-draft.*')">
                            {{ __('Review Final Draft') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Notification Bell + Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @isset($navbarNotifications)
                    <div class="relative mr-4" x-data="{ openNotif: false }">
                        <button @click="openNotif = !openNotif" @click.away="openNotif = false" class="relative rounded-full p-2 hover:bg-green-50 transition" title="Notifikasi" aria-label="Notifikasi">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-green-700">
                                <path fill-rule="evenodd" d="M5.25 8.25A6.75 6.75 0 0 1 12 1.5a6.75 6.75 0 0 1 6.75 6.75v3.318c0 .343.137.672.38.915l1.72 1.72a.75.75 0 0 1-.53 1.28H3.68a.75.75 0 0 1-.53-1.28l1.72-1.72c.243-.243.38-.572.38-.915V8.25Zm6.75 12a3 3 0 0 1-3-3h6a3 3 0 0 1-3 3Z" clip-rule="evenodd"/>
                            </svg>
                            @if(($navbarNotifications['total'] ?? 0) > 0)
                                <span class="absolute -top-0.5 -right-0.5 inline-flex items-center justify-center rounded-full bg-red-600 text-white text-[10px] font-bold px-1.5 py-0.5 min-w-[18px]">{{ $navbarNotifications['total'] }}</span>
                            @endif
                        </button>
                        <div x-show="openNotif" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1" class="absolute right-0 mt-2 w-80 max-w-xs bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50 overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <div class="text-sm font-semibold text-gray-900">Notifikasi</div>
                                <div class="text-xs text-gray-500">Ringkasan tindakan yang menunggu</div>
                            </div>
                            @php $items = $navbarNotificationItems ?? []; @endphp
                            @if(empty($items) || collect($items)->sum('count') === 0)
                                <div class="px-4 py-4 text-sm text-gray-600">Tidak ada notifikasi.</div>
                            @else
                                <ul class="max-h-96 overflow-auto divide-y divide-gray-100">
                                    @foreach($items as $item)
                                        @if(($item['count'] ?? 0) > 0)
                                            <li>
                                                <a href="{{ $item['url'] ?? '#' }}" class="flex items-start justify-between px-4 py-3 hover:bg-gray-50">
                                                    <div class="text-sm text-gray-800">{{ $item['label'] }}</div>
                                                    <span class="ml-3 inline-flex items-center justify-center rounded-full bg-orange-100 text-orange-800 text-xs font-medium px-2 py-0.5">{{ $item['count'] }}</span>
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                @endisset
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 rounded-full bg-green-50 text-green-700 ring-1 ring-green-200 hover:bg-green-100 transition text-sm font-medium">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-2">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-green-600 hover:text-green-700 hover:bg-green-50 focus:outline-none focus:bg-green-50 focus:text-green-700 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-green-100 shadow-2xl relative z-50 backdrop-blur-sm">
        <div class="pt-2 pb-3 space-y-1 px-6">
            <x-responsive-nav-link :href="route(auth()->user()->getDashboardRoute())" :active="request()->routeIs(['dashboard', 'admin.dashboard', 'penyusun.dashboard', 'lpm.dashboard'])">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            
            @if(auth()->user()->is_admin)
                <!-- Kelola Data Section -->
                <div class="px-3 py-2">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('Kelola Data') }}</div>
                </div>
                <x-responsive-nav-link :href="route('admin.jurusan.index')" :active="request()->routeIs('admin.jurusan.*')">
                    {{ __('Kelola Jurusan') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.mata-kuliah.index')" :active="request()->routeIs('admin.mata-kuliah.*')">
                    {{ __('Kelola Mata Kuliah') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.user.index')" :active="request()->routeIs('admin.user.*')">
                    {{ __('Kelola User') }}
                </x-responsive-nav-link>

                <!-- Penyusunan Section -->
                <div class="px-3 py-2">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('Penyusunan') }}</div>
                </div>
                <x-responsive-nav-link :href="route('admin.penyusun.index')" :active="request()->routeIs('admin.penyusun.*')">
                    {{ __('Kelola Penyusun') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.reviewer.index')" :active="request()->routeIs('admin.reviewer.*')">
                    {{ __('Kelola Reviewer') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.tahap-penyusunan.index')" :active="request()->routeIs('admin.tahap-penyusunan.*')">
                    {{ __('Periode Penyusunan') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.modul.index')" :active="request()->routeIs('admin.modul.*')">
                    {{ __('Penyusunan Modul') }}
                </x-responsive-nav-link>

                <!-- Publikasi Section -->
                <div class="px-3 py-2">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('Publikasi') }}</div>
                </div>
                <x-responsive-nav-link :href="route('admin.final-draft.index')" :active="request()->routeIs('admin.final-draft.*')">
                    {{ __('Final Draft') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.publication.index')" :active="request()->routeIs('admin.publication.*')">
                    {{ __('Publikasi') }}
                </x-responsive-nav-link>
            @endif
            
            @if(auth()->user()->is_penyusun)
                <x-responsive-nav-link :href="route('penyusun.modul.index')" :active="request()->routeIs('penyusun.modul.*')">
                    {{ __('Penyusunan Modul') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('penyusun.final-draft.index')" :active="request()->routeIs('penyusun.final-draft.*')">
                    {{ __('Final Draft') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('penyusun.publication.index')" :active="request()->routeIs('penyusun.publication.*')">
                    {{ __('Publikasi') }}
                </x-responsive-nav-link>
            @endif
            
            @if(auth()->user()->is_lpm)
                <x-responsive-nav-link :href="route('lpm.monitoring')" :active="request()->routeIs('lpm.monitoring')">
                    {{ __('Monitoring') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('lpm.final-draft.index')" :active="request()->routeIs('lpm.final-draft.*')">
                    {{ __('Final Draft') }}
                </x-responsive-nav-link>
            @endif

            @if(auth()->user()->is_reviewer)
                <x-responsive-nav-link :href="route('reviewer.final-draft.index')" :active="request()->routeIs('reviewer.final-draft.*')">
                    {{ __('Review Final Draft') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-green-100 shadow-inner">
            <div class="px-6">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
