<?php

declare(strict_types=1);

namespace Domain\Password\Mail;

use App\Mail\QueueMailable;
use Domain\Notification\Enums\NotificationTypeEnum;
use Domain\User\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\Attributes\WithoutRelations;
use Illuminate\Queue\SerializesModels;
use Support\Token\Models\Token;

class PasswordResetRequestMail extends QueueMailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        #[WithoutRelations]
        private readonly User $notifiable,
        #[WithoutRelations]
        private readonly Token $token
    ) {
        parent::__construct();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                new Address(address: $this->notifiable->email, name: $this->notifiable->full_name),
            ],
            subject: __n(NotificationTypeEnum::PASSWORD_RESET_REQUEST, 'mail', 'subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'password::mail.reset-request',
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
