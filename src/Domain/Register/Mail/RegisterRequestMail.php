<?php

declare(strict_types=1);

namespace Domain\Register\Mail;

use App\Mail\Mailable;
use Domain\Notification\Enums\NotificationTypeEnum;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Queue\Attributes\WithoutRelations;
use Support\Token\Models\Token;

class RegisterRequestMail extends Mailable
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
            subject: __n(NotificationTypeEnum::REGISTER_REQUEST, 'mail', 'subject', [
                'application' => (string) config('app.name'),
            ]),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'register::mail.request',
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
