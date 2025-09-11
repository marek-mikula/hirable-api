<?php

declare(strict_types=1);

namespace Domain\Position\Mail;

use App\Mail\QueueMailable;
use Domain\Notification\Enums\NotificationTypeEnum;
use Domain\Position\Models\PositionCandidateEvaluation;
use Domain\User\Models\User;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\Attributes\WithoutRelations;

class PositionCandidateEvaluationRequestedMail extends QueueMailable
{
    public function __construct(
        #[WithoutRelations]
        private readonly User $notifiable,
        #[WithoutRelations]
        private readonly PositionCandidateEvaluation $positionCandidateEvaluation,
    ) {
        parent::__construct();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                new Address(address: $this->notifiable->email, name: $this->notifiable->full_name),
            ],
            subject: __n(NotificationTypeEnum::POSITION_CANDIDATE_EVALUATION_REQUESTED, 'mail', 'subject', [
                'position' => $this->positionCandidateEvaluation->positionCandidate->position->name,
                'candidate' => $this->positionCandidateEvaluation->positionCandidate->candidate->full_name,
            ]),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'position::mail.position_candidate_evaluation_requested',
            with: [
                'notifiable' => $this->notifiable,
                'positionCandidateEvaluation' => $this->positionCandidateEvaluation,
            ]
        );
    }
}
