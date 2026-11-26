<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengingat Jadwal Pemeliharaan</title>
</head>

<body style="margin:0; padding:0; background:#f5f0ff; font-family:Arial, sans-serif;">
    @php
        $isH1 = $jenisPengingat === 'h_1';
        $judulPengingat = $isH1
            ? 'H-1 Tenggat'
            : 'Hari H Tenggat';
        $warnaPengingat = $isH1
            ? '#c05621'
            : '#c53030';
        $backgroundPengingat = $isH1
            ? '#fffaf0'
            : '#fff5f5';
        $borderPengingat = $isH1
            ? '#fbd38d'
            : '#feb2b2';
        $pesanPengingat = $isH1
            ? 'Batas waktu penyelesaian pekerjaan adalah besok.'
            : 'Batas waktu penyelesaian pekerjaan adalah hari ini.';
    @endphp

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f5f0ff; padding:30px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="width:100%; max-width:600px; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 20px rgba(102,126,234,0.15);">
                    {{-- HEADER --}}
                    <tr>
                        <td style="background:#667eea; background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding:30px 40px; text-align:center;">
                            <h1 style="color:#ffffff; margin:0; font-size:22px; letter-spacing:0.5px;">
                                🔧 Pengingat Jadwal Pemeliharaan
                            </h1>
                            <p style="color:#eee9ff; margin:8px 0 0 0; font-size:13px;">
                                PLTM Sangir Hulu (PT Waskita Energi) -
                                Sistem Manajemen Pemeliharaan
                            </p>
                        </td>
                    </tr>

                    {{-- BODY --}}
                    <tr>
                        <td style="padding:35px 40px 20px 40px;">
                            <p style="font-size:15px; color:#2d3748; margin:0 0 8px 0;">
                                Halo <strong>{{ $jadwal->user->name ?? 'Tenaga Kerja' }}</strong>,
                            </p>
                            <p style="font-size:14px; color:#4a5568; line-height:1.6; margin:0 0 20px 0;">
                                Kami mengingatkan bahwa jadwal pemeliharaan
                                yang ditugaskan kepada Anda akan mencapai
                                batas waktu penyelesaian
                                <strong>{{ $isH1 ? 'besok' : 'hari ini' }}.</strong>
                            </p>

                            {{-- INFORMASI PENGINGAT --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="background:{{ $backgroundPengingat }}; border:1px solid {{ $borderPengingat }}; border-left:4px solid {{ $warnaPengingat }}; border-radius:8px; margin-bottom:25px;">
                                <tr>
                                    <td style="padding:15px 20px;">
                                        <p style="font-size:15px; color:{{ $warnaPengingat }}; font-weight:bold; margin:0 0 6px 0;">
                                            ⏰ {{ $judulPengingat }}
                                        </p>
                                        <p style="font-size:13px; color:{{ $warnaPengingat }}; line-height:1.6; margin:0;">
                                            {{ $pesanPengingat }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            {{-- DETAIL JADWAL --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="background:#f7fafc; border-left:4px solid #667eea; border-radius:8px; margin-bottom:25px;">
                                <tr>
                                    <td style="padding:20px 25px;">
                                        <h2 style="font-size:17px; color:#1a1a2e; margin:0 0 15px 0;">
                                            {{ $jadwal->judul_pemeliharaan }}
                                        </h2>
                                        <table width="100%" cellpadding="0" cellspacing="0" style="font-size:13px;">
                                            <tr>
                                                <td style="padding:5px 0; color:#718096; width:140px;">
                                                    Nama Peralatan
                                                </td>
                                                <td style="padding:5px 0; color:#2d3748; font-weight:bold;">
                                                    : {{ $jadwal->nama_peralatan ?: '-' }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="padding:5px 0; color:#718096;">
                                                    Lokasi
                                                </td>
                                                <td style="padding:5px 0; color:#2d3748; font-weight:bold;">
                                                    : {{ $jadwal->lokasi ?: '-' }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="padding:5px 0; color:#718096;">
                                                    Quantity
                                                </td>
                                                <td style="padding:5px 0; color:#2d3748; font-weight:bold;">
                                                    : {{ $jadwal->quantity ?? '-' }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="padding:5px 0; color:#718096;">
                                                    Tanggal Selesai
                                                </td>
                                                <td style="padding:5px 0; color:#c53030; font-weight:bold;">
                                                    : {{ $jadwal->tanggal_selesai
                                                        ? $jadwal->tanggal_selesai->locale('id')->translatedFormat('d F Y')
                                                        : '-' }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="padding:5px 0; color:#718096;">
                                                    Status
                                                </td>
                                                <td style="padding:5px 0; color:#c05621; font-weight:bold;">
                                                    : {{ ucfirst(str_replace('_', ' ', $jadwal->status)) }}
                                                </td>
                                            </tr>

                                            @if($jadwal->keterangan)
                                                <tr>
                                                    <td style="padding:5px 0; color:#718096; vertical-align:top;">
                                                        Keterangan
                                                    </td>
                                                    <td style="padding:5px 0; color:#2d3748;">
                                                        : {{ $jadwal->keterangan }}
                                                    </td>
                                                </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            {{-- PESAN PENYELESAIAN --}}
                            <p style="font-size:14px; color:#4a5568; line-height:1.6; margin:0 0 15px 0;">
                                Mohon segera menyelesaikan pekerjaan dan
                                mengirimkan hasil checklist pemeliharaan
                                melalui sistem sebelum batas waktu yang
                                telah ditentukan.
                            </p>

                            {{-- BUTTON --}}
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding:10px 0 25px 0;">
                                        <a href="{{ url('/tenagakerja/checklist') }}" style="display:inline-block; background:#667eea; background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:#ffffff; text-decoration:none; padding:14px 40px; border-radius:40px; font-size:14px; font-weight:bold;">
                                            Lihat Checklist Saya
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            <p style="font-size:12px; color:#a0aec0; line-height:1.6; margin:0; text-align:center;">
                                Email ini dikirim secara otomatis oleh sistem.
                                Mohon tidak membalas email ini.
                            </p>
                        </td>
                    </tr>

                    {{-- FOOTER --}}
                    <tr>
                        <td style="background:#1a1a2e; padding:20px 40px; text-align:center;">
                            <p style="color:#ffffff; font-size:12px; margin:0 0 4px 0; font-weight:bold;">
                                PLTM Sangir Hulu - PT Waskita Energi
                            </p>
                            <p style="color:#a0aec0; font-size:11px; margin:0;">
                                Jorong Letter W, Nagari Lubuk Gadang Tim.,
                                Kec. Sangir, Kabupaten Solok Selatan,
                                Prov. Sumatera Barat
                            </p>
                            <p style="color:#718096; font-size:10px; margin:10px 0 0 0;">
                                © {{ date('Y') }} Sistem Manajemen Pemeliharaan.
                                All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
