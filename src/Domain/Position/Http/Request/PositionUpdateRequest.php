<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

class PositionUpdateRequest extends PositionStoreRequest
{
    public function authorize(): bool
    {
        return true;
    }
}
