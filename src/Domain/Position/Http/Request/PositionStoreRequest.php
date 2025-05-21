<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use Domain\Position\Http\Request\Data\PositionStoreData;

class PositionStoreRequest extends AuthRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

        ];
    }

    public function toData(): PositionStoreData
    {
        return PositionStoreData::from([

        ]);
    }
}
