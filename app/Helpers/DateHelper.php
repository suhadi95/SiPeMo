<?php

if (!function_exists('formatTanggalIndonesia')) {
    /**
     * Format tanggal ke format Indonesia (dd/mm/yyyy)
     */
    function formatTanggalIndonesia($tanggal, $format = 'd/m/Y')
    {
        if (!$tanggal) {
            return '-';
        }
        
        try {
            $carbon = \Carbon\Carbon::parse($tanggal);
            return $carbon->format($format);
        } catch (\Exception $e) {
            return '-';
        }
    }
}

if (!function_exists('formatTanggalWaktuIndonesia')) {
    /**
     * Format tanggal dan waktu ke format Indonesia (dd/mm/yyyy H:i)
     */
    function formatTanggalWaktuIndonesia($tanggal, $format = 'd/m/Y H:i')
    {
        if (!$tanggal) {
            return '-';
        }
        
        try {
            $carbon = \Carbon\Carbon::parse($tanggal);
            return $carbon->format($format);
        } catch (\Exception $e) {
            return '-';
        }
    }
}

if (!function_exists('formatTanggalLengkapIndonesia')) {
    /**
     * Format tanggal lengkap ke format Indonesia (dd MMMM yyyy)
     */
    function formatTanggalLengkapIndonesia($tanggal, $format = 'd F Y')
    {
        if (!$tanggal) {
            return '-';
        }
        
        try {
            $carbon = \Carbon\Carbon::parse($tanggal);
            return $carbon->format($format);
        } catch (\Exception $e) {
            return '-';
        }
    }
}

if (!function_exists('getNamaBulanIndonesia')) {
    /**
     * Dapatkan nama bulan dalam bahasa Indonesia
     */
    function getNamaBulanIndonesia($bulan)
    {
        $bulanIndonesia = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
        
        return $bulanIndonesia[$bulan] ?? '-';
    }
}

if (!function_exists('getNamaHariIndonesia')) {
    /**
     * Dapatkan nama hari dalam bahasa Indonesia
     */
    function getNamaHariIndonesia($hari)
    {
        $hariIndonesia = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];
        
        return $hariIndonesia[$hari] ?? '-';
    }
}

if (!function_exists('getWaktuIndonesia')) {
    /**
     * Dapatkan waktu saat ini dalam zona waktu Indonesia (WIB)
     */
    function getWaktuIndonesia()
    {
        return \Carbon\Carbon::now('Asia/Jakarta');
    }
}

if (!function_exists('getTanggalIndonesia')) {
    /**
     * Dapatkan tanggal saat ini dalam zona waktu Indonesia (WIB)
     */
    function getTanggalIndonesia()
    {
        return \Carbon\Carbon::now('Asia/Jakarta')->toDateString();
    }
}

if (!function_exists('getTanggalWaktuIndonesia')) {
    /**
     * Dapatkan tanggal dan waktu saat ini dalam zona waktu Indonesia (WIB)
     */
    function getTanggalWaktuIndonesia()
    {
        return \Carbon\Carbon::now('Asia/Jakarta')->toDateTimeString();
    }
}

