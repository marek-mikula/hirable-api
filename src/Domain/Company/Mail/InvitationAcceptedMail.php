<?php

declare(strict_types=1);

namespace Domain\Company\Mail;

use App\Enums\NotificationTypeEnum;
use App\Mail\Mailable;
use App\Models\User;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\Attributes\WithoutRelations;

class InvitationAcceptedMail extends Mailable
{
    public function __construct(
        #[WithoutRelations]
        private readonly User $notifiable,
        #[WithoutRelations]
        private readonly User $user,
    ) {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                new Address(address: $this->notifiable->email, name: $this->notifiable->full_name),
            ],
            subject: __n(NotificationTypeEnum::INVITATION_ACCEPTED, 'mail', 'subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'invitation::mail.invitation.accepted',
            with: [
                'notifiable' => $this->notifiable,
                'user' => $this->user,
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
