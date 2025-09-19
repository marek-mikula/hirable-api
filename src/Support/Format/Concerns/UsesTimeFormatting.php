<?php

declare(strict_types=1);

namespace Support\Format\Concerns;

use Carbon\Carbon;

trait UsesTimeFormatting
{
    public function formatTime(?Carbon $value, string $empty = '-', bool $withSeconds = false): string
    {
        return empty($value) ? $empty : $this->formatCarbon($value, (string) config($withSeconds ? 'format.time_seconds' : 'format.time'));
    }

    public function formatDate(?Carbon $value, string $empty = '-'): string
    {
        return empty($value) ? $empty : $this->formatCarbon($value, (string) config('format.date'));
    }

    public function formatDatetime(?Carbon $value, string $empty = '-', bool $withSeconds = false): string
    {
        return empty($value) ? $empty : $this->formatCarbon($value, (string) config($withSeconds ? 'format.datetime_seconds' : 'format.datetime'));
    }

    public function formatCarbon(Carbon $value, string $format): string
    {
        return $value->format($format);
    }
}
