<?php

namespace Domain\Company\Http\Requests;

use App\Http\Requests\Request;
use Support\Grid\Actions\CollectGridRequestQueryAction;
use Support\Grid\Data\Query\GridRequestQuery;

class CompanyUserIndexRequest extends Request
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }

    public function getGridQuery(): GridRequestQuery
    {
        return CollectGridRequestQueryAction::make()->handle($this);
    }
}
