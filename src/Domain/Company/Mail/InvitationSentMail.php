<?php

namespace Domain\Company\Mail;

use App\Enums\NotificationTypeEnum;
use App\Mail\Mailable;
use App\Models\Token;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Queue\Attributes\WithoutRelations;

class InvitationSentMail extends Mailable
{
    public function __construct(
        #[WithoutRelations]
        private readonly AnonymousNotifiable $notifiable,
        #[WithoutRelations]
        private readonly Token $token,
    ) {
        //
    }

    public function envelope(): Envelope
    {
        $emailAddress = (string) $this->notifiable->routeNotificationFor('mail');

        return new Envelope(
            to: [
                new Address(address: $emailAddress),
            ],
            subject: __n(NotificationTypeEnum::INVITATION_SENT, 'mail', 'subject', [
                'application' => (string) config('app.name'),
            ]),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'invitation::mail.invitation.sent',
            with: [
                'token' => $this->token,
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
