<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Akhir Monitoring Final Draft Modul</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.35;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #4f46e5;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 6px;
            color: #1e293b;
        }
        .subtitle {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 8px;
        }
        .meta {
            font-size: 10px;
            color: #64748b;
        }
        .summary-box {
            background-color: #f1f5f9;
            border-left: 4px solid #4f46e5;
            padding: 12px 14px;
            margin-bottom: 20px;
        }
        .summary-title {
            font-weight: bold;
            margin-bottom: 8px;
            color: #334155;
        }
        .summary-text {
            margin: 0;
            color: #475569;
        }
        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 22px;
        }
        .stats-table th,
        .stats-table td {
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            text-align: left;
        }
        .stats-table th {
            background-color: #e2e8f0;
            font-weight: bold;
            font-size: 10px;
        }
        .stats-table td {
            font-size: 10px;
        }
        .jurusan-section {
            margin-bottom: 22px;
            page-break-inside: avoid;
        }
        .jurusan-title {
            font-size: 12px;
            font-weight: bold;
            background-color: #4f46e5;
            color: white;
            padding: 6px 10px;
            margin-bottom: 0;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            font-size: 9px;
        }
        table.data-table th,
        table.data-table td {
            border: 1px solid #cbd5e1;
            padding: 5px 6px;
            text-align: left;
            vertical-align: top;
        }
        table.data-table th {
            background-color: #e2e8f0;
            font-weight: bold;
        }
        .no-col { width: 4%; text-align: center; }
        .nama-col { width: 15%; }
        .judul-col { width: 22%; }
        .matkul-col { width: 16%; }
        .status-col { width: 14%; }
        .upload-col { width: 14%; }
        .validasi-col { width: 15%; }
        .status-approved { color: #15803d; font-weight: bold; }
        .status-rejected { color: #b91c1c; font-weight: bold; }
        .status-pending { color: #a16207; font-weight: bold; }
        .status-belum { color: #64748b; font-weight: bold; }
        .rekomendasi-section {
            margin-top: 22px;
            padding: 12px;
            background-color: #fefce8;
            border: 1px solid #eab308;
        }
        .rekomendasi-title {
            font-weight: bold;
            margin-bottom: 6px;
            color: #854d0e;
        }
        .rekomendasi-text {
            font-size: 10px;
            color: #713f12;
            margin: 0;
        }
        .footer {
            margin-top: 25px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 9px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">LAPORAN AKHIR MONITORING FINAL DRAFT MODUL</div>
        <div class="subtitle">Sistem Penyusunan Modul (SiPeMo)</div>
        <div class="meta">Tanggal cetak: {{ $tanggalCetak }}</div>
    </div>

    <div class="summary-box">
        <div class="summary-title">Ringkasan Eksekutif</div>
        <p class="summary-text">
            Pada periode ini tercatat <strong>{{ $total }}</strong> pengusul (penyusun) yang sudah disetujui. Sebanyak <strong>{{ $approved }}</strong> final draft disetujui LPM,
            <strong>{{ $rejected }}</strong> ditolak LPM, <strong>{{ $pending }}</strong> masih dalam proses (Reviewer/LPM), dan <strong>{{ $belumUpload }}</strong> belum upload final draft.
            @if($total > 0)
                Persentase final draft disetujui (dari pengusul): <strong>{{ round($approved / $total * 100, 1) }}%</strong>.
            @endif
        </p>
    </div>

    <table class="stats-table">
        <thead>
            <tr>
                <th>Total Pengusul Disetujui</th>
                <th>FD Disetujui</th>
                <th>FD Ditolak</th>
                <th>Menunggu Proses</th>
                <th>Belum Upload</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $total }}</td>
                <td>{{ $approved }}</td>
                <td>{{ $rejected }}</td>
                <td>{{ $pending }}</td>
                <td>{{ $belumUpload }}</td>
            </tr>
        </tbody>
    </table>

    @if($byJurusan->isNotEmpty())
        @foreach($byJurusan as $namaJurusan => $items)
            <div class="jurusan-section">
                <h3 class="jurusan-title">{{ $namaJurusan }}</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="no-col">No</th>
                            <th class="nama-col">Nama Penyusun</th>
                            <th class="judul-col">Judul Modul</th>
                            <th class="matkul-col">Mata Kuliah</th>
                            <th class="status-col">Status FD</th>
                            <th class="upload-col">Tgl Upload</th>
                            <th class="validasi-col">Tgl Validasi LPM</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $index => $penyusun)
                            @php
                                $fd = $penyusun->finalDrafts->first();
                            @endphp
                            <tr>
                                <td class="no-col">{{ $index + 1 }}</td>
                                <td class="nama-col">{{ $penyusun->nama_penyusun }}</td>
                                <td class="judul-col">{{ $fd ? $fd->judul_modul : ($penyusun->judul_bahan_ajar ?? '-') }}</td>
                                <td class="matkul-col">{{ $penyusun->mataKuliah->nama_mata_kuliah ?? '-' }} (Smt {{ $penyusun->mataKuliah->semester ?? '-' }})</td>
                                <td class="status-col">
                                    @if($fd)
                                        @if($fd->status === 'approved')
                                            <span class="status-approved">{{ $fd->statusLabel() }}</span>
                                        @elseif(in_array($fd->status, ['rejected', 'rejected_by_reviewer'], true))
                                            <span class="status-rejected">{{ $fd->statusLabel() }}</span>
                                        @else
                                            <span class="status-pending">{{ $fd->statusLabel() }}</span>
                                        @endif
                                    @else
                                        <span class="status-belum">Belum Upload</span>
                                    @endif
                                </td>
                                <td class="upload-col">{{ $fd && $fd->uploaded_at ? $fd->uploaded_at->format('d/m/Y') : '-' }}</td>
                                <td class="validasi-col">{{ $fd && $fd->lpm_validated_at ? $fd->lpm_validated_at->format('d/m/Y') : '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    @else
        <p style="color: #64748b;">Belum ada pengusul yang disetujui.</p>
    @endif

    <div class="rekomendasi-section">
        <div class="rekomendasi-title">Rekomendasi untuk Periode Berikutnya</div>
        <p class="rekomendasi-text">
            Gunakan ruang ini untuk mencatat rekomendasi perbaikan (misalnya pelatihan template, penyesuaian deadline, atau fokus revisi yang sering muncul). Data di atas dapat digunakan sebagai dasar evaluasi sebelum memulai progres penyusunan modul periode berikutnya.
        </p>
    </div>

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh Sistem Penyusunan Modul (SiPeMo).</p>
    </div>
</body>
</html>
