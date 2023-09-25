<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CriticalMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(protected \Exception $exception)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    //cc carbon copy
    //bcc send coby with out to
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Critical Mailable',
            cc: 'log@exmaple.com',
            bcc: 'logbcc@exmaple.com',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            // view make html and text
            view: 'email.errors.critical',
            with: [],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            // file path
            storage_path('app/submissions/18/7wb9vqSYXjExOY0dbqmH56q2SCouaFveOP1DJh9N.jpg')
        ];
    }
}
