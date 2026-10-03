<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $judul }}</title>
    <style>
        @page {
            margin: 25px 20px 40px 20px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9px;
            color: #2d3748;
        }

        /* ===== KOP SURAT ===== */
        .kop-surat {
            display: table;
            width: 100%;
            border-bottom: 3px double #1a1a2e;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .kop-logo {
            display: table-cell;
            width: 70px;
            vertical-align: middle;
            text-align: center;
        }
        .kop-logo img {
            width: 60px;
            height: auto;
        }
        .kop-teks {
            display: table-cell;
            vertical-align: middle;
            padding-left: 8px;
        }
        .kop-teks h1 {
            font-size: 15px;
            font-weight: bold;
            color: #1a1a2e;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 1px;
        }
        .kop-teks h2 {
            font-size: 10px;
            font-weight: normal;
            color: #4a5568;
            margin-bottom: 1px;
        }
        .kop-teks p {
            font-size: 8px;
            color: #718096;
        }

        /* ===== JUDUL LAPORAN ===== */
        .judul-laporan {
            text-align: center;
            margin-bottom: 10px;
        }
        .judul-laporan h3 {
            font-size: 12px;
            font-weight: bold;
            color: #1a1a2e;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding-bottom: 4px;
            border-bottom: 1px solid #cbd5e0;
            display: inline-block;
            padding-left: 20px;
            padding-right: 20px;
        }

        /* ===== INFO PERIODE ===== */
        .info-bar {
            background: #f7fafc;
            border-left: 4px solid #667eea;
            padding: 8px 12px;
            margin-bottom: 12px;
            font-size: 9px;
            border-radius: 3px;
        }
        .info-bar table {
            width: 100%;
            border: none;
            margin: 0;
        }
        .info-bar td {
            padding: 2px 0;
            border: none !important;
            background: transparent !important;
            font-size: 9px;
            color: #2d3748;
        }
        .info-bar .label {
            color: #718096;
            width: 80px;
            font-weight: bold;
        }
        .info-bar .value {
            color: #1a1a2e;
            font-weight: bold;
        }

        /* ===== TABEL ===== */
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        table.data thead th {
            background: #1a1a2e;
            color: #ffffff;
            padding: 6px 5px;
            text-align: left;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border: 1px solid #1a1a2e;
        }
        table.data tbody td {
            padding: 6px 5px;
            border: 1px solid #cbd5e0;
            vertical-align: top;
            font-size: 8.5px;
            line-height: 1.4;
        }
        table.data tbody tr:nth-child(even) {
            background: #f7fafc;
        }
        table.data tbody tr:hover {
            background: #edf2f7;
        }

        /* ===== BADGE STATUS ===== */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 7.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .badge-pending {
            background: #fefcbf;
            color: #744210;
            border: 1px solid #ecc94b;
        }
        .badge-selesai {
            background: #c6f6d5;
            color: #22543d;
            border: 1px solid #48bb78;
        }
        .badge-ditolak {
            background: #fed7d7;
            color: #822727;
            border: 1px solid #f56565;
        }

        /* ===== FOTO ===== */
        .foto-cell {
            text-align: center;
        }
        .foto-bukti {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            border: 2px solid #e2e8f0;
        }
        .foto-kosong {
            width: 60px;
            height: 60px;
            background: #edf2f7;
            border-radius: 6px;
            border: 1px dashed #cbd5e0;
            display: inline-block;
            line-height: 60px;
            color: #a0aec0;
            font-size: 8px;
        }

        /* ===== RINGKASAN ===== */
        .ringkasan {
            margin-top: 10px;
            background: #f7fafc;
            border-radius: 6px;
            padding: 10px 14px;
            border: 1px solid #e2e8f0;
        }
        .ringkasan h4 {
            font-size: 9px;
            font-weight: bold;
            color: #1a1a2e;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .ringkasan-grid {
            display: table;
            width: 100%;
        }
        .ringkasan-item {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 6px;
            border-right: 1px solid #e2e8f0;
        }
        .ringkasan-item:last-child {
            border-right: none;
        }
        .ringkasan-item .angka {
            font-size: 14px;
            font-weight: bold;
            color: #1a1a2e;
            display: block;
            margin-bottom: 2px;
        }
        .ringkasan-item .keterangan {
            font-size: 7.5px;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .ringkasan-item.total .angka { color: #2b6cb0; }
        .ringkasan-item.pending .angka { color: #b7791f; }
        .ringkasan-item.selesai .angka { color: #2f855a; }
        .ringkasan-item.ditolak .angka { color: #c53030; }

        /* ===== DICETAK OLEH ===== */
        .dicetak-oleh {
            margin-top: 25px;
            text-align: right;
            padding-right: 20px;
        }
        .dicetak-oleh p {
            font-size: 9px;
            color: #4a5568;
            margin: 2px 0;
        }
        .dicetak-oleh strong {
            color: #1a1a2e;
            font-size: 10px;
        }
        .dicetak-oleh .tanggal {
            font-size: 8px;
            color: #a0aec0;
            font-style: italic;
        }

        /* ===== FOOTER ===== */
        .footer-info {
            position: fixed;
            bottom: -25px;
            left: 0;
            right: 0;
            height: 20px;
            text-align: center;
            font-size: 7.5px;
            color: #a0aec0;
            border-top: 1px solid #e2e8f0;
            padding-top: 3px;
        }
        .footer-info .page:after {
            content: counter(page);
        }
    </style>
</head>
<body>

@php
    $total   = $laporan->count();
    $jmlPending = $laporan->where('status', 'pending')->count();
    $jmlSelesai = $laporan->where('status', 'selesai')->count();
    $jmlDitolak = $laporan->where('status', 'ditolak')->count();

    // Cari logo perusahaan
    $logoPath = public_path('assets/img/logopt.png');
    $logoSrc = file_exists($logoPath)
        ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
        : null;
@endphp

{{-- ============ KOP SURAT ============ --}}
<div class="kop-surat">
    <div class="kop-logo">
        @if($logoSrc)
            <img src="{{ $logoSrc }}" alt="Logo">
        @endif
    </div>
    <div class="kop-teks">
        <h1>PT Waskita Energi</h1>
        <h2>Sistem Manajemen Onderdil & Pemeliharaan</h2>
        <p>Jl. Industri No. 123, Jakarta &nbsp;|&nbsp; Telp: (021) 1234-5678 &nbsp;|&nbsp; info@manajemenonderdil.id</p>
    </div>
</div>

{{-- ============ JUDUL LAPORAN ============ --}}
<div class="judul-laporan">
    <h3>{{ $judul }}</h3>
</div>

{{-- ============ INFO PERIODE ============ --}}
<div class="info-bar">
    <table>
        <tr>
            <td class="label">Periode</td>
            <td class="value">: {{ $periode }}</td>
            <td class="label">Tanggal Cetak</td>
            <td class="value">: {{ date('d F Y, H:i') }} WIB</td>
        </tr>
        <tr>
            <td class="label">Total Data</td>
            <td class="value">: {{ $total }} data</td>
            <td class="label">Dicetak Oleh</td>
            <td class="value">: {{ Auth::user()->name ?? 'Supervisor' }}</td>
        </tr>
    </table>
</div>

{{-- ============ RINGKASAN ============ --}}
<div class="ringkasan">
    <h4>Ringkasan Status</h4>
    <div class="ringkasan-grid">
        <div class="ringkasan-item total">
            <span class="angka">{{ $total }}</span>
            <span class="keterangan">Total</span>
        </div>
        <div class="ringkasan-item pending">
            <span class="angka">{{ $jmlPending }}</span>
            <span class="keterangan">Pending</span>
        </div>
        <div class="ringkasan-item selesai">
            <span class="angka">{{ $jmlSelesai }}</span>
            <span class="keterangan">Selesai</span>
        </div>
        <div class="ringkasan-item ditolak">
            <span class="angka">{{ $jmlDitolak }}</span>
            <span class="keterangan">Ditolak</span>
        </div>
    </div>
</div>

{{-- ============ TABEL DATA ============ --}}
<table class="data">
    <thead>
        <tr>
            <th width="3%">No</th>
            <th width="11%">Judul Pemeliharaan</th>
            <th width="9%">Tenaga Kerja</th>
            <th width="10%">Peralatan</th>
            <th width="7%">Lokasi</th>
            <th width="7%">Tgl Selesai</th>
            <th width="7%">Status</th>
            <th width="12%">Detail Penyelesaian</th>
            <th width="12%">Alasan Penolakan</th>
            <th width="8%">Bukti</th>
        </tr>
    </thead>
    <tbody>
        @foreach($laporan as $i => $item)
        <tr>
            <td style="text-align: center; font-weight: bold;">{{ $i + 1 }}</td>
            <td><strong>{{ $item->judul_pemeliharaan }}</strong></td>
            <td>{{ $item->user ? $item->user->name : '-' }}</td>
            <td>{{ $item->nama_peralatan }}</td>
            <td>{{ $item->lokasi ?? '-' }}</td>
            <td>{{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') }}</td>
            <td style="text-align: center;">
                @if($item->status == 'selesai')
                    <span class="badge badge-selesai">✓ Selesai</span>
                @elseif($item->status == 'ditolak')
                    <span class="badge badge-ditolak">✗ Ditolak</span>
                @else
                    <span class="badge badge-pending">⏳ Pending</span>
                @endif
            </td>
            <td>{{ $item->detail_penyelesaian ?? '-' }}</td>
            <td>{{ $item->alasan_penolakan ?? '-' }}</td>
            <td class="foto-cell">
                @php
                    $fotoPath = null;
                    if ($item->status == 'selesai' && $item->gambar_bukti) {
                        $fotoPath = public_path($item->gambar_bukti);
                    } elseif ($item->gambar) {
                        $fotoPath = public_path($item->gambar);
                    }
                @endphp

                @if($fotoPath && file_exists($fotoPath))
                    <img src="data:image/{{ pathinfo($fotoPath, PATHINFO_EXTENSION) }};base64,{{ base64_encode(file_get_contents($fotoPath)) }}"
                         alt="Bukti"
                         class="foto-bukti">
                @else
                    <span class="foto-kosong">No Image</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- ============ DICETAK OLEH ============ --}}
<div class="dicetak-oleh">
    <p>Dicetak oleh: <strong>{{ Auth::user()->name ?? 'Supervisor' }}</strong></p>
    <p class="tanggal">{{ date('d F Y, H:i') }} WIB</p>
</div>

{{-- ============ FOOTER ============ --}}
<div class="footer-info">
    Laporan Pemeliharaan — PT Waskita Energi &nbsp;|&nbsp; Halaman <span class="page"></span>
</div>

</body>
</html>