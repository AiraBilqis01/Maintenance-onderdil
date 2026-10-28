<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $judul }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; color: #333; }

        /* Style Kop Surat */
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

        /* Style Header */
        .header { text-align: center; margin-bottom: 15px; border-bottom: 2px solid #2C3E50; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; color: #2C3E50; }
        .header p { margin: 5px 0 0; color: #666; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: center; vertical-align: middle; }
        th { background-color: #f2f2f2; font-weight: bold; font-size: 10px; }
        td.text-left { text-align: left; }
        
        /* Style untuk Gambar di PDF */
        .img-pdf { 
            width: 120px; 
            height: 120px; 
            object-fit: cover; 
            border-radius: 4px; 
            border: 1px solid #eee;
        }
        
        /* Style untuk Badge Stok */
        .badge { padding: 3px 6px; border-radius: 4px; font-weight: bold; color: white; font-size: 9px; }
        .bg-success { background-color: #38a169; }
        .bg-warning { background-color: #d69e2e; }
        .bg-danger { background-color: #e53e3e; }
    </style>
</head>

<body>
@php
    $logoPath = public_path('assets/img/logopt.png');

    $logoSrc = file_exists($logoPath)
        ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
        : null;
@endphp

    <div class="kop-surat">
        <div class="kop-logo">
            @if($logoSrc)
                <img src="{{ $logoSrc }}" alt="Logo">
            @endif
        </div>

        <div class="kop-teks">
            <h1>PLTM Sangir Hulu (PT Waskita Energi)</h1>
            <h2>Sistem Manajemen Pemeliharan & Onderdil</h2>
            <p>
                Jorong Letter W, Nagari Lubuk Gadang Tim., Kec. Sangir, Kabupaten Solok Selatan, Prov. Sumatera Barat &nbsp;|&nbsp; <a href="mailto:adminpemeliharaan.pltmsh@gmail.com"> adminpemeliharaan.pltmsh@gmail.com </a>
            </p>
        </div>

    </div>
    <div class="header">
        <h1>{{ $judul }}</h1>
        <p>{{ $periode }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="8%">Gambar</th> <!-- KOLOM GAMBAR BARU -->
                <th width="20%">Nama Sparepart</th>
                <th width="9%">Stok Sebelum</th>
                <th width="10%">Periode</th>
                <th width="10%">Kondisi</th>
                <th width="8%">Pemakaian</th>
                <th width="9%">Stok Terbaru</th>
                <th width="22%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stokOnderdil as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    @if($item->gambar && file_exists(public_path($item->gambar)))
                        @php
                            // Mengubah gambar menjadi format Base64 agar bisa dibaca DomPDF
                            $path = public_path($item->gambar);
                            $type = pathinfo($path, PATHINFO_EXTENSION);
                            $data = file_get_contents($path);
                            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                        @endphp
                        <img src="{{ $base64 }}" class="img-pdf" alt="Gambar">
                    @else
                        <span style="color: #999; font-size: 9px;">No Image</span>
                    @endif
                </td>
                <td class="text-left"><strong>{{ $item->nama_sparepart }}</strong></td>
                <td>{{ $item->stok_periode_sebelum }}</td>
                <td>{{ \Carbon\Carbon::parse($item->periode)->format('F Y') }}</td>
                <td>{{ $item->kondisi_sekarang }}</td>
                <td>{{ $item->pemakaian }}</td>
                <td>
                    @php
                        $class = $item->stok_terbaru > 10 ? 'bg-success' : ($item->stok_terbaru > 5 ? 'bg-warning' : 'bg-danger');
                    @endphp
                    <span class="badge {{ $class }}">{{ $item->stok_terbaru }}</span>
                </td>
                <td class="text-left">{{ $item->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>