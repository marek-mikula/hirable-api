<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use Domain\Position\Models\Position;
use Domain\Position\Policies\PositionPolicy;

class PositionGenerateFromPromptRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see PositionPolicy::store() */
        return $this->user()->can('store', Position::class);
    }

    public function rules(): array
    {
        return [
            'prompt' => [
                'required',
                'string',
                'max:2000',
            ],
        ];
    }

    public function getPrompt(): string
    {
        return (string) $this->input('prompt');
    }
}
