<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Validasi Penyusun</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .subtitle {
            font-size: 14px;
            margin-bottom: 20px;
        }
        
        .info-section {
            margin-bottom: 25px;
            padding: 15px;
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
        }
        
        .info-title {
            font-weight: bold;
            margin-bottom: 10px;
            color: #007bff;
        }
        
        .info-content {
            margin-bottom: 8px;
        }
        
        .jurusan-section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        
        .jurusan-title {
            font-size: 14px;
            font-weight: bold;
            background-color: #007bff;
            color: white;
            padding: 8px 12px;
            margin-bottom: 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 11px;
        }
        
        td {
            font-size: 10px;
        }
        
        .no-col {
            width: 5%;
            text-align: center;
        }
        
        .nama-col {
            width: 23%;
        }
        
        .judul-col {
            width: 28%;
        }
        
        .matkul-col {
            width: 23%;
        }
        
        .semester-col {
            width: 11%;
            text-align: center;
        }
        
        .sks-col {
            width: 10%;
            text-align: center;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        
        .summary {
            background-color: #e9ecef;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .summary-title {
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">LAPORAN VALIDASI PENYUSUN BAHAN AJAR</div>
        <div class="subtitle">Sistem Penyusunan Modul (SiPeMo)</div>
        <div>Tanggal Laporan: {{ $tanggalLaporan }}</div>
    </div>

    <div class="info-section">
        <div class="info-title">Cara Login ke Sistem</div>
        <div class="info-content">1. Gunakan email yang didaftarkan pada saat pendaftaran</div>
        <div class="info-content">2. Password menggunakan NUPT pada saat pendaftaran</div>
    </div>

    @foreach($penyusunsByJurusan as $namaJurusan => $penyusuns)
        <div class="jurusan-section">
            <h3 class="jurusan-title">{{ $namaJurusan }}</h3>
            
            @if(isset($statistikPerJurusan[$namaJurusan]))
            <div class="summary" style="margin-top: 10px; margin-bottom: 15px;">
                <div class="summary-title">Statistik {{ $namaJurusan }}</div>
                <div style="display: inline-block; margin-right: 30px;">
                    <strong>Total Penyusun:</strong> {{ $statistikPerJurusan[$namaJurusan]['total_penyusun'] }}
                </div>
                <div style="display: inline-block;">
                    <strong>Total SKS:</strong> {{ $statistikPerJurusan[$namaJurusan]['total_sks'] }}
                </div>
            </div>
            @endif
            
            <table>
                <thead>
                    <tr>
                        <th class="no-col">No</th>
                        <th class="nama-col">Nama Penyusun</th>
                        <th class="judul-col">Judul Bahan Ajar</th>
                        <th class="matkul-col">Mata Kuliah</th>
                        <th class="semester-col">Semester</th>
                        <th class="sks-col">SKS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penyusuns as $index => $penyusun)
                        <tr>
                            <td class="no-col">{{ $index + 1 }}</td>
                            <td class="nama-col">{{ $penyusun->nama_penyusun }}</td>
                            <td class="judul-col">{{ $penyusun->judul_bahan_ajar }}</td>
                            <td class="matkul-col">{{ $penyusun->mata_kuliah }}</td>
                            <td class="semester-col">{{ $penyusun->semester }}</td>
                            <td class="sks-col">{{ $penyusun->mataKuliah->sks ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh Sistem Penyusunan Modul (SiPeMo)</p>
        <p>Untuk pertanyaan lebih lanjut, silakan hubungi UPT PJJ</p>
    </div>
</body>
</html>
