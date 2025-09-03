<?php

declare(strict_types=1);

namespace Tests\Unit\App\Casts;

use App\Casts\CapitalizeCast;
use Tests\Common\Models\TestModel;

use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertSame;

/**
 * @covers \App\Casts\CapitalizeCast::get
 * @covers \App\Casts\CapitalizeCast::set
 */
it('correctly casts value', function (): void {
    $cast = new CapitalizeCast();
    $model = new TestModel();

    // test that value gets cast when setting the value
    assertSame('Example', $cast->set($model, 'value', 'example', []));

    // test that value gets cast when accessing the value
    assertSame('Example', $cast->get($model, 'value', 'example', []));

    // test null values
    assertNull($cast->set($model, 'value', null, []));
    assertNull($cast->get($model, 'value', null, []));
});
