<?php

declare(strict_types=1);

namespace Domain\Register\Mail;

use App\Enums\NotificationTypeEnum;
use App\Mail\QueueMailable;
use App\Models\User;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\Attributes\WithoutRelations;

class RegisterRegisteredMail extends QueueMailable
{
    public function __construct(
        #[WithoutRelations]
        private readonly User $notifiable,
    ) {
        parent::__construct();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                new Address(address: $this->notifiable->email, name: $this->notifiable->full_name),
            ],
            subject: __n(NotificationTypeEnum::REGISTER_REGISTERED, 'mail', 'subject', [
                'application' => (string) config('app.name'),
            ]),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'register::mail.registered',
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
