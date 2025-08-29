<?php

declare(strict_types=1);

namespace Domain\Position\Http\Resources;

use Domain\Position\Models\PositionCandidateAction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Support\Classifier\Actions\ToClassifierAction;
use Support\Classifier\Enums\ClassifierTypeEnum;

/**
 * @property PositionCandidateAction $resource
 */
class PositionCandidateActionResource extends JsonResource
{
    public function __construct(PositionCandidateAction $resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        $toClassifier = ToClassifierAction::make();

        return [
            'id' => $this->resource->id,
            'type' => $this->resource->type->value,
            'state' => $this->resource->state->value,
            'datetime_start' => $this->resource->datetime_start?->toIso8601String(),
            'datetime_end' => $this->resource->datetime_end?->toIso8601String(),
            'note' => $this->resource->note,
            'address' => $this->resource->address,
            'instructions' => $this->resource->instructions,
            'result' => $this->resource->result,
            'name' => $this->resource->name,
            'interview_form' => $this->resource->interview_form
                ? $toClassifier->handle($this->resource->interview_form, ClassifierTypeEnum::INTERVIEW_FORM)
                : null,
            'interview_type' => $this->resource->interview_type
                ? $toClassifier->handle($this->resource->interview_type, ClassifierTypeEnum::INTERVIEW_TYPE)
                : null,
            'rejection_reason' => $this->resource->rejection_reason
                ? $toClassifier->handle($this->resource->rejection_reason, ClassifierTypeEnum::REJECTION_REASON)
                : null,
            'refusal_reason' => $this->resource->refusal_reason
                ? $toClassifier->handle($this->resource->refusal_reason, ClassifierTypeEnum::REFUSAL_REASON)
                : null,
            'testType' => $this->resource->test_type
                ? $toClassifier->handle($this->resource->test_type, ClassifierTypeEnum::TEST_TYPE)
                : null,
            'offer' => $this->resource->offer?->toArray() ?? null,
            'createdAt' => $this->resource->created_at->toIso8601String(),
            'updatedAt' => $this->resource->updated_at->toIso8601String(),
        ];
    }
}
