<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Perbaikan Checklist Pemeliharaan</title>
</head>

<body style="margin:0; padding:0; background:#f5f0ff; font-family:Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f5f0ff; padding:30px 0;">

    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:16px; overflow:hidden;">
                {{-- HEADER --}}
                <tr>
                    <td style="background:#c53030; padding:30px 40px; text-align:center;">
                        <h1 style="color:#ffffff; margin:0; font-size:22px;">
                            Hasil Pemeliharaan Perlu Diperbaiki
                        </h1>
                        <p style="color:#ffffff; margin:8px 0 0 0; font-size:13px;">
                            PLTM Sangir Hulu - Sistem Manajemen Pemeliharaan
                        </p>
                    </td>
                </tr>

                {{-- BODY --}}
                <tr>
                    <td style="padding:35px 40px;">
                        <p style="font-size:15px; color:#2d3748;">
                            Halo <strong>{{ $jadwal->user->name ?? 'Tenaga Kerja' }}</strong>,
                        </p>
                        <p style="font-size:14px; color:#4a5568; line-height:1.6;">
                            Hasil checklist pemeliharaan yang Anda kirim
                            belum disetujui oleh Supervisor.
                            Silakan lakukan perbaikan dan kirim ulang
                            checklist pemeliharaan.
                        </p>

                        {{-- DETAIL PEKERJAAN --}}
                        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f7fafc; border-left:4px solid #667eea; margin:20px 0;">
                            <tr>
                                <td style="padding:20px;">
                                    <h2 style="font-size:17px; color:#1a1a2e; margin-top:0;">
                                    {{ $jadwal->judul_pemeliharaan }}
                                    </h2>
                                    <p>
                                        <strong>Nama Peralatan:</strong>{{ $jadwal->nama_peralatan ?? '-' }}
                                    </p>
                                    <p>
                                        <strong>Lokasi:</strong>{{ $jadwal->lokasi ?? '-' }}
                                    </p>
                                </td>
                            </tr>
                        </table>

                        {{-- CATATAN SUPERVISOR --}}
                        <table width="100%" cellpadding="0" cellspacing="0" style="background:#fff5f5; border-left:4px solid #e53e3e; margin-bottom:25px;">
                            <tr>
                                <td style="padding:20px;">
                                    <h3 style="color:#c53030; margin-top:0;">
                                        Catatan dari Supervisor
                                    </h3>
                                    <p>
                                        <strong>Keterangan:</strong><br>{{ $jadwal->keterangan_tolak ?? '-' }}
                                    </p>
                                    <p>
                                        <strong>Alasan Penolakan:</strong><br>{{ $jadwal->alasan_penolakan ?? '-' }}
                                    </p>
                                </td>
                            </tr>
                        </table>

                        {{-- TOMBOL --}}
                        <div style="text-align:center; margin:25px 0;">
                            <a href="{{ url('/tenagakerja/checklist') }}" style="display:inline-block; background:#667eea; color:#ffffff; text-decoration:none; padding:14px 35px; border-radius:30px; font-weight:bold;">
                                Isi Ulang Checklist
                            </a>
                        </div>
                        <p style="font-size:12px; color:#a0aec0; text-align:center;">
                            Email ini dikirim otomatis oleh sistem.
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

</body>
</html>