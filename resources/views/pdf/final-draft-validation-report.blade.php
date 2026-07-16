<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Validasi Bahan Ajar Modular PJJ</title>
    <style>
        @page {
            margin: 2cm;
        }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111827; line-height: 1.45; margin: 0; }
        .title { text-align: center; margin-bottom: 12px; }
        .title h1 { font-size: 14px; margin: 0; font-weight: 700; }
        .title h2 { font-size: 12px; margin: 2px 0 0; font-weight: 700; }
        .section { margin-top: 14px; }
        .section-title { font-weight: 700; margin-bottom: 6px; }
        .muted { color: #374151; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #6b7280; padding: 5px; vertical-align: top; }
        th { background: #f3f4f6; font-weight: 700; }
        .no-border td { border: 0; padding: 2px 0; }
        .check { font-family: DejaVu Sans, sans-serif; font-weight: bold; }
        .ttd-block { margin-top: 6px; }
        .ttd-image { margin: 8px 0 4px; }
        .ttd-image img { max-height: 80px; max-width: 220px; }
        .ttd-name { font-weight: 700; }
        .small { font-size: 10px; }
        .rekomendasi { border: 1px solid #6b7280; min-height: 45px; padding: 6px; margin-top: 3px; white-space: pre-wrap; }
    </style>
</head>
<body>
    <div class="title">
        <h1>FORM VALIDASI BAHAN AJAR MODULAR PJJ</h1>
        <h2>LP2M UINSSC</h2>
    </div>

    <div class="muted">
        Berikan penilaian (validasi) terhadap bahan ajar dengan skala likert berikut:
    </div>
    <div class="small" style="margin-top: 4px;">
        <strong>Skala Penilaian (Likert 1-5)</strong><br>
        1 = Sangat Tidak Baik / Tidak Sesuai<br>
        2 = Tidak Baik / Kurang Sesuai<br>
        3 = Cukup / Sesuai Sebagian<br>
        4 = Baik / Sesuai<br>
        5 = Sangat Baik / Sangat Sesuai
    </div>

    <div class="section">
        <div class="section-title">A. Informasi Umum Bahan Ajar</div>
        <table class="no-border">
            <tr><td width="35%">Judul Modul</td><td>: {{ $finalDraft->judul_modul }}</td></tr>
            <tr><td>Mata Kuliah</td><td>: {{ $finalDraft->mataKuliah->nama_mata_kuliah ?? '-' }}</td></tr>
            <tr><td>SKS</td><td>: {{ $finalDraft->mataKuliah->sks ?? '-' }}</td></tr>
            <tr><td>Jumlah Bab (Modul)</td><td>: {{ $finalDraft->mataKuliah?->jumlahBab() ?? '-' }}</td></tr>
            <tr><td>Penulis / Pengembang Modul</td><td>: {{ $finalDraft->penyusunApplication->nama_penyusun ?? '-' }}</td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">B. Identitas Validator</div>
        <table class="no-border">
            <tr><td width="35%">Nama Validator</td><td>: {{ $review->reviewer->name ?? '-' }}</td></tr>
            <tr><td>Institusi / Unit Kerja</td><td>: {{ $review->validator_institusi ?? '-' }}</td></tr>
            <tr><td>Bidang Keahlian</td><td>: {{ $review->validator_bidang_keahlian ?? '-' }}</td></tr>
            <tr><td>Email / Kontak</td><td>: {{ $review->validator_email_kontak ?? '-' }}</td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">C. Aspek yang Dinilai</div>
        @php $grouped = $review->answersGroupedByAspek(); @endphp
        @foreach($grouped as $aspekNama => $answers)
            <div style="margin-bottom: 8px;"><strong>{{ $loop->iteration }}. {{ $aspekNama }}</strong></div>
            <table style="margin-bottom: 10px;">
                <thead>
                    <tr>
                        <th width="6%">No</th>
                        <th width="52%">Indikator</th>
                        <th width="12%">Skor (1-5)</th>
                        <th width="30%">Catatan / Saran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($answers as $answer)
                        <tr>
                            <td style="text-align: center;">{{ $loop->iteration }}</td>
                            <td>{{ $answer->teks_pertanyaan }}</td>
                            <td style="text-align: center;">{{ $answer->skor }}</td>
                            <td>{{ $answer->catatan ?: '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    </div>

    <div class="section">
        <div class="section-title">D. Penilaian Umum</div>
        <div style="margin-bottom: 6px;"><strong>1. Kelayakan Modul</strong></div>
        @foreach($hasilOptions as $key => $label)
            <div style="margin-bottom: 3px;">
                <span class="check">{{ $review->hasil_penilaian === $key ? '[x]' : '[ ]' }}</span> {{ $label }}
            </div>
        @endforeach

        <div style="margin-top: 8px;"><strong>2. Rekomendasi Validator:</strong></div>
        <div class="rekomendasi">{{ $review->rekomendasi_validator ?: ($review->catatan_revisi ?: '-') }}</div>
    </div>

    <div class="section">
        <div class="section-title">E. Tanda Tangan Validator</div>
        <table class="no-border">
            <tr><td width="35%">Nama</td><td>: {{ $review->reviewer->name ?? '-' }}</td></tr>
            <tr><td>Tanggal</td><td>: {{ optional($review->validator_report_completed_at ?? $review->submitted_at)->format('d-m-Y') }}</td></tr>
        </table>
        <div class="ttd-block">
            <div>Tanda Tangan :</div>
            <div class="ttd-image">
                @if($signatureDataUri = $review->signatureDataUri())
                    <img src="{{ $signatureDataUri }}" alt="Tanda Tangan">
                @endif
            </div>
        </div>
    </div>
</body>
</html>
