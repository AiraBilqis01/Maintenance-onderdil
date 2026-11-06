<?php

namespace App\Mail;

use App\Models\JadwalPemeliharaan;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PenugasanPemeliharaanDialihkanMail extends Mailable
{
    use Queueable, SerializesModels;

    public $jadwal;
    public $tenagaKerjaLama;

    public function __construct(JadwalPemeliharaan $jadwal, User $tenagaKerjaLama)
    {
        $this->jadwal = $jadwal;
        $this->tenagaKerjaLama = $tenagaKerjaLama;
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Penugasan Pemeliharaan Dialihkan - ' .  $this->jadwal->judul_pemeliharaan,);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.penugasan-pemeliharaan-dialihkan',);
    }

    public function attachments(): array
    {
        return [];
    }
}
