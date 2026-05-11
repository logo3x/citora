<?php

namespace App\Mail;

use App\Models\EmailCampaign;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;

class CampaignMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public EmailCampaign $campaign,
        public ?int $recipientUserId = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->campaign->subject,
            from: new Address(
                config('mail.from.address'),
                config('mail.from.name', 'Citora'),
            ),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.campaign',
            text: 'emails.campaign-text',
            with: [
                'subject' => $this->campaign->subject,
                'bodyMarkdown' => $this->campaign->body_markdown,
                'campaignId' => $this->campaign->id,
                'recipientUserId' => $this->recipientUserId,
                'pixelUrl' => $this->recipientUserId
                    ? url("/m/{$this->campaign->id}/open.gif?u={$this->recipientUserId}")
                    : null,
                'unsubscribeUrl' => url('/mis-citas'),
            ],
        );
    }

    public function headers(): Headers
    {
        return new Headers(
            text: [
                'List-Unsubscribe' => '<'.url('/mis-citas').'>',
                'List-Unsubscribe-Post' => 'List-Unsubscribe=One-Click',
            ],
        );
    }
}
