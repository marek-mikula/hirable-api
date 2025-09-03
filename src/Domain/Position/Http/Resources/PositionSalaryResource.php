<?php

declare(strict_types=1);

namespace Domain\Position\Http\Resources;

use Domain\Position\Models\Position;
use Illuminate\Http\Request;
use App\Http\Resources\Resource;
use Support\Classifier\Actions\ToClassifierAction;
use Support\Classifier\Enums\ClassifierTypeEnum;
use Support\Classifier\Http\Resources\ClassifierResource;

/**
 * @property Position $resource
 */
class PositionSalaryResource extends Resource
{
    public function toArray(Request $request): array
    {
        $toClassifier = ToClassifierAction::make();

        return [
            'from' => $this->resource->salary_from,
            'to' => $this->resource->salary_to,
            'type' => new ClassifierResource($toClassifier->handle($this->resource->salary_type, ClassifierTypeEnum::SALARY_TYPE)),
            'frequency' => new ClassifierResource($toClassifier->handle($this->resource->salary_frequency, ClassifierTypeEnum::SALARY_FREQUENCY)),
            'currency' => new ClassifierResource($toClassifier->handle($this->resource->salary_currency, ClassifierTypeEnum::CURRENCY)),
            'var' => $this->resource->salary_var,
        ];
    }
}
