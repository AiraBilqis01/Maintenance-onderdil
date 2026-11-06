<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Penugasan Pengecekan Sparepart Dialihkan</title>
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
                            Penugasan Pengecekan Sparepart Dialihkan
                        </h1>
                        <p style="color:#ffffff; margin:8px 0 0; font-size:13px;">
                            PLTMH Sangir Hulu - Sistem Manajemen Pemeliharaan dan Persediaan
                        </p>
                    </td>
                </tr>

                {{-- BODY --}}
                <tr>
                    <td style="padding:35px 40px;">
                        <p style="font-size:15px; color:#2d3748;">
                            Halo <strong>{{ $tenagaKerjaLama->name ?? 'Tenaga Kerja' }}</strong>,
                        </p>
                        <p style="font-size:14px; color:#4a5568; line-height:1.6;">
                            Penugasan pengecekan sparepart berikut telah diperbarui oleh Supervisor dan
                            <strong>tidak lagi ditugaskan kepada Anda.</strong>
                        </p>

                        {{-- PERINGATAN --}}
                        <table width="100%" cellpadding="0" cellspacing="0" style="background:#fff5f5; border-left:4px solid #e53e3e; margin-bottom:25px;">
                            <tr>
                                <td style="padding:15px 20px; color:#822727; font-size:13px; line-height:1.6;">
                                    <strong>Perhatian:</strong>
                                    Silakan abaikan email penugasan sebelumnya untuk jadwal ini. Jadwal tersebut telah dialihkan kepada Tenaga Kerja lain.
                                </td>
                            </tr>
                        </table>

                        {{-- DETAIL --}}
                        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f7fafc; border-left:4px solid #667eea; border-radius:8px; margin-bottom:25px;">
                            <tr>
                                <td style="padding:20px 25px;">
                                    <h2 style="font-size:17px; color:#1a1a2e; margin:0 0 15px 0;">
                                        {{ $jadwal->judul_pengecekan }}
                                    </h2>
                                    <table width="100%" cellpadding="0" cellspacing="0" style="font-size:13px;">
                                        <tr>
                                            <td style="padding:5px 0; color:#718096; width:160px;">Tanggal Pengecekan</td>
                                            <td style="padding:5px 0; color:#2d3748; font-weight:bold;">
                                                : {{ \Carbon\Carbon::parse($jadwal->tanggal_pengecekan)->format('d/m/Y') }}
                                            </td>
                                        </tr>
                                    </table>
                                    <div style="margin-top:20px;">
                                        <p style="font-size:13px; color:#718096; margin:0 0 10px 0;">
                                            Sparepart pada jadwal:
                                        </p>
                                        @forelse($jadwal->details as $detail)
                                            <div style="background:#edf2f7; color:#2d3748; padding:8px 12px; border-radius:6px; margin-bottom:6px; font-size:13px;">
                                                • {{ $detail->nama_sparepart_snapshot }}
                                            </div>
                                        @empty
                                            <p style="font-size:13px; color:#718096;">
                                                Tidak ada data sparepart.
                                            </p>
                                        @endforelse
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <p style="font-size:14px; color:#4a5568; line-height:1.6;">
                            Anda tidak perlu melakukan tindakan lebih lanjut terhadap jadwal pengecekan sparepart ini.
                        </p>
                        <p style="font-size:12px; color:#a0aec0; text-align:center; margin-top:30px;">
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
