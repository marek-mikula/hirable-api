<?php

declare(strict_types=1);

namespace Tests\Common\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

function normalizeCollection(Collection $collection): Collection
{
    return $collection->map(function (mixed $item): mixed {
        if ($item instanceof Model) {
            return $item->getKey();
        }

        if ($item instanceof \BackedEnum) {
            return $item->value;
        }

        if ($item instanceof \UnitEnum) {
            return $item->name;
        }

        return $item;
    });
}

function assertCollectionsAreSame(Collection $expected, Collection $actual): void
{
    $expected = normalizeCollection($expected);
    $actual = normalizeCollection($actual);

    $message = sprintf(
        'Collection [%s] do no match the expected collection [%s].',
        $actual->implode(', '),
        $expected->implode(', '),
    );

    assertSame($expected->count(), $actual->count(), message: $message);
    assertTrue($expected->diff($actual)->isEmpty(), message: $message);
    assertTrue($actual->diff($expected)->isEmpty(), message: $message);
}
