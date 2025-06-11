<?php

declare(strict_types=1);

namespace Support\Classifier\Http\Requests;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\Traits\ValidationFailsWithStatus;
use Illuminate\Validation\Rule;
use Support\Classifier\Enums\ClassifierTypeEnum;

class ClassifierIndexRequest extends AuthRequest
{
    use ValidationFailsWithStatus;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'types' => [
                'array',
            ],
            'types.*' => [
                'required',
                'string',
                Rule::enum(ClassifierTypeEnum::class),
            ]
        ];
    }

    /**
     * @return ClassifierTypeEnum[]
     */
    public function getTypes(): array
    {
        return $this->collect('types')
            ->map(fn (mixed $type) => ClassifierTypeEnum::from((string) $type))
            ->all();
    }
}
