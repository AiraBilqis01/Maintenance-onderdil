<?php

namespace App\Mail;

use App\Models\JadwalPemeliharaan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PengingatJadwalPemeliharaanMail extends Mailable
{
    use Queueable, SerializesModels;

    public $jadwal;
    public $jenisPengingat;

    public function __construct(
        JadwalPemeliharaan $jadwal,
        string $jenisPengingat
    ) {
        $this->jadwal = $jadwal;
        $this->jenisPengingat = $jenisPengingat;
    }

    public function envelope(): Envelope
    {
        $judulPengingat = $this->jenisPengingat === 'h_1'
            ? 'H-1 Tenggat'
            : 'Hari H Tenggat';

        return new Envelope(
            subject: 'Pengingat Jadwal Pemeliharaan ('
                . $judulPengingat . ') - '
                . $this->jadwal->judul_pemeliharaan,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pengingat-jadwal-pemeliharaan',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
