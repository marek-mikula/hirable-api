<?php

namespace Support\ActivityLog\Data;

use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Data;

class Log extends Data
{
    /** @var class-string<Model>|null */
    public ?string $causer;

    public ?int $causerId;

    /** @var class-string<Model> */
    public string $subject;

    public int $subjectId;

    public string $action;

    public array $data = [];
}
