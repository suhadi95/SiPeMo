<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Penilaian Reviewer</title>
    <style>
        @page {
            margin: 2cm 1.5cm;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #111827;
            line-height: 1.45;
            margin: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #1f2937;
            padding-bottom: 8px;
            margin-bottom: 14px;
        }
        .header h1 {
            font-size: 14px;
            margin: 0;
            font-weight: 700;
            text-transform: uppercase;
        }
        .header h2 {
            font-size: 11px;
            margin: 3px 0 0;
            font-weight: 600;
            color: #4b5563;
        }
        .section-title {
            font-size: 11px;
            font-weight: 700;
            background: #f3f4f6;
            padding: 4px 6px;
            margin: 12px 0 6px;
            border-left: 3px solid #2563eb;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #9ca3af;
            padding: 5px 6px;
            vertical-align: top;
        }
        th {
            background: #f9fafb;
            font-weight: 700;
            font-size: 9.5px;
        }
        .no-border td {
            border: 0;
            padding: 2.5px 0;
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
        .stat-card {
            display: inline-block;
            width: 30%;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            padding: 6px;
            text-align: center;
            margin-right: 2%;
        }
        .stat-number {
            font-size: 16px;
            font-weight: bold;
            color: #1e40af;
        }
        .stat-label {
            font-size: 8.5px;
            color: #6b7280;
            text-transform: uppercase;
        }
        .ttd-block {
            float: right;
            width: 220px;
            text-align: center;
            margin-top: 25px;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PORTOFOLIO & RIWAYAT REVIEW BAHAN AJAR</h1>
        <h2>Sistem Penjaminan Mutu & Monitoring Modul (SiPeMo)</h2>
    </div>

    <!-- Data Reviewer & Rekening -->
    <div class="section-title">A. Identitas Reviewer & Informasi Rekening</div>
    <table class="no-border" style="margin-bottom: 12px;">
        <tr>
            <td width="22%"><strong>Nama Reviewer</strong></td>
            <td width="2%">:</td>
            <td width="76%"><strong>{{ $application->nama_reviewer }}</strong></td>
        </tr>
        <tr>
            <td><strong>NIDN / NIP</strong></td>
            <td>:</td>
            <td>{{ $application->nidn }} / {{ $application->nip ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Email / WhatsApp</strong></td>
            <td>:</td>
            <td>{{ $application->email }} / {{ $application->no_wa }}</td>
        </tr>
        <tr>
            <td><strong>Rekening Bank</strong></td>
            <td>:</td>
            <td>
                @if($application->nomor_rekening)
                    <strong>{{ $application->nama_bank ?? 'Bank' }}</strong> - {{ $application->nomor_rekening }}
                    @if($application->nama_pemilik_rekening)
                        (a.n. {{ $application->nama_pemilik_rekening }})
                    @endif
                @else
                    <span style="color: #9ca3af; font-style: italic;">Belum diisi</span>
                @endif
            </td>
        </tr>
        <tr>
            <td><strong>Status Pendaftaran</strong></td>
            <td>:</td>
            <td>Disetujui pada {{ $application->approved_at ? $application->approved_at->format('d F Y') : '-' }}</td>
        </tr>
    </table>

    <!-- Statistik Singkat -->
    <div style="margin-bottom: 14px;">
        @php
            $totalReviewed = $reviews->count();
            $sangatLayakCount = $reviews->where('hasil_penilaian', \App\Models\FinalDraftReview::HASIL_SANGAT_LAYAK)->count();
            $perluPerbaikanCount = $reviews->where('hasil_penilaian', '!=', \App\Models\FinalDraftReview::HASIL_SANGAT_LAYAK)->count();
        @endphp
        <div class="stat-card">
            <div class="stat-number">{{ $totalReviewed }}</div>
            <div class="stat-label">Total Modul Direview</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color: #166534;">{{ $sangatLayakCount }}</div>
            <div class="stat-label">Sangat Layak (&ge;80%)</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color: #92400e;">{{ $perluPerbaikanCount }}</div>
            <div class="stat-label">Perlu Perbaikan (&lt;80%)</div>
        </div>
    </div>

    <!-- Tabel Daftar Modul yang Telah Direview -->
    <div class="section-title">B. Daftar Modul yang Telah Ditinjau</div>
    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="28%">Judul Modul & Mata Kuliah</th>
                <th width="18%">Penyusun</th>
                <th width="12%">Tanggal Review</th>
                <th width="12%">Skor Likert</th>
                <th width="12%">Keputusan</th>
                <th width="14%">Catatan / Rekomendasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reviews as $idx => $r)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td>
                        <strong>{{ $r->finalDraft->judul_modul ?? '-' }}</strong>
                        <div style="font-size: 8.5px; color: #4b5563;">
                            {{ $r->finalDraft->mataKuliah->nama_mata_kuliah ?? '-' }}
                            ({{ $r->finalDraft->mataKuliah->jurusan->nama_jurusan ?? '-' }})
                        </div>
                    </td>
                    <td>{{ $r->finalDraft->penyusunApplication->nama_penyusun ?? '-' }}</td>
                    <td style="text-align: center;">{{ $r->submitted_at->format('d/m/Y') }}</td>
                    <td style="text-align: center;">
                        <strong>{{ $r->total_skor }}</strong>/{{ $r->maksimal_skor }}
                        <div style="font-size: 8.5px; color: #2563eb; font-weight: bold;">({{ $r->persentase_skor }}%)</div>
                    </td>
                    <td style="text-align: center;">
                        @if($r->isSangatLayak())
                            <span class="badge badge-success">Sangat Layak</span>
                        @else
                            <span class="badge badge-warning">Perlu Perbaikan</span>
                        @endif
                    </td>
                    <td>
                        <div style="font-size: 8.5px;">
                            {{ Str::limit($r->rekomendasi_validator ?: ($r->catatan_revisi ?: '-'), 90) }}
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #6b7280; padding: 16px;">
                        Belum ada riwayat review yang diselesaikan oleh reviewer ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Tanda Tangan Pengesahan -->
    <div class="ttd-block">
        <div>Dicetak pada: {{ now()->format('d F Y') }}</div>
        <div style="margin-top: 4px;">Mengetahui,</div>
        <div style="font-weight: bold;">Reviewer Bersangkutan</div>
        <div style="height: 55px; padding-top: 10px;">
            @php $latestSignature = $reviews->first(fn($x) => $x->signatureDataUri()); @endphp
            @if($latestSignature && ($uri = $latestSignature->signatureDataUri()))
                <img src="{{ $uri }}" style="max-height: 50px; max-width: 160px;" alt="Tanda Tangan">
            @endif
        </div>
        <div style="font-weight: bold; text-decoration: underline;">{{ $application->nama_reviewer }}</div>
        <div>NIDN: {{ $application->nidn }}</div>
    </div>
    <div class="clear"></div>
</body>
</html>
