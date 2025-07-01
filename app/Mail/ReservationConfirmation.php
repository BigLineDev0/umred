<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation de votre réservation - ' . $this->reservation->laboratoire->nom,
        );
    }

    public function content(): Content
    {
        return new Content(
            html: 'emails.reservation-confirmation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
