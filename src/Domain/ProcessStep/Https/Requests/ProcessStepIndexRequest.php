<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Https\Requests;

use App\Http\Requests\AuthRequest;
use Domain\ProcessStep\Models\ProcessStep;
use Domain\ProcessStep\Policies\ProcessStepPolicy;

class ProcessStepIndexRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see ProcessStepPolicy::index() */
        return $this->user()->can('index', ProcessStep::class);
    }

    public function rules(): array
    {
        return [
            'includeCommon' => [
                'boolean',
            ]
        ];
    }

    public function includeCommon(): bool
    {
        return $this->boolean('includeCommon');
    }
}
