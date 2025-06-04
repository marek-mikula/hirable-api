<?php

declare(strict_types=1);

namespace Domain\Search\Http\Requests;

class SearchCompanyContactsRequest extends SearchRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return parent::rules();
    }
}
