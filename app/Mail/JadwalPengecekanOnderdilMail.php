<?php

namespace App\Mail;

use App\Models\JadwalPengecekanOnderdil;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JadwalPengecekanOnderdilMail extends Mailable
{
    use Queueable, SerializesModels;

    public $jadwal;

    public function __construct(
        JadwalPengecekanOnderdil $jadwal) {
        $this->jadwal = $jadwal;
    }


    public function envelope(): Envelope
    {
        return new Envelope(
            subject:'Jadwal Pengecekan Sparepart Baru - ' .$this->jadwal->judul_pengecekan,
        );
    }


    public function content(): Content {
        return new Content(
            view:'emails.jadwal-pengecekan-onderdil',
        );
    }


    public function attachments(): array {
        return [];
    }
}
