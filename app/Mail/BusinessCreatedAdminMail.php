<?php

namespace App\Mail;

use App\Models\Business;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BusinessCreatedAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Business $business)
    {
        $this->business->loadMissing(['services', 'employees', 'schedules']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "🎉 Nuevo negocio en Citora: {$this->business->name}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.business-created-admin',
        );
    }
}
