<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request\Data;

use Carbon\Carbon;
use Domain\Position\Enums\PositionOperationEnum;
use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;
use Support\File\Data\FileData;

class PositionStoreData extends Data
{
    public PositionOperationEnum $operation;

    public string $name;

    public string $externName;

    public ?string $department = null;

    public ?string $field = null;

    public int $jobSeatsNum;

    public string $description;

    public ?string $address = null;

    public ?int $salaryFrom = null;

    public ?int $salaryTo = null;

    public ?int $salary = null;

    public string $salaryType;

    public string $salaryFrequency;

    public string $salaryCurrency;

    public ?string $salaryVar = null;

    public ?string $minEducationLevel = null;

    public ?string $educationField = null;

    public ?int $experience = null;

    public ?string $hardSkills = null;

    public int $organisationSkills;

    public int $teamSkills;

    public int $timeManagement;

    public int $communicationSkills;

    public int $leadership;

    public ?string $note = null;

    /** @var string[] */
    public array $seniority;

    /** @var string[] */
    public array $workloads;

    /** @var string[] */
    public array $employmentRelationships;

    /** @var string[] */
    public array $employmentForms;

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

    public int $hardSkillsWeight;

    public int $softSkillsWeight;

    public int $languageSkillsWeight;

    public int $experienceWeight;

    public int $educationWeight;

    public bool $shareSalary;

    public bool $shareContact;

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
        return array_map(fn (UploadedFile $file) => FileData::make($file), $this->files);
    }
}
