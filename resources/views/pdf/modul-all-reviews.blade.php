<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Hasil Review Modul</title>
    <style>
        @page {
            margin: 1.8cm 1.5cm;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #111827;
            line-height: 1.4;
            margin: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #1f2937;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .header h1 {
            font-size: 13px;
            margin: 0;
            font-weight: 700;
            text-transform: uppercase;
        }
        .header h2 {
            font-size: 11px;
            margin: 2px 0 0;
            font-weight: 600;
            color: #4b5563;
        }
        .section-title {
            font-size: 11px;
            font-weight: 700;
            background: #f3f4f6;
            padding: 4px 6px;
            margin: 10px 0 6px;
            border-left: 3px solid #2563eb;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #9ca3af;
            padding: 4px 6px;
            vertical-align: top;
        }
        th {
            background: #f9fafb;
            font-weight: 700;
            text-align: center;
            font-size: 9.5px;
        }
        .no-border td {
            border: 0;
            padding: 2px 0;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 700;
            font-size: 9px;
        }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .reviewer-card {
            border: 1px solid #d1d5db;
            margin-top: 12px;
            padding: 8px;
            page-break-inside: avoid;
        }
        .ttd-box {
            float: right;
            width: 200px;
            text-align: center;
            margin-top: 10px;
        }
        .ttd-img {
            max-height: 60px;
            max-width: 180px;
            margin: 4px auto;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REKAPITULASI HASIL REVIEW BAHAN AJAR MODULAR</h1>
        <h2>Sistem Penjaminan Mutu & Monitoring Modul (SiPeMo)</h2>
    </div>

    <!-- Informasi Umum Modul -->
    <div class="section-title">A. Informasi Bahan Ajar</div>
    <table class="no-border" style="margin-bottom: 8px;">
        <tr>
            <td width="25%"><strong>Judul Modul</strong></td>
            <td width="2%">:</td>
            <td width="73%">{{ $penyusun->judul_bahan_ajar }}</td>
        </tr>
        <tr>
            <td><strong>Nama Penyusun</strong></td>
            <td>:</td>
            <td>{{ $penyusun->nama_penyusun }} (NIDN: {{ $penyusun->nidn ?? '-' }} / NIP: {{ $penyusun->nip ?? '-' }})</td>
        </tr>
        <tr>
            <td><strong>Mata Kuliah</strong></td>
            <td>:</td>
            <td>{{ $penyusun->mataKuliah->nama_mata_kuliah ?? '-' }} ({{ $penyusun->mataKuliah->sks ?? '-' }} SKS)</td>
        </tr>
        <tr>
            <td><strong>Jurusan / Semester</strong></td>
            <td>:</td>
            <td>{{ $penyusun->mataKuliah->jurusan->nama_jurusan ?? $penyusun->jurusan }} / Semester {{ $penyusun->semester }}</td>
        </tr>
        <tr>
            <td><strong>Kode Draft</strong></td>
            <td>:</td>
            <td>FD-{{ str_pad($finalDraft->id, 5, '0', STR_PAD_LEFT) }} (Diunggah: {{ $finalDraft->uploaded_at->format('d F Y') }})</td>
        </tr>
    </table>

    <!-- Ringkasan Hasil Seluruh Reviewer -->
    <div class="section-title">B. Ringkasan Evaluasi Seluruh Reviewer</div>
    <table style="margin-bottom: 12px;">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Nama Reviewer</th>
                <th width="15%">Tanggal Review</th>
                <th width="15%">Total Skor</th>
                <th width="15%">Persentase</th>
                <th width="20%">Hasil Keputusan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reviews as $idx => $rev)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td>
                        <strong>{{ $rev->reviewer->name ?? 'Reviewer' }}</strong>
                        <div style="font-size: 8.5px; color: #6b7280;">{{ $rev->validator_institusi ?? '-' }}</div>
                    </td>
                    <td style="text-align: center;">{{ $rev->submitted_at->format('d M Y') }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ $rev->total_skor }} / {{ $rev->maksimal_skor }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ $rev->persentase_skor }}%</td>
                    <td style="text-align: center;">
                        @if($rev->isSangatLayak())
                            <span class="badge badge-success">Sangat Layak</span>
                        @else
                            <span class="badge badge-warning">Perlu Perbaikan</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Rincian Penilaian per Reviewer -->
    <div class="section-title">C. Rincian Penilaian dan Catatan Reviewer</div>

    @foreach($reviews as $index => $rev)
        <div class="reviewer-card">
            <div style="font-weight: bold; font-size: 11px; margin-bottom: 4px; color: #1e40af;">
                Reviewer {{ $index + 1 }}: {{ $rev->reviewer->name ?? 'Reviewer' }}
                @if($rev->validator_institusi)
                    <span style="font-size: 9.5px; font-weight: normal; color: #4b5563;">({{ $rev->validator_institusi }})</span>
                @endif
            </div>

            <!-- Tabel Aspek & Skor -->
            @php $grouped = $rev->answersGroupedByAspek(); @endphp
            @foreach($grouped as $aspekNama => $answers)
                <div style="font-weight: bold; margin: 6px 0 2px;">{{ $loop->iteration }}. {{ $aspekNama }}</div>
                <table style="margin-bottom: 6px;">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="55%">Indikator Penilaian</th>
                            <th width="10%">Skor (1-5)</th>
                            <th width="30%">Catatan / Saran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($answers as $ans)
                            <tr>
                                <td style="text-align: center;">{{ $loop->iteration }}</td>
                                <td>{{ $ans->teks_pertanyaan }}</td>
                                <td style="text-align: center; font-weight: bold;">{{ $ans->skor }}</td>
                                <td>{{ $ans->catatan ?: '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach

            <!-- Catatan Revisi & Rekomendasi -->
            @if($rev->catatan_revisi)
                <div style="margin-top: 6px; padding: 4px 6px; background: #fffbeb; border: 1px solid #fde68a;">
                    <strong>Catatan Revisi Reviewer:</strong>
                    <div style="margin-top: 2px;">{{ $rev->catatan_revisi }}</div>
                </div>
            @endif

            @if($rev->rekomendasi_validator)
                <div style="margin-top: 6px; padding: 4px 6px; background: #f0fdf4; border: 1px solid #bbf7d0;">
                    <strong>Rekomendasi Validator:</strong>
                    <div style="margin-top: 2px;">{{ $rev->rekomendasi_validator }}</div>
                </div>
            @endif

            <!-- Tanda Tangan Reviewer -->
            <div class="ttd-box">
                <div>Tanggal: {{ ($rev->validator_report_completed_at ?? $rev->submitted_at)->format('d F Y') }}</div>
                <div>Validator Reviewer,</div>
                <div style="height: 60px;">
                    @if($uri = $rev->signatureDataUri())
                        <img src="{{ $uri }}" class="ttd-img" alt="Tanda Tangan">
                    @else
                        <div style="padding-top: 25px; color: #9ca3af; font-style: italic;">(Tanda tangan digital)</div>
                    @endif
                </div>
                <div style="font-weight: bold; text-decoration: underline;">{{ $rev->reviewer->name ?? 'Reviewer' }}</div>
                @if($rev->validator_bidang_keahlian)
                    <div style="font-size: 8.5px; color: #6b7280;">Keahlian: {{ $rev->validator_bidang_keahlian }}</div>
                @endif
            </div>
            <div class="clear"></div>
        </div>
    @endforeach

</body>
</html>
