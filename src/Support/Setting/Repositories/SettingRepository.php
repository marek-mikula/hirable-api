<?php

declare(strict_types=1);

namespace Support\Setting\Repositories;

use App\Exceptions\RepositoryException;
use App\Models\User;
use Support\Setting\Enums\SettingKeyEnum;
use Support\Setting\Models\Setting;

class SettingRepository implements SettingRepositoryInterface
{
    public function find(User $user, SettingKeyEnum $key): ?Setting
    {
        /** @var Setting|null $model */
        $model = Setting::query()
            ->whereUser($user->id)
            ->whereSettingKey($key)
            ->first();

        return $model;
    }

    public function findOrNew(User $user, SettingKeyEnum $key): Setting
    {
        $model = $this->find($user, $key);

        if (!$model) {
            $model = new Setting();
            $model->key = $key;
            $model->user_id = $user->id;
            $model->data = [];
            $model->setRelation('user', $user);
        }

        return $model;
    }

    public function save(Setting $setting): Setting
    {
        throw_if(!$setting->save(), RepositoryException::saved(Setting::class));

        return $setting;
    }

    public function delete(Setting $setting): void
    {
        throw_if(!$setting->delete(), RepositoryException::deleted(Setting::class));
    }
}
