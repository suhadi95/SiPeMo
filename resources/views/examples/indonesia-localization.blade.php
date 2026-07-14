{{-- Contoh penggunaan translation dalam view --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ __('messages.welcome') }}</h1>
    <h2>{{ __('messages.dashboard') }}</h2>
    
    {{-- Contoh penggunaan dengan parameter --}}
    <p>{{ __('messages.success_created') }}</p>
    
    {{-- Contoh penggunaan format tanggal Indonesia --}}
    <div class="date-examples">
        <h3>Contoh Format Tanggal Indonesia:</h3>
        
        {{-- Format tanggal biasa --}}
        <p>Tanggal: {{ formatTanggalIndonesia(now()) }}</p>
        
        {{-- Format tanggal dan waktu --}}
        <p>Tanggal dan Waktu: {{ formatTanggalWaktuIndonesia(now()) }}</p>
        
        {{-- Format tanggal lengkap --}}
        <p>Tanggal Lengkap: {{ formatTanggalLengkapIndonesia(now()) }}</p>
        
        {{-- Nama bulan --}}
        <p>Bulan: {{ getNamaBulanIndonesia(now()->month) }}</p>
        
        {{-- Nama hari --}}
        <p>Hari: {{ getNamaHariIndonesia(now()->dayOfWeek) }}</p>
        
        {{-- Waktu saat ini dalam WIB --}}
        <p>Waktu WIB: {{ getWaktuIndonesia()->format('d/m/Y H:i:s') }}</p>
    </div>
    
    {{-- Contoh penggunaan Carbon dengan locale Indonesia --}}
    <div class="carbon-examples">
        <h3>Contoh Carbon dengan Locale Indonesia:</h3>
        
        {{-- Set locale ke Indonesia --}}
        @php
            \Carbon\Carbon::setLocale('id');
        @endphp
        
        <p>Tanggal dengan Carbon: {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
        <p>Tanggal dengan hari: {{ \Carbon\Carbon::now()->format('l, d F Y') }}</p>
        <p>Waktu relatif: {{ \Carbon\Carbon::now()->diffForHumans() }}</p>
    </div>
    
    {{-- Contoh penggunaan validation messages --}}
    <div class="validation-examples">
        <h3>Contoh Pesan Validasi:</h3>
        
        <p>{{ __('validation.required', ['attribute' => 'nama']) }}</p>
        <p>{{ __('validation.email') }}</p>
        <p>{{ __('validation.min.string', ['min' => 8]) }}</p>
    </div>
    
    {{-- Contoh penggunaan auth messages --}}
    <div class="auth-examples">
        <h3>Contoh Pesan Autentikasi:</h3>
        
        <p>{{ __('auth.failed') }}</p>
        <p>{{ __('auth.password') }}</p>
        <p>{{ __('auth.throttle', ['seconds' => 60]) }}</p>
    </div>
    
    {{-- Contoh penggunaan pagination --}}
    <div class="pagination-examples">
        <h3>Contoh Pagination:</h3>
        
        <p>{{ __('pagination.previous') }}</p>
        <p>{{ __('pagination.next') }}</p>
    </div>
</div>
@endsection

