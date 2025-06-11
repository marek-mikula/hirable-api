<?php

declare(strict_types=1);

namespace Domain\Search\Http\Requests;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\Traits\ValidationFailsWithStatus;
use Domain\Search\Data\SearchData;

abstract class SearchRequest extends AuthRequest
{
    use ValidationFailsWithStatus;

    public function rules(): array
    {
        return [
            'q' => [
                'nullable',
                'string',
            ],
            'limit' => [
                'nullable',
                'integer',
                'max:150',
            ],
        ];
    }

    public function toData(): SearchData
    {
        return SearchData::from([
            'query' => $this->filled('q') ? (string) $this->input('q') : null,
            'limit' => $this->filled('limit') ? (int) $this->input('limit') : 50,
        ]);
    }
}
