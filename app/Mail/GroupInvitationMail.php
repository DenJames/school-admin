<?php

namespace App\Mail;

use App\Models\GroupInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GroupInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public GroupInvitation $invitation, public string $acceptUrl)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Group Invitation',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.group-invitation',
            with: ['acceptUrl' => $this->acceptUrl],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
