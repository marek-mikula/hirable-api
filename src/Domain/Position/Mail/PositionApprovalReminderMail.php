<?php

declare(strict_types=1);

namespace Domain\Position\Mail;

use App\Mail\QueueMailable;
use Domain\Company\Models\CompanyContact;
use Domain\Position\Models\Position;
use Domain\User\Models\User;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\Attributes\WithoutRelations;
use Support\Notification\Enums\NotificationTypeEnum;
use Support\Token\Actions\GetTokenLinkAction;
use Support\Token\Models\Token;

class PositionApprovalReminderMail extends QueueMailable
{
    public function __construct(
        #[WithoutRelations]
        private readonly User|CompanyContact $notifiable,
        #[WithoutRelations]
        private readonly Position $position,
        #[WithoutRelations]
        private readonly ?Token $token,
    ) {
        parent::__construct();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                new Address(address: $this->notifiable->email, name: $this->notifiable->full_name),
            ],
            subject: __n(NotificationTypeEnum::POSITION_APPROVAL_REMINDER, 'mail', 'subject', [
                'position' => $this->position->name,
            ]),
        );
    }

    public function content(): Content
    {
        $link = $this->token
            ? GetTokenLinkAction::make()->handle($this->token)
            : frontendLink('/positions/{id}/edit', ['id' => $this->position->id]);

        return new Content(
            markdown: 'position::mail.reminder',
            with: [
                'notifiable' => $this->notifiable,
                'position' => $this->position,
                'link' => $link,
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
