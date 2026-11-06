<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jadwal Pengecekan Sparepart Baru</title>
</head>
<body style="margin: 0; padding: 0; background: #f5f0ff; font-family: Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f5f0ff; padding:30px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style=" background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 20px rgba(102,126,234,0.15);">
                    {{-- HEADER --}}
                    <tr>
                        <td style=" background:linear-gradient( 135deg, #667eea 0%, #764ba2 100%); padding:30px 40px; text-align:center;">
                            <h1 style="color:#ffffff; margin:0; font-size:22px;">
                                📦 Jadwal Pengecekan Sparepart Baru
                            </h1>
                            <p style="color:rgba(255,255,255,0.85); margin:8px 0 0 0; font-size:13px;">
                                PLTM Sangir Hulu (PT Waskita Energi) - Sistem Manajemen Pemeliharaan
                            </p>
                        </td>
                    </tr>

                    {{-- BODY --}}
                    <tr>
                        <td style="padding:35px 40px 20px 40px;">
                            <p style="font-size:15px; color:#2d3748; margin:0 0 8px 0;">
                                Halo <strong>{{ $jadwal->tenagaKerja->name ?? 'Tenaga Kerja' }}</strong>,
                            </p>
                            <p style="font-size:14px; color:#4a5568; line-height:1.6;">
                                Anda telah mendapatkan tugas baru untuk melakukan pengecekan stok sparepart. Mohon lakukan pengecekan sesuai jadwal yang telah ditentukan.
                            </p>

                            {{-- DETAIL JADWAL --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="background:#f7fafc; border-left:4px solid #667eea; border-radius:8px; margin:25px 0;">
                                <tr>
                                    <td style="padding:20px 25px;">
                                        <h2 style="font-size:17px; color:#1a1a2e; margin:0 0 15px 0;">
                                            {{ $jadwal->judul_pengecekan }}
                                        </h2>

                                        <table width="100%" cellpadding="0" cellspacing="0" style="font-size:13px;">
                                            <tr>
                                                <td style="padding:5px 0; color:#718096; width:150px;">Tanggal Pengecekan</td>
                                                <td style="padding:5px 0; color:#c53030; font-weight:bold;">
                                                    : {{ \Carbon\Carbon::parse($jadwal->tanggal_pengecekan)->format('d/m/Y') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:5px 0; color:#718096; vertical-align:top;">Sparepart yang Dicek</td>
                                                <td style=" padding:5px 0; color:#2d3748;">:
                                                    @foreach($jadwal->details as $detail)
                                                        <div style="margin-bottom:5px; font-weight:bold;">
                                                            • {{ $detail->nama_sparepart_snapshot }}
                                                        </div>
                                                    @endforeach
                                                </td>
                                            </tr>
                                            @if($jadwal->keterangan)
                                                <tr>
                                                    <td style="padding:5px 0; color:#718096; vertical-align:top;">Keterangan</td>
                                                    <td style="padding:5px 0; color:#2d3748;">
                                                        : {{ $jadwal->keterangan }}
                                                    </td>
                                                </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            {{-- BUTTON --}}
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding:10px 0 25px 0;">
                                        <a href="{{url('/tenagakerja/pengecekan-onderdil')}}"
                                            style="display:inline-block; background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:#ffffff; text-decoration:none; padding:14px 40px; border-radius:40px; font-size:14px; font-weight:bold;">
                                            Lihat Tugas Pengecekan
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            <p style="font-size:12px; color:#a0aec0; line-height:1.6; text-align:center;">
                                Email ini dikirim secara otomatis oleh sistem. Mohon tidak membalas email ini.
                            </p>
                        </td>
                    </tr>

                    {{-- FOOTER --}}
                    <tr>
                        <td style="background: #1a1a2e; padding: 20px 40px; text-align: center;">
                            <p style="color: #ffffff; font-size: 12px; margin: 0 0 4px 0; font-weight: bold;">
                                PLTM Sangir Hulu - PT Waskita Energi
                            </p>
                            <p style="color: #a0aec0; font-size: 11px; margin: 0;">
                                Jorong Letter W, Nagari Lubuk Gadang Tim., Kec. Sangir, Kabupaten Solok Selatan, Prov. Sumatera Barat                            </p>
                            <p style="color: #718096; font-size: 10px; margin: 10px 0 0 0;">
                                © {{ date('Y') }} Sistem Manajemen Pemeliharaan. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
