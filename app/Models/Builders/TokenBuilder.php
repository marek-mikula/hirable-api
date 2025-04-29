<?php

declare(strict_types=1);

namespace App\Models\Builders;

use App\Models\Builders\Traits\BelongsToUser;
use Support\Token\Enums\TokenTypeEnum;

class TokenBuilder extends Builder
{
    use BelongsToUser;

    public function whereCompany(int $id): static
    {
        return $this->where('data->companyId', '=', $id);
    }

    public function whereEmail(string $email): static
    {
        return $this->where('data->email', '=', $email);
    }

    public function whereType(TokenTypeEnum $type): static
    {
        return $this->where('type', '=', $type->value);
    }

    public function expired(): static
    {
        return $this->whereTimestamp('valid_until', '<=', now());
    }

    public function used(): static
    {
        return $this->whereNotNull('used_at');
    }

    public function valid(): static
    {
        return $this->whereTimestamp('valid_until', '>', now());
    }

    public function readyToDelete(): static
    {
        $days = (int) config('token.keep_days');

        $date = now()->subDays($days);

        return $this->where(function (TokenBuilder $query) use ($date): void {
            $query
                ->where('used_at', '<=', $date->format('Y-m-d H:i:s'))
                ->orWhere('valid_until', '<=', $date->format('Y-m-d H:i:s'));
        });
    }
}
