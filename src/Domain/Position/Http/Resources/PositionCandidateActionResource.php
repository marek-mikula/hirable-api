<?php

declare(strict_types=1);

namespace Domain\Position\Http\Resources;

use App\Http\Resources\Collections\ResourceCollection;
use Domain\Position\Models\PositionCandidateAction;
use Illuminate\Http\Request;
use App\Http\Resources\Resource;
use Support\Classifier\Actions\ToClassifierAction;
use Support\Classifier\Enums\ClassifierTypeEnum;
use Support\Classifier\Http\Resources\ClassifierResource;

/**
 * @property PositionCandidateAction $resource
 */
class PositionCandidateActionResource extends Resource
{
    public function toArray(Request $request): array
    {
        $toClassifier = ToClassifierAction::make();

        return [
            'id' => $this->resource->id,
            'type' => $this->resource->type,
            'state' => $this->resource->state,
            'date' => $this->resource->date?->toIso8601String(),
            'timeStart' => $this->resource->time_start?->toIso8601String(),
            'timeEnd' => $this->resource->time_end?->toIso8601String(),
            'place' => $this->resource->place,
            'instructions' => $this->resource->instructions,
            'evaluation' => $this->resource->evaluation,
            'name' => $this->resource->name,
            'interviewForm' => $this->resource->interview_form ? new ClassifierResource($toClassifier->handle($this->resource->interview_form, ClassifierTypeEnum::INTERVIEW_FORM)) : null,
            'interviewType' => $this->resource->interview_type ? new ClassifierResource($toClassifier->handle($this->resource->interview_type, ClassifierTypeEnum::INTERVIEW_TYPE)) : null,
            'unavailable' => $this->resource->unavailable,
            'noShow' => $this->resource->no_show,
            'rejectedByCandidate' => $this->resource->rejected_by_candidate,
            'rejectionReason' => $this->resource->rejection_reason ? new ClassifierResource($toClassifier->handle($this->resource->rejection_reason, ClassifierTypeEnum::REJECTION_REASON)) : null,
            'refusalReason' => $this->resource->refusal_reason ? new ClassifierResource($toClassifier->handle($this->resource->refusal_reason, ClassifierTypeEnum::REFUSAL_REASON)) : null,
            'testType' => $this->resource->test_type ? new ClassifierResource($toClassifier->handle($this->resource->test_type, ClassifierTypeEnum::TEST_TYPE)) : null,
            'offerState' => $this->resource->offer_state,
            'offerJobTitle' => $this->resource->offer_job_title,
            'offerCompany' => $this->resource->offer_company,
            'offerEmploymentForms' => $this->resource->offer_employment_forms !== null ? new ResourceCollection(ClassifierResource::class, $toClassifier->handle($this->resource->offer_employment_forms, ClassifierTypeEnum::EMPLOYMENT_FORM)) : null,
            'offerPlace' => $this->resource->offer_place,
            'offerSalary' => $this->resource->offer_salary,
            'offerSalaryCurrency' => $this->resource->offer_salary_currency ? new ClassifierResource($toClassifier->handle($this->resource->offer_salary_currency, ClassifierTypeEnum::CURRENCY)) : null,
            'offerSalaryFrequency' => $this->resource->offer_salary_frequency ? new ClassifierResource($toClassifier->handle($this->resource->offer_salary_frequency, ClassifierTypeEnum::SALARY_FREQUENCY)) : null,
            'offerWorkload' => $this->resource->offer_workload ? new ClassifierResource($toClassifier->handle($this->resource->offer_workload, ClassifierTypeEnum::WORKLOAD)) : null,
            'offerEmploymentRelationship' => $this->resource->offer_employment_relationship ? new ClassifierResource($toClassifier->handle($this->resource->offer_employment_relationship, ClassifierTypeEnum::EMPLOYMENT_RELATIONSHIP)) : null,
            'offerStartDate' => $this->resource->offer_start_date?->toIso8601String(),
            'offerEmploymentDuration' => $this->resource->offer_employment_duration ? new ClassifierResource($toClassifier->handle($this->resource->offer_employment_duration, ClassifierTypeEnum::EMPLOYMENT_DURATION)) : null,
            'offerCertainPeriodTo' => $this->resource->offer_certain_period_to?->toIso8601String(),
            'offerTrialPeriod' => $this->resource->offer_trial_period,
            'offerCandidateNote' => $this->resource->offer_candidate_note,
            'note' => $this->resource->note,
            'createdAt' => $this->resource->created_at->toIso8601String(),
            'updatedAt' => $this->resource->updated_at->toIso8601String(),
        ];
    }
}
