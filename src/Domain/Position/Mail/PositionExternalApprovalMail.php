<?php

declare(strict_types=1);

namespace Domain\Position\Mail;

use App\Mail\QueueMailable;
use Domain\Company\Models\CompanyContact;
use Domain\Position\Models\Position;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\Attributes\WithoutRelations;
use Support\Notification\Enums\NotificationTypeEnum;

class PositionExternalApprovalMail extends QueueMailable
{
    public function __construct(
        #[WithoutRelations]
        private readonly CompanyContact $notifiable,
        #[WithoutRelations]
        private readonly Position $position,
    ) {
        parent::__construct();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                new Address(address: $this->notifiable->email, name: $this->notifiable->full_name),
            ],
            subject: __n(NotificationTypeEnum::POSITION_EXTERNAL_APPROVAL, 'mail', 'subject', [
                'position' => $this->position->name,
            ]),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'position::mail.external-approval',
            with: [
                'notifiable' => $this->notifiable,
                'position' => $this->position,
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
