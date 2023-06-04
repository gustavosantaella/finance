<?php

namespace App\Mail;

use App\Helpers\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use stdClass;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public stdClass $data;
    /**
     * Create a new message instance.
     */
    public function __construct(array $fields)
    {
        $obj = new stdClass();
        foreach($fields as $key => $field){
            $obj->$key  = $field;
        }

        $this->data = $obj;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $this->data->appIcon = asset('/images/app_icon.png');
        return new Envelope(
            subject: $this->data?->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.resetPassword',
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
