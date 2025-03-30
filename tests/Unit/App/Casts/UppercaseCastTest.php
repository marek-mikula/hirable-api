<?php

namespace Tests\Unit\App\Casts;

use App\Casts\Uppercase;
use Tests\Common\Models\TestModel;

use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertSame;

/**
 * @covers \App\Casts\Uppercase::get
 * @covers \App\Casts\Uppercase::set
 */
it('correctly casts value', function (): void {
    $cast = new Uppercase();
    $model = new TestModel();

    // test that value gets cast when setting the value
    assertSame('EXAMPLE', $cast->set($model, 'value', 'example', []));

    // test that value gets cast when accessing the value
    assertSame('EXAMPLE', $cast->get($model, 'value', 'example', []));

    // test null values
    assertNull($cast->set($model, 'value', null, []));
    assertNull($cast->get($model, 'value', null, []));
});
