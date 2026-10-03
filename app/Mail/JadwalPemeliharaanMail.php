<?php

namespace App\Mail;

use App\Models\JadwalPemeliharaan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JadwalPemeliharaanMail extends Mailable
{
    use Queueable, SerializesModels;

    public $jadwal;

    public function __construct(JadwalPemeliharaan $jadwal)
    {
        $this->jadwal = $jadwal;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Jadwal Pemeliharaan Baru - ' . $this->jadwal->judul_pemeliharaan,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.jadwal-pemeliharaan',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}