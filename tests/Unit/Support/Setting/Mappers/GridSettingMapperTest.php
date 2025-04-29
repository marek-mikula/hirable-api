<?php

declare(strict_types=1);

namespace Tests\Unit\Support\Setting\Mappers;

use Support\Grid\Enums\OrderEnum;
use Support\Grid\Enums\PerPageEnum;
use Support\Setting\Mappers\GridSettingMapper;

use function PHPUnit\Framework\assertSame;

/** @covers \Support\Setting\Mappers\GridSettingMapper::fromArray */
it('correctly maps the array', function (): void {
    /** @var GridSettingMapper $mapper */
    $mapper = app(GridSettingMapper::class);

    $data = [
        'perPage' => PerPageEnum::HUNDRED->value,
        'stickyHeader' => fake()->boolean,
        'stickyFooter' => fake()->boolean,
        'columns' => [
            'firstname' => [
                'enabled' => fake()->boolean,
                'width' => fake()->numberBetween(1, 500),
            ],
            'lastname' => [
                'enabled' => fake()->boolean,
                'width' => fake()->numberBetween(1, 500),
            ],
            'email' => [
                'enabled' => fake()->boolean,
                'width' => fake()->numberBetween(1, 500),
            ],
            'phone' => [
                'enabled' => fake()->boolean,
                'width' => fake()->numberBetween(1, 500),
            ],
        ],
        'order' => [
            'firstname',
            'lastname',
            'email',
            'phone',
        ],
        'searchQuery' => fake()->word,
        'sort' => [
            'firstname' => OrderEnum::ASC->value,
            'email' => OrderEnum::DESC->value,
        ],
    ];

    $setting = $mapper->fromArray($data);

    assertSame($data['perPage'], $setting->perPage?->value);
    assertSame($data['stickyHeader'], $setting->stickyHeader);
    assertSame($data['stickyFooter'], $setting->stickyFooter);
    assertSame($data['columns']['firstname']['enabled'], $setting->columns['firstname']->enabled);
    assertSame($data['columns']['firstname']['width'], $setting->columns['firstname']->width);
    assertSame($data['columns']['lastname']['enabled'], $setting->columns['lastname']->enabled);
    assertSame($data['columns']['lastname']['width'], $setting->columns['lastname']->width);
    assertSame($data['columns']['email']['enabled'], $setting->columns['email']->enabled);
    assertSame($data['columns']['email']['width'], $setting->columns['email']->width);
    assertSame($data['columns']['phone']['enabled'], $setting->columns['phone']->enabled);
    assertSame($data['columns']['phone']['width'], $setting->columns['phone']->width);
    assertSame($data['order'], $setting->order);
    assertSame($data['searchQuery'], $setting->searchQuery);
    assertSame(array_keys($data['sort']), array_keys($setting->sort));

    foreach ($setting->sort as $column => $order) {
        assertSame($data['sort'][$column], $order->value);
    }
});
