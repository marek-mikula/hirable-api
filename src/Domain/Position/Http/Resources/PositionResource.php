<?php

declare(strict_types=1);

namespace Domain\Position\Http\Resources;

use App\Http\Resources\Traits\ChecksRelations;
use Domain\Company\Http\Resources\Collection\CompanyContactCollection;
use Domain\Position\Http\Resources\Collections\PositionApprovalCollection;
use Domain\Position\Models\Position;
use Domain\User\Http\Resources\Collections\UserCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Support\Classifier\Actions\ToClassifierAction;
use Support\Classifier\Enums\ClassifierTypeEnum;
use Support\Classifier\Http\Resources\ClassifierResource;
use Support\Classifier\Http\Resources\Collections\ClassifierCollection;
use Support\File\Http\Resources\Collections\FileCollection;

/**
 * @property Position $resource
 */
class PositionResource extends JsonResource
{
    use ChecksRelations;

    public function __construct(Position $resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        $this->checkLoadedRelations([
            'files',
            'hiringManagers',
            'approvers',
            'externalApprovers',
            'approvals',
        ], Position::class);

        $toClassifier = ToClassifierAction::make();

        return [
            'id' => $this->resource->id,
            'state' => $this->resource->state->value,
            'approvalState' => $this->resource->approval_state?->value,
            'approvalRound' => $this->resource->approval_round,
            'name' => $this->resource->name,
            'department' => $this->resource->department,
            'field' => $this->resource->field
                ? new ClassifierResource($toClassifier->handle($this->resource->field, ClassifierTypeEnum::FIELD))
                : null,
            'workloads' => new ClassifierCollection($toClassifier->handle($this->resource->workloads, ClassifierTypeEnum::WORKLOAD)),
            'employmentRelationships' => new ClassifierCollection($toClassifier->handle($this->resource->employment_relationships, ClassifierTypeEnum::EMPLOYMENT_RELATIONSHIP)),
            'employmentForms' => new ClassifierCollection($toClassifier->handle($this->resource->employment_forms, ClassifierTypeEnum::EMPLOYMENT_FORM)),
            'jobSeatsNum' => $this->resource->job_seats_num,
            'description' => $this->resource->description,
            'isTechnical' => $this->resource->is_technical,
            'address' => $this->resource->address,
            'salaryFrom' => $this->resource->salary_from,
            'salaryTo' => $this->resource->salary_to,
            'salaryType' => new ClassifierResource($toClassifier->handle($this->resource->salary_type, ClassifierTypeEnum::SALARY_TYPE)),
            'salaryFrequency' => new ClassifierResource($toClassifier->handle($this->resource->salary_frequency, ClassifierTypeEnum::SALARY_FREQUENCY)),
            'salaryCurrency' => new ClassifierResource($toClassifier->handle($this->resource->salary_currency, ClassifierTypeEnum::CURRENCY)),
            'salaryVar' => $this->resource->salary_var,
            'benefits' => new ClassifierCollection($toClassifier->handle($this->resource->benefits, ClassifierTypeEnum::BENEFIT)),
            'minEducationLevel' => $this->resource->min_education_level
                ? new ClassifierResource($toClassifier->handle($this->resource->min_education_level, ClassifierTypeEnum::EDUCATION_LEVEL))
                : null,
            'seniority' => $this->resource->seniority
                ? new ClassifierResource($toClassifier->handle($this->resource->seniority, ClassifierTypeEnum::SENIORITY))
                : null,
            'experience' => $this->resource->experience,
            'drivingLicences' => new ClassifierCollection($toClassifier->handle($this->resource->driving_licences, ClassifierTypeEnum::DRIVING_LICENCE)),
            'organisationSkills' => $this->resource->organisation_skills,
            'teamSkills' => $this->resource->team_skills,
            'timeManagement' => $this->resource->time_management,
            'communicationSkills' => $this->resource->communication_skills,
            'leadership' => $this->resource->leadership,
            'languageRequirements' => array_map(function (array $requirement) use ($toClassifier): array {
                $requirement['language'] = new ClassifierResource($toClassifier->handle($requirement['language'], ClassifierTypeEnum::LANGUAGE));
                $requirement['level'] = new ClassifierResource($toClassifier->handle($requirement['level'], ClassifierTypeEnum::LANGUAGE_LEVEL));

                return $requirement;
            }, $this->resource->language_requirements),
            'note' => $this->resource->note,
            'createdAt' => $this->resource->created_at->toIso8601String(),
            'updatedAt' => $this->resource->updated_at->toIso8601String(),
            'files' => new FileCollection($this->resource->files),
            'hiringManagers' => new UserCollection($this->resource->hiringManagers),
            'approvers' => new UserCollection($this->resource->approvers),
            'externalApprovers' => new CompanyContactCollection($this->resource->externalApprovers),
            'approvals' => new PositionApprovalCollection($this->resource->approvals),
        ];
    }
}
