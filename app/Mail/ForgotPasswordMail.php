<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $logo;

    /**
     * Create a new message instance.
     */
    public function __construct(public string $fullName, public string $code)
    {
        $this->fullName = __('ui.mail.title', ['fullName' => $this->fullName]);
        $this->code = __('ui.mail.code', ['code' => $this->code]);
        $this->logo = app()->isLocal() ? 'https://orbit-promo.kz/images/showcase__orbit-img.png' : asset('orbit.png');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('ui.mail.subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.forgot_password',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
