<?php

declare(strict_types=1);

namespace Domain\Password\Mail;

use App\Mail\QueueMailable;
use Domain\Notification\Enums\NotificationTypeEnum;
use Domain\User\Models\User;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\Attributes\WithoutRelations;

class PasswordChangedMail extends QueueMailable
{
    public function __construct(
        #[WithoutRelations]
        private readonly User $notifiable
    ) {
        parent::__construct();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                new Address(address: $this->notifiable->email, name: $this->notifiable->full_name),
            ],
            subject: __n(NotificationTypeEnum::PASSWORD_CHANGED, 'mail', 'subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'password::mail.password-changed',
            with: [
                'notifiable' => $this->notifiable,
            ],
        );
    }
}
