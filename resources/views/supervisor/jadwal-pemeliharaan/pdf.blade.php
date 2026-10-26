<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $judul }}</title>
    <style>
        @page { margin: 25px 20px 40px 20px; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 9px; color: #2d3748; }
        
        .kop-surat { display: table; width: 100%; border-bottom: 3px double #1a1a2e; padding-bottom: 8px; margin-bottom: 12px; }
        .kop-teks { display: table-cell; vertical-align: middle; }
        .kop-teks h1 { font-size: 15px; font-weight: bold; color: #1a1a2e; text-transform: uppercase; margin-bottom: 1px; }
        .kop-teks h2 { font-size: 10px; font-weight: normal; color: #4a5568; margin-bottom: 1px; }
        .kop-teks p { font-size: 8px; color: #718096; }

        .judul-laporan { text-align: center; margin-bottom: 10px; }
        .judul-laporan h3 { font-size: 12px; font-weight: bold; color: #1a1a2e; text-transform: uppercase; padding-bottom: 4px; border-bottom: 1px solid #cbd5e0; display: inline-block; padding-left: 20px; padding-right: 20px; }

        .info-bar { background: #f7fafc; border-left: 4px solid #667eea; padding: 8px 12px; margin-bottom: 12px; font-size: 9px; }
        .info-bar table { width: 100%; border: none; margin: 0; }
        .info-bar td { padding: 2px 0; border: none !important; background: transparent !important; font-size: 9px; color: #2d3748; }
        .info-bar .label { color: #718096; width: 80px; font-weight: bold; }
        .info-bar .value { color: #1a1a2e; font-weight: bold; }

        table.data { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        table.data thead th { background: #1a1a2e; color: #ffffff; padding: 6px 5px; text-align: left; font-size: 8px; text-transform: uppercase; border: 1px solid #1a1a2e; }
        table.data tbody td { padding: 6px 5px; border: 1px solid #cbd5e0; vertical-align: top; font-size: 8.5px; line-height: 1.4; }
        table.data tbody tr:nth-child(even) { background: #f7fafc; }

        .badge { display: inline-block; padding: 3px 8px; border-radius: 10px; font-size: 7.5px; font-weight: bold; text-transform: uppercase; }
        .badge-pending { background: #fefcbf; color: #744210; border: 1px solid #ecc94b; }
        .badge-selesai { background: #c6f6d5; color: #22543d; border: 1px solid #48bb78; }
        .badge-ditolak { background: #fed7d7; color: #822727; border: 1px solid #f56565; }
        .badge-menunggu { background: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd; }

        .img-pdf { width: 40px; height: 40px; object-fit: cover; border-radius: 4px; border: 1px solid #e2e8f0; }

        .dicetak-oleh { margin-top: 25px; text-align: right; padding-right: 20px; }
        .dicetak-oleh p { font-size: 9px; color: #4a5568; margin: 2px 0; }
        .dicetak-oleh strong { color: #1a1a2e; font-size: 10px; }
        .dicetak-oleh .tanggal { font-size: 8px; color: #a0aec0; font-style: italic; }

        .footer-info { position: fixed; bottom: -25px; left: 0; right: 0; height: 20px; text-align: center; font-size: 7.5px; color: #a0aec0; border-top: 1px solid #e2e8f0; padding-top: 3px; }
        .footer-info .page:after { content: counter(page); }
    </style>
</head>
<body>

@php
    $total   = $jadwal->count();
    $jmlPending = $jadwal->where('status', 'pending')->count();
    $jmlMenunggu = $jadwal->where('status', 'menunggu_verifikasi')->count();
    $jmlSelesai = $jadwal->where('status', 'selesai')->count();
    $jmlDitolak = $jadwal->where('status', 'ditolak')->count();
@endphp

<div class="kop-surat">
    <div class="kop-teks">
        <h1>PLTM Sangir Hulu (PT Waskita Energi)</h1>
        <h2>Sistem Manajemen Pemeliharan & Onderdil</h2>
        <p>Jorong Letter W, Nagari Lubuk Gadang Tim., Kec. Sangir, Kabupaten Solok Selatan, Prov. Sumatera Barat &nbsp;|&nbsp; <a href="mailto:adminpemeliharaan.pltmsh@gmail.com">adminpemeliharaan.pltmsh@gmail.com</a> </p>
    </div>
</div>

<div class="judul-laporan">
    <h3>{{ $judul }}</h3>
</div>

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

<table class="data">
    <thead>
        <tr>
            <th width="3%">No</th>
            <th width="6%">Gambar</th>
            <th width="10%">Judul Pemeliharaan</th>
            <th width="7%">Tgl Selesai</th>
            <th width="8%">Tenaga Kerja</th>
            <th width="8%">Peralatan</th>
            <th width="6%">Lokasi</th>
            <th width="7%">Status</th>
            <th width="8%">Nama Onderdil</th>
            <th width="5%">Jumlah Pakai</th>
            <th width="10%">Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($jadwal as $i => $item)
        <tr>
            <td style="text-align: center;">{{ $i + 1 }}</td>
            <td style="text-align: center;">
                @if($item->gambar && file_exists(public_path($item->gambar)))
                    @php
                        $path = public_path($item->gambar);
                        $type = pathinfo($path, PATHINFO_EXTENSION);
                        $data = file_get_contents($path);
                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    @endphp
                    <img src="{{ $base64 }}" class="img-pdf" alt="Gambar">
                @else
                    <span style="color: #999; font-size: 7px;">No Image</span>
                @endif
            </td>
            <td><strong>{{ $item->judul_pemeliharaan }}</strong></td>
            <td>{{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') }}</td>
            <td>{{ $item->user ? $item->user->name : '-' }}</td>
            <td>{{ $item->nama_peralatan }}</td>
            <td>{{ $item->lokasi ?? '-' }}</td>
            <td style="text-align: center;">
                @if($item->status == 'selesai')
                    <span class="badge badge-selesai">✓ Selesai</span>
                @elseif($item->status == 'ditolak')
                    <span class="badge badge-ditolak">✗ Ditolak</span>
                @elseif($item->status == 'menunggu_verifikasi')
                    <span class="badge badge-menunggu">⏳ Menunggu</span>
                @else
                    <span class="badge badge-pending">⌛ Pending</span>
                @endif
            </td>
            <td>{{ $item->nama_onderdil ?? '-' }}</td>
            <td style="text-align: center;">{{ $item->jumlah_pemakaian ?? '-' }}</td>
            <td>{{ Str::limit($item->keterangan, 40) ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="dicetak-oleh">
    <p>Dicetak oleh: <strong>{{ Auth::user()->name ?? 'Supervisor' }}</strong></p>
    <p class="tanggal">{{ date('d F Y, H:i') }} WIB</p>
</div>

<div class="footer-info">
    Laporan Jadwal Pemeliharaan — PLTM Sangir Hulu (PT Waskita Energi) &nbsp;|&nbsp; Halaman <span class="page"></span>
</div>

</body>
</html>