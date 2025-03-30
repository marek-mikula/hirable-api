<?php

namespace Support\ActivityLog\Facades;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;
use Support\ActivityLog\Services\ActivityLogCauserResolver;

/**
 * @method static array getCauserInfo() gets causer model and ID as array
 * @method static Model|null getCauser() gets causer model
 * @method static void setCauser(Model|null $model) sets causer model
 * @method static void resetCauser() resets current causer to default causer resolver
 * @method static void setDefaultCauserResolver(\Closure $callback) sets default causer resolver
 * @method static void setCauserResolver(\Closure $callback) sets current causer resolver
 * @method static mixed withCauser(\Closure $callback, ?Model $causer) calls given callback with specific causer
 */
class CauserResolver extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ActivityLogCauserResolver::class;
    }
}
