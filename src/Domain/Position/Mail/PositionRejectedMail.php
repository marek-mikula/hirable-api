<?php

declare(strict_types=1);

namespace Domain\Position\Mail;

use App\Mail\QueueMailable;
use Domain\Company\Models\CompanyContact;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionApproval;
use Domain\User\Models\User;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\Attributes\WithoutRelations;
use Support\Notification\Enums\NotificationTypeEnum;

class PositionRejectedMail extends QueueMailable
{
    public function __construct(
        #[WithoutRelations]
        private readonly User|CompanyContact $notifiable,
        #[WithoutRelations]
        private readonly User|CompanyContact $rejectedBy,
        #[WithoutRelations]
        private readonly PositionApproval $approval,
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
            subject: __n(NotificationTypeEnum::POSITION_REJECTED, 'mail', 'subject', [
                'position' => $this->position->name,
            ]),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'position:mail.rejected',
            with: [
                'notifiable' => $this->notifiable,
                'rejectedBy' => $this->rejectedBy,
                'approval' => $this->approval,
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
