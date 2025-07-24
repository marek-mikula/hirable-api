<?php

declare(strict_types=1);

namespace Domain\Position\Repositories\Inputs;

use Carbon\Carbon;

readonly class PositionUpdateInput
{
    /**
     * @param string[] $workloads
     * @param string[] $employmentRelationships
     * @param string[] $employmentForms
     * @param string[] $seniority
     * @param string[] $benefits
     * @param array[] $languageRequirements
     */
    public function __construct(
        public string $name,
        public string $externName,
        public ?Carbon $approveUntil,
        public ?string $approveMessage,
        public ?string $department,
        public ?string $field,
        public int $jobSeatsNum,
        public string $description,
        public ?string $address,
        public int $salaryFrom,
        public ?int $salaryTo,
        public string $salaryType,
        public string $salaryFrequency,
        public string $salaryCurrency,
        public ?string $salaryVar,
        public ?string $minEducationLevel,
        public ?string $educationField,
        public array $seniority,
        public ?int $experience,
        public ?string $hardSkills,
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
        public int $hardSkillsWeight,
        public int $softSkillsWeight,
        public int $languageSkillsWeight,
        public int $experienceWeight,
        public int $educationWeight,
        public bool $shareSalary,
        public bool $shareContact,
    ) {
    }
}
