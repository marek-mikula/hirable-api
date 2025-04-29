<?php

declare(strict_types=1);

namespace App\Repositories\Token\Input;

use App\Models\User;
use Spatie\LaravelData\Data;
use Support\Token\Enums\TokenTypeEnum;

class StoreInput extends Data
{
    public TokenTypeEnum $type;

    public array $data = [];

    public ?int $validMinutes = null;

    public ?User $user = null;
}
