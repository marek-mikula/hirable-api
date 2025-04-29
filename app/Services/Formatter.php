<?php

declare(strict_types=1);

namespace App\Services;

use Carbon\Carbon;

class Formatter
{
    public function time(?Carbon $value, string $empty = '-', bool $withSeconds = false): string
    {
        return empty($value) ? $empty : $this->formatCarbon($value, (string) config($withSeconds ? 'format.time_seconds' : 'format.time'));
    }

    public function date(?Carbon $value, string $empty = '-'): string
    {
        return empty($value) ? $empty : $this->formatCarbon($value, (string) config('format.date'));
    }

    public function datetime(?Carbon $value, string $empty = '-', bool $withSeconds = false): string
    {
        return empty($value) ? $empty : $this->formatCarbon($value, (string) config($withSeconds ? 'format.datetime_seconds' : 'format.datetime'));
    }

    private function formatCarbon(Carbon $value, string $format): string
    {
        return $value->format($format);
    }
}
