<?php

namespace Domain\Search\Http\Requests;

class SearchCompanyUsersRequest extends SearchRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'ignoreAuth' => [
                'nullable',
                'boolean',
            ],
        ]);
    }

    public function ignoreAuth(): bool
    {
        return $this->boolean('ignoreAuth');
    }
}
