<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Internal notification for a public "request a quote" submission.
 *
 * Deliberately plain and data-dense: this lands in the sales inbox, so the rep
 * should be able to price the job from the email body without opening anything.
 */
class QuoteRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array<string, mixed>  $lead
     */
    public function __construct(public array $lead) {}

    public function envelope(): Envelope
    {
        $company = $this->lead['company'] ?: $this->lead['name'];

        return new Envelope(
            subject: "طلب عرض سعر — {$company} — {$this->lead['city_label']}",
            // Reply-To points at the prospect so the rep can just hit reply.
            replyTo: $this->lead['email']
                ? [new Address($this->lead['email'], $this->lead['name'])]
                : [],
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.quote-request');
    }
}
