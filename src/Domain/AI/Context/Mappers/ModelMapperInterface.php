<?php

declare(strict_types=1);

namespace Domain\AI\Context\Mappers;

use Illuminate\Database\Eloquent\Model;

interface ModelMapperInterface
{
    /**
     * Maps field to model value.
     */
    public function mapField(Model $model, string $field): mixed;
}
