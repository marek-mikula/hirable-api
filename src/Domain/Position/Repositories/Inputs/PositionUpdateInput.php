<?php

declare(strict_types=1);

namespace Domain\Position\Repositories\Inputs;

use Carbon\Carbon;
use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Enums\PositionStateEnum;

readonly class PositionUpdateInput
{
    /**
     * @param string[] $drivingLicences
     * @param string[] $workloads
     * @param string[] $employmentRelationships
     * @param string[] $employmentForms
     * @param string[] $benefits
     * @param array[] $languageRequirements
     */
    public function __construct(
        public PositionStateEnum $state,
        public ?PositionApprovalStateEnum $approvalState,
        public ?int $approvalRound,
        public ?Carbon $approveUntil,
        public string $name,
        public ?string $department,
        public ?string $field,
        public int $jobSeatsNum,
        public string $description,
        public bool $isTechnical,
        public ?string $address,
        public ?int $salaryFrom,
        public ?int $salaryTo,
        public ?int $salary,
        public string $salaryType,
        public string $salaryFrequency,
        public string $salaryCurrency,
        public ?string $salaryVar,
        public ?string $minEducationLevel,
        public ?string $seniority,
        public ?int $experience,
        public array $drivingLicences,
        public int $organisationSkills,
        public int $teamSkills,
        public int $timeManagement,
        public int $communicationSkills,
        public int $leadership,
        public ?string $note,
        public array $workloads,
        public array $employmentRelationships,
        public array $employmentForms,
        public array $benefits,
        public array $languageRequirements,
    ) {
    }
}
