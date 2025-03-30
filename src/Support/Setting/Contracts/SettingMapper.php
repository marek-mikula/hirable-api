<?php

namespace Support\Setting\Contracts;

use Spatie\LaravelData\Data;

interface SettingMapper
{
    public function fromArray(array $data): Data;

    public function toArray(Data $data): array;
}
