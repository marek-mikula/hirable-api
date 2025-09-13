<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request\Data;

use Carbon\Carbon;
use Domain\Position\Enums\PositionOperationEnum;
use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;
use Support\File\Data\FileData;

class PositionUpdateData extends Data
{
    public PositionOperationEnum $operation;

    /** @var string[] */
    public array $keys;

    public ?string $name = null;

    public ?string $externName = null;

    public ?string $department = null;

    public ?string $field = null;

    public ?int $jobSeatsNum = null;

    public ?string $description = null;

    public ?string $address = null;

    public ?int $salaryFrom = null;

    public ?int $salaryTo = null;

    public ?int $salary = null;

    public ?string $salaryType = null;

    public ?string $salaryFrequency = null;

    public ?string $salaryCurrency = null;

    public ?string $salaryVar = null;

    public ?string $minEducationLevel = null;

    public ?string $educationField = null;

    public ?int $experience = null;

    public ?string $hardSkills = null;

    public ?int $organisationSkills = null;

    public ?int $teamSkills = null;

    public ?int $timeManagement = null;

    public ?int $communicationSkills = null;

    public ?int $leadership = null;

    public ?string $note = null;

    /** @var string[] */
    public array $workloads;

    /** @var string[] */
    public array $employmentRelationships;

    /** @var string[] */
    public array $employmentForms;

    /** @var string[] */
    public array $seniority;

    /** @var string[] */
    public array $benefits;

    /** @var UploadedFile[] */
    public array $files;

    /** @var LanguageRequirementData[] */
    public array $languageRequirements;

    /** @var int[] */
    public array $hiringManagers;

    /** @var int[] */
    public array $recruiters;

    /** @var int[] */
    public array $approvers;

    /** @var int[] */
    public array $externalApprovers;

    public ?Carbon $approveUntil = null;

    public ?string $approveMessage = null;

    public ?int $hardSkillsWeight = null;

    public ?int $softSkillsWeight = null;

    public ?int $languageSkillsWeight = null;

    public ?int $experienceWeight = null;

    public ?int $educationWeight = null;

    public ?bool $shareSalary = null;

    public ?bool $shareContact = null;

    /** @var string[] */
    public array $tags;

    public function hasFiles(): bool
    {
        return !empty($this->files);
    }

    public function hasHiringManagers(): bool
    {
        return !empty($this->hiringManagers);
    }

    public function hasRecruiters(): bool
    {
        return !empty($this->recruiters);
    }

    public function hasApprovers(): bool
    {
        return !empty($this->approvers);
    }

    public function hasExternalApprovers(): bool
    {
        return !empty($this->externalApprovers);
    }

    public function hasAnyApprovers(): bool
    {
        return $this->hasApprovers() || $this->hasExternalApprovers();
    }

    /**
     * @return FileData[]
     */
    public function getFilesData(): array
    {
        return array_map(fn (UploadedFile $file): \Support\File\Data\FileData => FileData::make($file), $this->files);
    }

    public function hasKey(string $key): bool
    {
        return in_array($key, $this->keys);
    }
}
