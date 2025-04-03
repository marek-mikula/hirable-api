<?php

namespace Tests\Unit\App\Repositories;

use App\Models\Setting;
use App\Models\User;
use App\Repositories\Setting\SettingRepositoryInterface;
use Support\Setting\Enums\SettingKeyEnum;

use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\assertModelMissing;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

/** @covers \App\Repositories\Setting\SettingRepository::find */
it('tests find method', function (): void {
    /** @var SettingRepositoryInterface $repository */
    $repository = app(SettingRepositoryInterface::class);

    $user = User::factory()->create();

    $setting = Setting::factory()
        ->ofUser($user)
        ->ofKey(SettingKeyEnum::GRID_CANDIDATE)
        ->create();

    $model = $repository->find($user, SettingKeyEnum::GRID_CANDIDATE);

    assertNotNull($model);
    assertTrue($setting->is($model));
});

/** @covers \App\Repositories\Setting\SettingRepository::findOrNew */
it('tests findOrNew method', function (): void {
    /** @var SettingRepositoryInterface $repository */
    $repository = app(SettingRepositoryInterface::class);

    $user = User::factory()->create();

    $setting = $repository->findOrNew($user, SettingKeyEnum::GRID_CANDIDATE);

    assertModelMissing($setting);
    assertSame($user->id, $setting->user_id);
    assertSame(SettingKeyEnum::GRID_CANDIDATE, $setting->key);

    $setting->save();

    $setting = $repository->findOrNew($user, SettingKeyEnum::GRID_CANDIDATE);

    assertModelExists($setting);
});

/** @covers \App\Repositories\Setting\SettingRepository::save */
it('tests save method', function (): void {
    /** @var SettingRepositoryInterface $repository */
    $repository = app(SettingRepositoryInterface::class);

    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $setting = Setting::factory()->ofUser($user1)->create();
    $setting->user_id = $user2->id;

    $setting = $repository->save($setting);

    assertSame($user2->id, $setting->user_id);
});
