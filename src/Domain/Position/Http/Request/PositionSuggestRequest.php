<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;

class PositionSuggestRequest extends AuthRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function getQuery(): ?string
    {
        return $this->filled('q') ? (string) $this->input('q') : null;
    }
}
