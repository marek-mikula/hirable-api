<?php

declare(strict_types=1);

namespace Tests\Unit\App\Casts;

use App\Casts\EnumOrValue;
use Tests\Common\Enums\TestEnum;
use Tests\Common\Models\TestModel;

use function PHPUnit\Framework\assertSame;

/**
 * @covers \App\Casts\EnumOrValue::get
 * @covers \App\Casts\EnumOrValue::set
 */
it('correctly casts value', function (): void {
    $cast = new EnumOrValue(TestEnum::class);
    $model = new TestModel();

    // test that value gets cast when setting the value
    assertSame(TestEnum::VALUE1->value, $cast->set($model, 'value', TestEnum::VALUE1, []));
    assertSame('randomValue', $cast->set($model, 'value', 'randomValue', []));

    // test that value gets cast when accessing the value
    assertSame(TestEnum::VALUE1, $cast->get($model, 'value', TestEnum::VALUE1->value, []));
    assertSame('randomValue', $cast->get($model, 'value', 'randomValue', []));
})->only();
