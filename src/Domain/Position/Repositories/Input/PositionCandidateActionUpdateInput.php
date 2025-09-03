<?php

declare(strict_types=1);

namespace Domain\Position\Repositories\Input;

use Carbon\Carbon;
use Domain\Position\Enums\ActionAssessmentCenterResultEnum;
use Domain\Position\Enums\ActionInterviewResultEnum;
use Domain\Position\Enums\OfferStateEnum;

readonly class PositionCandidateActionUpdateInput
{
    public function __construct(
        public ?Carbon $date = null,
        public ?Carbon $timeStart = null,
        public ?Carbon $timeEnd = null,
        public ?string $place = null,
        public ?string $instructions = null,
        public ?string $evaluation = null,
        public ?string $name = null,
        public ?string $interviewForm = null,
        public ?string $interviewType = null,
        public ?ActionInterviewResultEnum $interviewResult = null,
        public ?ActionAssessmentCenterResultEnum $assessmentCenterResult = null,
        public ?bool $rejectedByCandidate = null,
        public ?string $rejectionReason = null,
        public ?string $refusalReason = null,
        public ?string $testType = null,
        public ?OfferStateEnum $offerState = null,
        public ?string $offerJobTitle = null,
        public ?string $offerCompany = null,
        public ?array $offerEmploymentForms = null,
        public ?string $offerPlace = null,
        public ?int $offerSalary = null,
        public ?string $offerSalaryCurrency = null,
        public ?string $offerSalaryFrequency = null,
        public ?string $offerWorkload = null,
        public ?string $offerEmploymentRelationship = null,
        public ?Carbon $offerStartDate = null,
        public ?string $offerEmploymentDuration = null,
        public ?Carbon $offerCertainPeriodTo = null,
        public ?int $offerTrialPeriod = null,
        public ?string $offerCandidateNote = null,
        public ?Carbon $realStartDate = null,
        public ?string $note = null,
    ) {
    }
}
