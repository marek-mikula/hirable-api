<?php

namespace App\Models\Traits;

use Illuminate\Support\Arr;

/**
 * @property array $data
 */
trait HasArrayData
{
    public function hasDataValue(string ...$keys): bool
    {
        foreach ($keys as $key) {
            if (! Arr::exists($this->data, $key)) {
                return false;
            }
        }

        return true;
    }

    public function hasAnyDataValue(string ...$keys): bool
    {
        foreach ($keys as $key) {
            if (Arr::exists($this->data, $key)) {
                return true;
            }
        }

        return false;
    }

    public function getDataValue(string $key, mixed $default = null): mixed
    {
        return Arr::get($this->data, $key, $default);
    }

    public function setDataValue(string $key, mixed $value): void
    {
        $data = $this->data;

        $data[$key] = $value;

        $this->data = $data;
    }

    public function unsetDataValue(string $key): void
    {
        $data = $this->data;

        Arr::forget($data, $key);

        $this->data = $data;
    }

    public function hasData(): bool
    {
        return ! empty($this->data);
    }
}
