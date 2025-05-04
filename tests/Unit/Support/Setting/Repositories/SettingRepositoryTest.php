<?php

declare(strict_types=1);

namespace Tests\Unit\Support\Setting\Repositories;

use App\Models\User;
use Support\Setting\Enums\SettingKeyEnum;
use Support\Setting\Models\Setting;
use Support\Setting\Repositories\SettingRepositoryInterface;

use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\assertModelMissing;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

/** @covers \Support\Setting\Repositories\SettingRepository::find */
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

/** @covers \Support\Setting\Repositories\SettingRepository::findOrNew */
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

/** @covers \Support\Setting\Repositories\SettingRepository::save */
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
