<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Https\Requests;

use App\Http\Requests\AuthRequest;
use Domain\ProcessStep\Policies\ProcessStepPolicy;

class ProcessStepDeleteRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see ProcessStepPolicy::delete() */
        return $this->user()->can('delete', $this->route('processStep'));
    }

    public function rules(): array
    {
        return [];
    }
}
