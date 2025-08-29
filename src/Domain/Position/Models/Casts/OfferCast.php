<?php

declare(strict_types=1);

namespace Domain\Position\Models\Casts;

use Domain\Position\Models\Data\OfferData;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class OfferCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (!is_string($value) || !Str::isJson($value)) {
            return $value;
        }

        $array = json_decode($value, associative: true);

        return new OfferData(
            jobTitle: Arr::get($array, 'jobTitle')
        );
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (!($value instanceof OfferData)) {
            return $value;
        }

        return json_encode($value->toArray());
    }
}
