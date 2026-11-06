<?php

namespace App\Mail;

use App\Models\JadwalPengecekanOnderdil;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JadwalPengecekanOnderdilDiperbaruiMail extends Mailable
{
    use Queueable, SerializesModels;

    public $jadwal;

    public function __construct(JadwalPengecekanOnderdil $jadwal)
    {
        $this->jadwal = $jadwal;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Perubahan Jadwal Pengecekan Sparepart - ' . $this->jadwal->judul_pengecekan,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.jadwal-pengecekan-onderdil-diperbarui',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
