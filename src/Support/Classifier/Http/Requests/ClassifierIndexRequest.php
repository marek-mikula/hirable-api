<?php

declare(strict_types=1);

namespace Support\Classifier\Http\Requests;

use App\Http\Requests\AuthRequest;
use Illuminate\Validation\Rules\Enum;
use Support\Classifier\Enums\ClassifierTypeEnum;

class ClassifierIndexRequest extends AuthRequest
{
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
                new Enum(ClassifierTypeEnum::class),
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
