<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use Domain\Position\Policies\PositionPolicy;

class PositionUpdateRequest extends PositionStoreRequest
{
    public function authorize(): bool
    {
        /** @see PositionPolicy::update() */
        return $this->user()->can('update', $this->route('position'));
    }
}
