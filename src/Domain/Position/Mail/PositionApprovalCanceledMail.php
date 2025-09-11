<?php

declare(strict_types=1);

namespace Domain\Position\Mail;

use App\Mail\QueueMailable;
use Domain\Company\Models\CompanyContact;
use Domain\Notification\Enums\NotificationTypeEnum;
use Domain\Position\Models\Position;
use Domain\User\Models\User;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\Attributes\WithoutRelations;

class PositionApprovalCanceledMail extends QueueMailable
{
    public function __construct(
        #[WithoutRelations]
        private readonly User|CompanyContact $notifiable,
        #[WithoutRelations]
        private readonly Position $position,
        #[WithoutRelations]
        private readonly User $canceledBy,
    ) {
        parent::__construct();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                new Address(address: $this->notifiable->email, name: $this->notifiable->full_name),
            ],
            subject: __n(NotificationTypeEnum::POSITION_APPROVAL_CANCELED, 'mail', 'subject', [
                'position' => $this->position->name,
            ]),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'position::mail.canceled',
            with: [
                'notifiable' => $this->notifiable,
                'position' => $this->position,
                'canceledBy' => $this->canceledBy,
            ]
        );
    }
}
