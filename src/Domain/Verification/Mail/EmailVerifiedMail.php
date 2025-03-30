<?php

namespace Domain\Verification\Mail;

use App\Enums\NotificationTypeEnum;
use App\Mail\Mailable;
use App\Models\User;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\Attributes\WithoutRelations;

class EmailVerifiedMail extends Mailable
{
    public function __construct(
        #[WithoutRelations]
        private readonly User $notifiable,
    ) {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                new Address(address: $this->notifiable->email, name: $this->notifiable->full_name),
            ],
            subject: __n(NotificationTypeEnum::VERIFICATION_EMAIL_VERIFIED, 'mail', 'subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'verification::mail.email-verified',
            with: [
                'notifiable' => $this->notifiable,
            ]
        );
    }

    /**
     * @return Attachment[]
     */
    public function attachments(): array
    {
        return [];
    }
}
