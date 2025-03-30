<?php

namespace App\Models\Builders;

use App\Models\Builders\Traits\BelongsToUser;
use Support\Setting\Enums\SettingKeyEnum;

class SettingBuilder extends Builder
{
    use BelongsToUser;

    public function whereSettingKey(SettingKeyEnum $key): static
    {
        return $this->where('key', '=', $key->value);
    }
}
