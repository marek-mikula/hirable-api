<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request\Data;

use Carbon\Carbon;
use Domain\Position\Enums\PositionOperationEnum;
use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;
use Support\File\Data\FileData;

class PositionData extends Data
{
    public PositionOperationEnum $operation;

    public string $name;

    public string $externName;

    public ?string $department;

    public ?string $field;

    public int $jobSeatsNum;

    public string $description;

    public ?string $address;

    public ?int $salaryFrom;

    public ?int $salaryTo;

    public ?int $salary;

    public string $salaryType;

    public string $salaryFrequency;

    public string $salaryCurrency;

    public ?string $salaryVar;

    public ?string $minEducationLevel;

    public ?int $experience;

    public ?string $hardSkills;

    public int $organisationSkills;

    public int $teamSkills;

    public int $timeManagement;

    public int $communicationSkills;

    public int $leadership;

    public ?string $note;

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

    public ?Carbon $approveUntil;

    public ?string $approveMessage;

    public int $hardSkillsWeight;

    public int $softSkillsWeight;

    public int $languageSkillsWeight;

    public bool $shareSalary;

    public bool $shareContact;

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
