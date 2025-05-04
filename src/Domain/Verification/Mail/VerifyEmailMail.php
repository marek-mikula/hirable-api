<?php

declare(strict_types=1);

namespace Domain\Verification\Mail;

use App\Mail\Mailable;
use Domain\User\Models\User;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\Attributes\WithoutRelations;
use Support\Notification\Enums\NotificationTypeEnum;
use Support\Token\Models\Token;

class VerifyEmailMail extends Mailable
{
    public function __construct(
        #[WithoutRelations]
        private readonly User $notifiable,
        #[WithoutRelations]
        private readonly Token $token,
    ) {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                new Address(address: $this->notifiable->email, name: $this->notifiable->full_name),
            ],
            subject: __n(NotificationTypeEnum::VERIFICATION_VERIFY_EMAIL, 'mail', 'subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'verification::mail.verify-email',
            with: [
                'notifiable' => $this->notifiable,
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
