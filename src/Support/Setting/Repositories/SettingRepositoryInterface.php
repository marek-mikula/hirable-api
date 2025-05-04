<?php

declare(strict_types=1);

namespace Support\Setting\Repositories;

use App\Models\User;
use Support\Setting\Enums\SettingKeyEnum;
use Support\Setting\Models\Setting;

interface SettingRepositoryInterface
{
    public function find(User $user, SettingKeyEnum $key): ?Setting;

    public function findOrNew(User $user, SettingKeyEnum $key): Setting;

    public function save(Setting $setting): Setting;

    public function delete(Setting $setting): void;
}
