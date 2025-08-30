<?php

declare(strict_types=1);

namespace Domain\Position\Http\Resources;

use Domain\Position\Models\PositionCandidateAction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Support\Classifier\Actions\ToClassifierAction;
use Support\Classifier\Enums\ClassifierTypeEnum;
use Support\Classifier\Http\Resources\ClassifierResource;

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
            'type' => $this->resource->type,
            'state' => $this->resource->state,
            'date' => $this->resource->date?->toIso8601String(),
            'time_start' => $this->resource->time_start?->toIso8601String(),
            'time_end' => $this->resource->time_end?->toIso8601String(),
            'note' => $this->resource->note,
            'place' => $this->resource->place,
            'instructions' => $this->resource->instructions,
            'result' => $this->resource->result,
            'name' => $this->resource->name,
            'interview_form' => $this->resource->interview_form
                ? new ClassifierResource($toClassifier->handle($this->resource->interview_form, ClassifierTypeEnum::INTERVIEW_FORM))
                : null,
            'interview_type' => $this->resource->interview_type
                ? new ClassifierResource($toClassifier->handle($this->resource->interview_type, ClassifierTypeEnum::INTERVIEW_TYPE))
                : null,
            'rejection_reason' => $this->resource->rejection_reason
                ? new ClassifierResource($toClassifier->handle($this->resource->rejection_reason, ClassifierTypeEnum::REJECTION_REASON))
                : null,
            'refusal_reason' => $this->resource->refusal_reason
                ? new ClassifierResource($toClassifier->handle($this->resource->refusal_reason, ClassifierTypeEnum::REFUSAL_REASON))
                : null,
            'testType' => $this->resource->test_type
                ? new ClassifierResource($toClassifier->handle($this->resource->test_type, ClassifierTypeEnum::TEST_TYPE))
                : null,
            'offer' => $this->resource->offer?->toArray() ?? null,
            'createdAt' => $this->resource->created_at->toIso8601String(),
            'updatedAt' => $this->resource->updated_at->toIso8601String(),
        ];
    }
}
