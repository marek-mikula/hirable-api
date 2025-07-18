<?php

declare(strict_types=1);

namespace Domain\Application\Mail;

use App\Mail\Mailable;
use Domain\Application\Models\Application;
use Domain\Notification\Enums\NotificationTypeEnum;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\Attributes\WithoutRelations;

class ApplicationAcceptedMail extends Mailable
{
    public function __construct(
        #[WithoutRelations]
        private readonly Application $notifiable,
    ) {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                new Address(address: $this->notifiable->email, name: $this->notifiable->full_name),
            ],
            subject: __n(NotificationTypeEnum::APPLICATION_ACCEPTED, 'mail', 'subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'application::mail.accepted',
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
