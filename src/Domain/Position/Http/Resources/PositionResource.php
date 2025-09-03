<?php

declare(strict_types=1);

namespace Domain\Position\Http\Resources;

use App\Http\Resources\Collections\ResourceCollection;
use Domain\Position\Models\Position;
use Illuminate\Http\Request;
use App\Http\Resources\Resource;
use Support\Classifier\Actions\ToClassifierAction;
use Support\Classifier\Enums\ClassifierTypeEnum;
use Support\Classifier\Http\Resources\ClassifierResource;

/**
 * @property Position $resource
 */
class PositionResource extends Resource
{
    public function toArray(Request $request): array
    {
        $toClassifier = ToClassifierAction::make();

        return [
            'id' => $this->resource->id,
            'userId' => $this->resource->user_id,
            'companyId' => $this->resource->company_id,
            'name' => $this->resource->name,
            'externName' => $this->resource->extern_name,
            'state' => $this->resource->state,
            'approveUntil' => $this->resource->approve_until?->toIso8601String(),
            'approveMessage' => $this->resource->approve_message,
            'approveRound' => $this->resource->approve_round,
            'department' => $this->resource->department,
            'field' => $this->resource->field
                ? new ClassifierResource($toClassifier->handle($this->resource->field, ClassifierTypeEnum::FIELD))
                : null,
            'workloads' => new ResourceCollection(ClassifierResource::class, $toClassifier->handle($this->resource->workloads, ClassifierTypeEnum::WORKLOAD)),
            'employmentRelationships' => new ResourceCollection(ClassifierResource::class, $toClassifier->handle($this->resource->employment_relationships, ClassifierTypeEnum::EMPLOYMENT_RELATIONSHIP)),
            'employmentForms' => new ResourceCollection(ClassifierResource::class, $toClassifier->handle($this->resource->employment_forms, ClassifierTypeEnum::EMPLOYMENT_FORM)),
            'jobSeatsNum' => $this->resource->job_seats_num,
            'description' => $this->resource->description,
            'address' => $this->resource->address,
            'salary' => new PositionSalaryResource($this->resource),
            'benefits' => new ResourceCollection(ClassifierResource::class, $toClassifier->handle($this->resource->benefits, ClassifierTypeEnum::BENEFIT)),
            'minEducationLevel' => $this->resource->min_education_level
                ? new ClassifierResource($toClassifier->handle($this->resource->min_education_level, ClassifierTypeEnum::EDUCATION_LEVEL))
                : null,
            'educationField' => $this->resource->education_field,
            'seniority' => new ResourceCollection(ClassifierResource::class, $toClassifier->handle($this->resource->seniority, ClassifierTypeEnum::SENIORITY)),
            'experience' => $this->resource->experience,
            'hardSkills' => $this->resource->hard_skills,
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
            'hardSkillsWeight' => $this->resource->hard_skills_weight,
            'softSkillsWeight' => $this->resource->soft_skills_weight,
            'languageSkillsWeight' => $this->resource->language_skills_weight,
            'experienceWeight' => $this->resource->experience_weight,
            'educationWeight' => $this->resource->education_weight,
            'shareSalary' => $this->resource->share_salary,
            'shareContact' => $this->resource->share_contact,
            'tags' => $this->resource->tags,
            'commonLink' => $this->resource->common_link,
            'internLink' => $this->resource->intern_link,
            'referralLink' => $this->resource->referral_link,
            'createdAt' => $this->resource->created_at->toIso8601String(),
            'updatedAt' => $this->resource->updated_at->toIso8601String(),
        ];
    }
}
