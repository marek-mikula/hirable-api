<?php

declare(strict_types=1);

namespace Support\Setting\Models\Builders;

use App\Models\Builders\Builder;
use Domain\User\Models\Builders\Concerns\BelongsToUser;
use Support\Setting\Enums\SettingKeyEnum;

class SettingBuilder extends Builder
{
    use BelongsToUser;

    public function whereSettingKey(SettingKeyEnum $key): static
    {
        return $this->where('key', '=', $key);
    }
}
