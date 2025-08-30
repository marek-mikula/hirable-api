<?php

declare(strict_types=1);

namespace Tests\Unit\App\Casts;

use App\Casts\LowercaseCast;
use Tests\Common\Models\TestModel;

use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertSame;

/**
 * @covers \App\Casts\LowercaseCast::get
 * @covers \App\Casts\LowercaseCast::set
 */
it('correctly casts value', function (): void {
    $cast = new LowercaseCast();
    $model = new TestModel();

    // test that value gets cast when setting the value
    assertSame('example', $cast->set($model, 'value', 'EXAMPLE', []));

    // test that value gets cast when accessing the value
    assertSame('example', $cast->get($model, 'value', 'EXAMPLE', []));

    // test null values
    assertNull($cast->set($model, 'value', null, []));
    assertNull($cast->get($model, 'value', null, []));
});
