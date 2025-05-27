<?php

declare(strict_types=1);

namespace Domain\Position\Repositories\Inputs;

use Domain\Company\Models\Company;
use Domain\Position\Enums\PositionStateEnum;
use Domain\User\Models\User;

class PositionStoreInput
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
        public Company $company,
        public User $user,
        public PositionStateEnum $state,
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
