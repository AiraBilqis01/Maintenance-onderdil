<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jadwal Pemeliharaan Baru</title>
</head>
<body style="margin:0; padding:0; background:#f5f0ff; font-family: Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f5f0ff; padding:30px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:16px; overflow:hidden; box-shadow: 0 4px 20px rgba(102,126,234,0.15);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px 40px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 22px; letter-spacing: 0.5px;">
                                🔧 Jadwal Pemeliharaan Baru
                            </h1>
                            <p style="color: rgba(255,255,255,0.85); margin: 8px 0 0 0; font-size: 13px;">
                                PT Waskita Energi - Sistem Manajemen Onderdil
                            </p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 35px 40px 20px 40px;">
                            <p style="font-size: 15px; color: #2d3748; margin: 0 0 8px 0;">
                                Halo <strong>{{ $jadwal->user->name ?? 'Tenaga Kerja' }}</strong>,
                            </p>
                            <p style="font-size: 14px; color: #4a5568; line-height: 1.6; margin: 0 0 25px 0;">
                                Anda telah ditugaskan untuk melakukan pemeliharaan berikut. Mohon segera cek dan lakukan sesuai jadwal.
                            </p>

                            <!-- Detail card -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background: #f7fafc; border-left: 4px solid #667eea; border-radius: 8px; padding: 0; margin-bottom: 25px;">
                                <tr>
                                    <td style="padding: 20px 25px;">
                                        <h2 style="font-size: 17px; color: #1a1a2e; margin: 0 0 15px 0;">
                                            {{ $jadwal->judul_pemeliharaan }}
                                        </h2>

                                        <table width="100%" cellpadding="0" cellspacing="0" style="font-size: 13px;">
                                            <tr>
                                                <td style="padding: 5px 0; color: #718096; width: 140px;">Nama Peralatan</td>
                                                <td style="padding: 5px 0; color: #2d3748; font-weight: bold;">: {{ $jadwal->nama_peralatan }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px 0; color: #718096;">Lokasi</td>
                                                <td style="padding: 5px 0; color: #2d3748; font-weight: bold;">: {{ $jadwal->lokasi ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px 0; color: #718096;">Quantity</td>
                                                <td style="padding: 5px 0; color: #2d3748; font-weight: bold;">: {{ $jadwal->quantity }}</td>
                                            </tr>
                                            @if($jadwal->serial_number)
                                            <tr>
                                                <td style="padding: 5px 0; color: #718096;">Serial Number</td>
                                                <td style="padding: 5px 0; color: #2d3748; font-weight: bold;">: {{ $jadwal->serial_number }}</td>
                                            </tr>
                                            @endif
                                            @if($jadwal->kapasitas)
                                            <tr>
                                                <td style="padding: 5px 0; color: #718096;">Kapasitas</td>
                                                <td style="padding: 5px 0; color: #2d3748; font-weight: bold;">: {{ $jadwal->kapasitas }}</td>
                                            </tr>
                                            @endif
                                            @if($jadwal->merek)
                                            <tr>
                                                <td style="padding: 5px 0; color: #718096;">Merek</td>
                                                <td style="padding: 5px 0; color: #2d3748; font-weight: bold;">: {{ $jadwal->merek }}</td>
                                            </tr>
                                            @endif
                                            @if($jadwal->tipe)
                                            <tr>
                                                <td style="padding: 5px 0; color: #718096;">Tipe</td>
                                                <td style="padding: 5px 0; color: #2d3748; font-weight: bold;">: {{ $jadwal->tipe }}</td>
                                            </tr>
                                            @endif
                                            @if($jadwal->tahun_pembuatan)
                                            <tr>
                                                <td style="padding: 5px 0; color: #718096;">Tahun Pembuatan</td>
                                                <td style="padding: 5px 0; color: #2d3748; font-weight: bold;">: {{ $jadwal->tahun_pembuatan }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td style="padding: 5px 0; color: #718096;">Tanggal Selesai</td>
                                                <td style="padding: 5px 0; color: #c53030; font-weight: bold;">
                                                    : {{ \Carbon\Carbon::parse($jadwal->tanggal_selesai)->format('d F Y') }}
                                                </td>
                                            </tr>
                                            @if($jadwal->keterangan)
                                            <tr>
                                                <td style="padding: 5px 0; color: #718096; vertical-align: top;">Keterangan</td>
                                                <td style="padding: 5px 0; color: #2d3748;">: {{ $jadwal->keterangan }}</td>
                                            </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding: 10px 0 25px 0;">
                                        <a href="{{ url('/tenagakerja/checklist') }}"
                                           style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; padding: 14px 40px; border-radius: 40px; font-size: 14px; font-weight: bold; letter-spacing: 0.5px;">
                                            Lihat Checklist Saya
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 12px; color: #a0aec0; line-height: 1.6; margin: 0; text-align: center;">
                                Email ini dikirim otomatis oleh sistem. Mohon tidak membalas email ini.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background: #1a1a2e; padding: 20px 40px; text-align: center;">
                            <p style="color: #ffffff; font-size: 12px; margin: 0 0 4px 0; font-weight: bold;">
                                PT Waskita Energi
                            </p>
                            <p style="color: #a0aec0; font-size: 11px; margin: 0;">
                                Jl. Industri No. 123, Jakarta | (021) 1234-5678
                            </p>
                            <p style="color: #718096; font-size: 10px; margin: 10px 0 0 0;">
                                © {{ date('Y') }} Sistem Manajemen Onderdil. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>