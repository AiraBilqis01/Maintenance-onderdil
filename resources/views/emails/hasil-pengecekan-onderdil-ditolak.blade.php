<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Perbaikan Hasil Pengecekan Sparepart</title>
</head>

<body style="margin: 0; padding: 0; background: #f5f0ff; font-family: Arial, sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background: #f5f0ff; padding: 30px 0;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style=" background: #ffffff; border-radius: 16px; overflow: hidden;">
                {{-- ================================================= --}}
                {{-- HEADER --}}
                {{-- ================================================= --}}
                <tr>
                    <td style="background: #c53030; padding: 30px 40px; text-align: center;">
                        <h1 style="color: #ffffff; margin: 0; font-size: 22px;">
                            Hasil Pengecekan Sparepart Perlu Diperbaiki
                        </h1>
                        <p style="color: #ffffff; margin: 8px 0 0 0; font-size: 13px;">
                            PLTM Sangir Hulu - Sistem Manajemen Pemeliharaan dan Persediaan
                        </p>
                    </td>
                </tr>
                {{-- ================================================= --}}
                {{-- BODY --}}
                {{-- ================================================= --}}
                <tr>
                    <td style="padding: 35px 40px;">
                        {{-- SALAM --}}
                        <p style="font-size: 15px; color: #2d3748;">
                            Halo <strong>{{ $jadwal->tenagaKerja->name ?? 'Tenaga Kerja' }}</strong>,
                        </p>
                        <p style="font-size: 14px; color: #4a5568; line-height: 1.6;">
                            Hasil pengecekan sparepart yang Anda kirimbelum disetujui oleh Supervisor.
                            Silakan periksa catatan dari Supervisor, kemudian lakukan pengecekan ulang dan kirim kembali hasil pengecekan.
                        </p>
                        {{-- ================================================= --}}
                        {{-- DETAIL JADWAL --}}
                        {{-- ================================================= --}}
                        <table width="100%" cellpadding="0" cellspacing="0" style="background: #f7fafc; border-left: 4px solid #667eea; border-radius: 8px; margin-bottom: 25px;">
                            <tr>
                                <td style="padding: 20px 25px;">
                                    <h2 style="font-size: 17px; color: #1a1a2e; margin: 0 0 15px 0;">
                                        {{ $jadwal->judul_pengecekan }}
                                    </h2>
                                    <table width="100%" cellpadding="0" cellspacing="0" style="font-size: 13px;">
                                        {{-- TANGGAL PENGECEKAN --}}
                                        <tr>
                                            <td style="padding: 5px 0; color: #718096; width: 160px;">
                                                Tanggal Pengecekan
                                            </td>
                                            <td style="padding: 5px 0; color: #2d3748; font-weight: bold;">
                                                : {{ \Carbon\Carbon::parse($jadwal->tanggal_pengecekan)->format('d/m/Y') }}
                                            </td>
                                        </tr>
                                        {{-- TENAGA KERJA --}}
                                        <tr>
                                            <td style="padding: 5px 0; color: #718096;">
                                                Tenaga Kerja
                                            </td>
                                            <td style="padding: 5px 0; color: #2d3748; font-weight: bold;">
                                                : {{ $jadwal->tenagaKerja->name ?? '-' }}
                                            </td>
                                        </tr>
                                        {{-- KETERANGAN JADWAL --}}
                                        @if($jadwal->keterangan)
                                            <tr>
                                                <td style="padding: 5px 0; color: #718096; vertical-align: top;">
                                                    Keterangan
                                                </td>
                                                <td style="padding: 5px 0; color: #2d3748;">
                                                    : {{ $jadwal->keterangan }}
                                                </td>
                                            </tr>
                                        @endif
                                    </table>

                                    {{-- ===================================== --}}
                                    {{-- DAFTAR ONDERDIL --}}
                                    {{-- ===================================== --}}
                                    <div style="margin-top: 18px;">
                                        <p style="font-size: 13px; color: #718096; margin: 0 0 8px 0;">
                                            Sparepart yang harus diperiksa:
                                        </p>
                                        @forelse($jadwal->details as $detail)
                                            <div style="display: block; background: #edf2f7; color: #2d3748; padding: 8px 12px; border-radius: 6px; margin-bottom: 6px; font-size: 13px;">
                                                • {{ $detail->nama_sparepart_snapshot }}
                                            </div>
                                        @empty
                                            <p style="color: #718096; font-size: 13px;">Tidak ada data sparepart.</p>
                                        @endforelse
                                    </div>
                                </td>
                            </tr>
                        </table>

                        {{-- ================================================= --}}
                        {{-- CATATAN SUPERVISOR --}}
                        {{-- ================================================= --}}
                        <table width="100%" cellpadding="0" cellspacing="0" style="background: #fff5f5; border-left: 4px solid #e53e3e; margin-bottom: 25px;">
                            <tr>
                                <td style="padding: 20px;">
                                    <h3 style="color: #c53030; margin-top: 0;">
                                        Catatan dari Supervisor
                                    </h3>
                                    <p style="color: #2d3748; line-height: 1.5;">
                                        <strong>Keterangan: </strong><br> {{ $jadwal->keterangan_tolak ?? '-' }}
                                    </p>
                                    <p style="color: #2d3748; line-height: 1.5; margin-bottom: 0;">
                                        <strong>Alasan Penolakan: </strong><br> {{ $jadwal->alasan_penolakan ?? '-' }}
                                    </p>
                                </td>
                            </tr>
                        </table>
                        {{-- ================================================= --}}
                        {{-- INFORMASI --}}
                        {{-- ================================================= --}}
                        <table width="100%" cellpadding="0" cellspacing="0" style="background: #fffaf0; border-left: 4px solid #ed8936; margin-bottom: 25px;">
                            <tr>
                                <td style=" padding: 15px 20px; color: #744210; font-size: 13px; line-height: 1.5;">
                                    Status jadwal telah dikembalikan menjadi <strong>Pending</strong>.
                                    Silakan lakukan pengecekan ulang sesuai catatan dari Supervisor.
                                </td>
                            </tr>
                        </table>
                        {{-- ================================================= --}}
                        {{-- TOMBOL --}}
                        {{-- ================================================= --}}
                        <div style=" text-align: center; margin: 25px 0;">
                            <a href="{{ url('/tenagakerja/pengecekan-onderdil') }}" style="display: inline-block; background: #667eea; color: #ffffff; text-decoration: none; padding: 14px 35px; border-radius: 30px; font-weight: bold;">Lakukan Pengecekan Ulang</a>
                        </div>
                        <p style="font-size: 12px; color: #a0aec0; text-align: center;">Email ini dikirim otomatis oleh sistem.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
