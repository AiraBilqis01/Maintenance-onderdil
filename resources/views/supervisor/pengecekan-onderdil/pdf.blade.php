<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ $judul }}</title>
    <style>
        @page {
            margin: 25px 20px 40px 20px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 8px;
            color: #2d3748;
        }


        /* =========================================================
           CONTAINER
           ========================================================= */

        .page-container {
            margin-top: 20px;
            margin-left: 20px;
            margin-right: 20px;
        }


        /* =========================================================
           KOP SURAT
           ========================================================= */

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
            padding-right: 10px;
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
        }

        .kop-teks h1 {
            font-size: 15px;
            font-weight: bold;
            color: #1a1a2e;
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


        /* =========================================================
           JUDUL LAPORAN
           ========================================================= */

        .judul-laporan {
            text-align: center;
            margin-bottom: 10px;
        }

        .judul-laporan h3 {
            font-size: 12px;
            font-weight: bold;
            color: #1a1a2e;
            text-transform: uppercase;
            padding-bottom: 4px;
            padding-left: 20px;
            padding-right: 20px;
            border-bottom: 1px solid #cbd5e0;
            display: inline-block;
        }


        /* =========================================================
           INFORMASI LAPORAN
           ========================================================= */

        .info-bar {
            background: #f7fafc;
            border-left: 4px solid #667eea;
            padding: 8px 12px;
            margin-bottom: 12px;
            font-size: 8.5px;
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
            font-size: 8.5px;
            color: #2d3748;
        }

        .info-bar .label {
            color: #718096;
            width: 90px;
            font-weight: bold;
        }

        .info-bar .value {
            color: #1a1a2e;
            font-weight: bold;
        }


        /* =========================================================
           RINGKASAN STATUS
           ========================================================= */

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .summary-table td {
            width: 20%;
            padding: 6px;
            border: 1px solid #e2e8f0;
            text-align: center;
            font-size: 8px;
        }

        .summary-number {
            font-size: 13px;
            font-weight: bold;
            color: #1a1a2e;
            display: block;
        }

        .summary-label {
            color: #718096;
            font-size: 7.5px;
        }


        /* =========================================================
           TABEL DATA
           ========================================================= */

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        table.data thead th {
            background: #1a1a2e;
            color: #ffffff;
            padding: 6px 4px;
            text-align: center;
            vertical-align: middle;
            font-size: 7px;
            text-transform: uppercase;
            border: 1px solid #1a1a2e;
        }

        table.data tbody td {
            padding: 5px 4px;
            border: 1px solid #cbd5e0;
            vertical-align: top;
            font-size: 7.5px;
            line-height: 1.35;
        }

        table.data tbody tr:nth-child(even) {
            background: #f7fafc;
        }

        .text-center {
            text-align: center;
        }


        /* =========================================================
           STATUS
           ========================================================= */

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 7.5px;
            font-weight: bold;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .badge-pending {
            background: #fefcbf;
            color: #744210;
            border: 1px solid #ecc94b;
        }

        .badge-menunggu {
            background: #e0f2fe;
            color: #0284c7;
            border: 1px solid #bae6fd;
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


        /* =========================================================
           SELISIH
           ========================================================= */

        .selisih-positif {
            color: #22543d;
            font-weight: bold;
        }

        .selisih-negatif {
            color: #c53030;
            font-weight: bold;
        }

        .selisih-normal {
            color: #4a5568;
            font-weight: bold;
        }


        /* =========================================================
           TIDAK ADA DATA
           ========================================================= */

        .empty-data {
            text-align: center;
            color: #718096;
            padding: 15px !important;
        }


        /* =========================================================
           TANDA CETAK
           ========================================================= */

        .dicetak-oleh {
            margin-top: 25px;
            text-align: right;
            padding-right: 20px;
        }

        .dicetak-oleh p {
            font-size: 8.5px;
            color: #4a5568;
            margin: 2px 0;
        }

        .dicetak-oleh strong {
            color: #1a1a2e;
            font-size: 9px;
        }

        .dicetak-oleh .tanggal {
            font-size: 7.5px;
            color: #a0aec0;
            font-style: italic;
        }


        /* =========================================================
           FOOTER
           ========================================================= */

        .footer-info {
            position: fixed;
            bottom: -25px;
            left: 0;
            right: 0;
            height: 20px;
            text-align: center;
            font-size: 7px;
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
    /*
     * =========================================================
     * RINGKASAN DATA
     * =========================================================
     */
    $totalJadwal = $jadwal->count();
    $jmlPending = $jadwal->where('status', 'pending')->count();
    $jmlMenunggu = $jadwal->where('status', 'menunggu_verifikasi')->count();
    $jmlSelesai = $jadwal->where('status', 'selesai')->count();
    $jmlDitolak = $jadwal->where('status', 'ditolak')->count();

    /*
     * =========================================================
     * LOGO
     * =========================================================
     */
    $logoPath =
        public_path(
            'assets/img/logopt.png'
        );

    $logoSrc =
        file_exists($logoPath)
            ? 'data:image/png;base64,' .
                base64_encode(
                    file_get_contents(
                        $logoPath
                    )
                )
            : null;

@endphp

<div class="page-container">
    {{-- ===================================================== --}}
    {{-- KOP SURAT --}}
    {{-- ===================================================== --}}
    <div class="kop-surat">
        <div class="kop-logo">
            @if($logoSrc)
                <img src="{{ $logoSrc }}" alt="Logo">
            @endif
        </div>
        <div class="kop-teks">
            <h1>
                PLTM Sangir Hulu (PT Waskita Energi)
            </h1>
            <h2>
                Sistem Manajemen Pemeliharaan & Sparepart
            </h2>
            <p> Jorong Letter W, Nagari Lubuk Gadang Tim., Kec. Sangir, Kabupaten Solok Selatan, Prov. Sumatera Barat &nbsp;|&nbsp; adminpemeliharaan.pltmsh@gmail.com</p>
        </div>
    </div>
    {{-- ===================================================== --}}
    {{-- JUDUL LAPORAN --}}
    {{-- ===================================================== --}}
    <div class="judul-laporan">
        <h3>{{ $judul }}</h3>
    </div>

    {{-- ===================================================== --}}
    {{-- INFORMASI FILTER / LAPORAN --}}
    {{-- ===================================================== --}}
    <div class="info-bar">
        <table>
            <tr>
                <td class="label">Filter Data</td>
                <td class="value"> : {{ $periode }}</td>
                <td class="label">Tanggal Cetak</td>
                <td class="value"> : {{ now()->format('d/m/Y H:i') }} WIB</td>
            </tr>
            <tr>
                <td class="label">Total Jadwal</td>
                <td class="value"> : {{ $totalJadwal }} jadwal</td>
                <td class="label">Dicetak Oleh</td>
                <td class="value"> : {{ Auth::user()->name ?? 'Supervisor' }}</td>
            </tr>
        </table>
    </div>

    {{-- ===================================================== --}}
    {{-- RINGKASAN STATUS --}}
    {{-- ===================================================== --}}
    <table class="summary-table">
        <tr>
            <td>
                <span class="summary-number">{{ $totalJadwal }}</span>
                <span class="summary-label">Total Jadwal</span>
            </td>
            <td>
                <span class="summary-number">{{ $jmlPending }}</span>
                <span class="summary-label">Pending</span>
            </td>
            <td>
                <span class="summary-number">{{ $jmlMenunggu }}</span>
                <span class="summary-label">Menunggu Verifikasi</span>
            </td>
            <td>
                <span class="summary-number">{{ $jmlSelesai }}</span>
                <span class="summary-label">Selesai</span>
            </td>
            <td>
                <span class="summary-number">{{ $jmlDitolak }}</span>
                <span class="summary-label">Ditolak</span>
            </td>
        </tr>
    </table>

    {{-- ===================================================== --}}
    {{-- DATA PENGECEKAN --}}
    {{-- ===================================================== --}}
    <table class="data">
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="10%">Judul Pengecekan</th>
                <th width="7%">Tanggal Pengecekan</th>
                <th width="8%">Hasil Dikirim (ACC)</th>
                <th width="9%">Tenaga Kerja</th>
                <th width="11%">Nama Sparepart</th>
                <th width="7%">Stok Sistem</th>
                <th width="7%">Stok Fisik</th>
                <th width="6%">Selisih</th>
                <th width="8%">Kondisi</th>
                <th width="10%">Status</th>
                <th width="22%">Keterangan</th>
            </tr>
        </thead>

        <tbody>
            @php
                $nomor = 1;
            @endphp
            @forelse($jadwal as $item)
                {{-- ================================================= --}}
                {{-- JIKA JADWAL MEMILIKI DETAIL ONDERDIL --}}
                {{-- ================================================= --}}
                @if($item->details->isNotEmpty())
                    @foreach($item->details as $detail)
                        @php
                            /* Selisih yang sudah tersimpan digunakan terlebih dahulu. */
                            $selisih = $detail->selisih;

                            /* Jika nilai selisih belum tersimpan, tetapi stok sistem dan stok fisik sudah tersedia, hitung ulang hanya untuk tampilan laporan. */
                            if (is_null($selisih) && !is_null($detail->stok_sistem_saat_cek) && !is_null($detail->stok_fisik))
                            {
                                $selisih = (int) $detail->stok_fisik - (int) $detail->stok_sistem_saat_cek;
                            }
                            /*
                             * =================================================
                             * KETERANGAN LAPORAN
                             * =================================================
                             */
                            $catatan = [];
                            if (!empty($item->keterangan)) {
                                $catatan[] = 'Jadwal: ' . $item->keterangan;
                            }

                            if (!empty($detail->catatan_pengecekan))
                            {
                                $catatan[] = 'Hasil: ' . $detail->catatan_pengecekan;
                            }

                            /* Penyebab dan keterangan selisih hanya ditampilkan jika memang terdapat selisih. */
                            if (!is_null($selisih) && (int) $selisih !== 0)
                            {
                                if (!empty($detail->penyebab_selisih))
                                {
                                    $catatan[] = 'Penyebab selisih: ' . $detail->penyebab_selisih;
                                }

                                if (!empty($detail->keterangan_selisih))
                                {
                                    $catatan[] = 'Keterangan selisih: ' . $detail->keterangan_selisih;
                                }
                            }

                            /* Tampilkan alasan penolakan jika ada. */
                            if (!empty($item->alasan_penolakan))
                            {
                                $catatan[] = 'Alasan penolakan: ' . $item->alasan_penolakan;
                            }
                        @endphp
                        <tr>
                            {{-- ================================================= --}}
                            {{-- NO --}}
                            {{-- ================================================= --}}
                            <td class="text-center">
                                {{ $nomor++ }}
                            </td>
                            {{-- ================================================= --}}
                            {{-- JUDUL PENGECEKAN --}}
                            {{-- ================================================= --}}
                            <td>
                                <strong>{{ $item->judul_pengecekan }}</strong>
                            </td>
                            {{-- ================================================= --}}
                            {{-- TARGET PENGECEKAN TERAKHIR --}}
                            {{-- ================================================= --}}
                            <td class="text-center">
                                {{ $item->tanggal_pengecekan ? \Carbon\Carbon::parse($item->tanggal_pengecekan)->format('d/m/Y') : '-' }}
                            </td>
                            {{-- ================================================= --}}
                            {{-- HASIL DIKIRIM DAN SUDAH ACC --}}
                            {{-- ================================================= --}}
                            <td class="text-center">
                                @if($item->status === 'selesai' && !empty($item->tanggal_kirim_hasil))
                                    {{ \Carbon\Carbon::parse($item->tanggal_kirim_hasil)->format('d/m/Y') }}
                                @else
                                    -
                                @endif
                            </td>
                            {{-- ================================================= --}}
                            {{-- TENAGA KERJA --}}
                            {{-- ================================================= --}}
                            <td>
                                {{ $item->tenagaKerja->name ?? '-' }}
                            </td>
                            {{-- ================================================= --}}
                            {{-- ONDERDIL --}}
                            {{-- ================================================= --}}
                            <td>
                                {{ $detail->nama_sparepart_snapshot }}
                            </td>
                            {{-- ================================================= --}}
                            {{-- STOK SISTEM --}}
                            {{-- ================================================= --}}
                            <td class="text-center">
                                @if(!is_null($detail->stok_sistem_saat_cek))
                                    {{ $detail->stok_sistem_saat_cek }}
                                @else
                                    -
                                @endif
                            </td>
                            {{-- ================================================= --}}
                            {{-- STOK FISIK --}}
                            {{-- ================================================= --}}
                            <td class="text-center">
                                @if(!is_null($detail->stok_fisik))
                                    {{ $detail->stok_fisik }}
                                @else
                                    -
                                @endif
                            </td>
                            {{-- ================================================= --}}
                            {{-- SELISIH --}}
                            {{-- ================================================= --}}
                            <td class="text-center">
                                @if(is_null($selisih))
                                    -
                                @elseif((int) $selisih > 0)
                                    <span class="selisih-positif"> +{{ $selisih }}</span>
                                @elseif((int) $selisih < 0)
                                    <span class="selisih-negatif"> {{ $selisih }}</span>
                                @else
                                    <span class="selisih-normal">0</span>
                                @endif
                            </td>
                            {{-- ================================================= --}}
                            {{-- KONDISI --}}
                            {{-- ================================================= --}}
                            <td class="text-center">{{ $detail->kondisi ?? '-' }}</td>
                            {{-- ================================================= --}}
                            {{-- STATUS --}}
                            {{-- Badge sama seperti PDF Jadwal Pemeliharaan --}}
                            {{-- ================================================= --}}
                            <td class="text-center">
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
                            {{-- ================================================= --}}
                            {{-- KETERANGAN --}}
                            {{-- ================================================= --}}
                            <td>
                                @if(count($catatan) > 0)
                                    @foreach($catatan as $text)
                                        <div style="margin-bottom: 3px;">{{ $text }}</div>
                                    @endforeach
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                {{-- ================================================= --}}
                {{-- JIKA DETAIL ONDERDIL TIDAK ADA --}}
                {{-- ================================================= --}}
                @else
                    <tr>
                        {{-- NO --}}
                        <td class="text-center"> {{ $nomor++ }}</td>
                        {{-- JUDUL --}}
                        <td>
                            <strong> {{ $item->judul_pengecekan }}</strong>
                        </td>
                        {{-- TARGET PENGECEKAN TERAKHIR --}}
                        <td class="text-center">
                            {{ $item->tanggal_pengecekan ? \Carbon\Carbon::parse($item->tanggal_pengecekan)->format('d/m/Y') : '-' }}
                        </td>
                        {{-- HASIL DIKIRIM DAN SUDAH ACC --}}
                        <td class="text-center">
                            @if($item->status === 'selesai' && !empty($item->tanggal_kirim_hasil))
                                {{ \Carbon\Carbon::parse($item->tanggal_kirim_hasil)->format('d/m/Y') }}
                            @else
                                -
                            @endif
                        </td>
                        {{-- TENAGA KERJA --}}
                        <td> {{ $item->tenagaKerja->name ?? '-' }}</td>
                        {{-- ONDERDIL --}}
                        <td class="text-center">-</td>
                        {{-- STOK SISTEM --}}
                        <td class="text-center">-</td>
                        {{-- STOK FISIK --}}
                        <td class="text-center">-</td>
                        {{-- SELISIH --}}
                        <td class="text-center">-</td>
                        {{-- KONDISI --}}
                        <td class="text-center">-</td>
                        {{-- ================================================= --}}
                        {{-- STATUS --}}
                        {{-- Badge sama seperti PDF Jadwal Pemeliharaan --}}
                        {{-- ================================================= --}}
                        <td class="text-center">
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
                        {{-- KETERANGAN --}}
                        <td>{{ $item->keterangan ?? '-' }}</td>
                    </tr>
                @endif
            @empty
                {{-- ================================================= --}}
                {{-- JIKA TIDAK ADA DATA --}}
                {{-- ================================================= --}}
                <tr>
                    <td colspan="12" class="empty-data">Tidak ada data pengecekan sparepart yang sesuai dengan filter.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ===================================================== --}}
    {{-- DICETAK OLEH --}}
    {{-- ===================================================== --}}
    <div class="dicetak-oleh">
        <p>Dicetak oleh: <strong>{{ Auth::user()->name ?? 'Supervisor' }}</strong></p>
        <p class="tanggal">{{ now()->format('d/m/Y H:i') }} WIB</p>
    </div>
</div>

{{-- ========================================================= --}}
{{-- FOOTER --}}
{{-- ========================================================= --}}
<div class="footer-info">
    Laporan Pengecekan Sparepart — PLTM Sangir Hulu (PT Waskita Energi) &nbsp;|&nbsp; Halaman<span class="page"></span>
</div>

</body>
</html>
