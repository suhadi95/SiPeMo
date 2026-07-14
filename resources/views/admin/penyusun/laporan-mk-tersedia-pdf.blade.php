<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Mata Kuliah Tanpa Penyusun</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.4; margin: 0; padding: 0; }
        .header { text-align: center; margin-bottom: 30px; }
        .title { font-size: 18px; font-weight: bold; margin-bottom: 10px; }
        .subtitle { font-size: 14px; margin-bottom: 20px; }
        .summary { background-color: #e9ecef; padding: 15px; margin: 0 20px 20px 20px; border-radius: 5px; }
        .jurusan-section { margin: 0 20px 30px 20px; page-break-inside: avoid; }
        .jurusan-title { font-size: 14px; font-weight: bold; background-color: #0ea5e9; color: #fff; padding: 8px 12px; margin-bottom: 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; vertical-align: top; }
        th { background-color: #f8f9fa; font-weight: bold; font-size: 11px; }
        td { font-size: 10px; }
        .no-col { width: 6%; text-align: center; }
        .kode-col { width: 16%; }
        .nama-col { width: 38%; }
        .semester-col { width: 10%; text-align: center; }
        .sks-col { width: 8%; text-align: center; }
        .footer { margin: 20px; text-align: center; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">LAPORAN MATA KULIAH TANPA PENYUSUN</div>
        <div class="subtitle">Sistem Penyusunan Modul (SiPeMo)</div>
        <div>Tanggal Laporan: {{ $tanggalLaporan }}</div>
    </div>

    <div class="summary">
        <div><strong>Total Mata Kuliah Tersedia:</strong> {{ $totalMataKuliah }}</div>
        <div><strong>Kriteria:</strong> Mata kuliah yang belum memiliki penyusun dengan status approved</div>
    </div>

    @foreach($mkByJurusan as $namaJurusan => $mks)
        <div class="jurusan-section">
            <h3 class="jurusan-title">{{ $namaJurusan }}</h3>
            <table>
                <thead>
                    <tr>
                        <th class="no-col">No</th>
                        <th class="kode-col">Kode</th>
                        <th class="nama-col">Nama Mata Kuliah</th>
                        <th class="semester-col">Semester</th>
                        <th class="sks-col">SKS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mks as $index => $mk)
                        <tr>
                            <td class="no-col">{{ $index + 1 }}</td>
                            <td class="kode-col">{{ $mk->kode_mata_kuliah }}</td>
                            <td class="nama-col">{{ $mk->nama_mata_kuliah }}</td>
                            <td class="semester-col">{{ $mk->semester }}</td>
                            <td class="sks-col">{{ $mk->sks }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

    <div class="footer">
        <p>Laporan ini dibuat otomatis oleh SiPeMo</p>
    </div>
</body>
</html>


