<?php

namespace App\Mail;

use App\Models\JadwalPengecekanOnderdil;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PenugasanPengecekanOnderdilDialihkanMail extends Mailable
{
    use Queueable, SerializesModels;

    public $jadwal;
    public $tenagaKerjaLama;

    public function __construct(
        JadwalPengecekanOnderdil $jadwal,
        User $tenagaKerjaLama
    ) {
        $this->jadwal = $jadwal;
        $this->tenagaKerjaLama = $tenagaKerjaLama;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Penugasan Pengecekan Sparepart Dialihkan - ' . $this->jadwal->judul_pengecekan,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.penugasan-pengecekan-onderdil-dialihkan',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
