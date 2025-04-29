<?php

declare(strict_types=1);

namespace Support\ActivityLog\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Support\ActivityLog\Data\LogBuilder;
use Support\ActivityLog\Data\LogOptions;
use Support\ActivityLog\Facades\ActivityLog as ActivityLogFacade;
use Support\ActivityLog\Services\ActivityLogHandler;

/**
 * @mixin Model
 *
 * @property-read Collection<ActivityLog> $activityLogs
 */
trait LogsActivity
{
    // abstract method that the model needs to implement
    abstract protected static function configureLogging(): LogOptions;

    /**
     * Boots eloquent models and attaches event
     * handling instance to handle the events
     */
    protected static function booted(): void
    {
        $options = once(static fn (): LogOptions => static::configureLogging());

        $usesSoftDeletes = usesSoftDeletes(static::class);

        if ($options->hasEvent('created')) {
            static::created(static fn (Model $model) => ActivityLogHandler::instance()->handleCreated($options, $model));
        }

        if ($options->hasEvent('updated')) {
            static::updated(static fn (Model $model) => ActivityLogHandler::instance()->handleUpdated($options, $model));
        }

        if ($options->hasEvent('deleted')) {
            static::deleted(static fn (Model $model) => ActivityLogHandler::instance()->handleDeleted($options, $model));
        }

        if ($options->hasEvent('restored') && $usesSoftDeletes) {
            static::restored(static fn (Model $model) => ActivityLogHandler::instance()->handleRestored($options, $model));
        }

        if ($options->hasEvent('deleted') && $usesSoftDeletes) {
            static::forceDeleted(static fn (Model $model) => ActivityLogHandler::instance()->handleDeleted($options, $model, force: true));
        }
    }

    /**
     * Creates new log build instance with current
     * model as subject
     */
    public function logActivity(string $action): LogBuilder
    {
        return ActivityLogFacade::log(subject: $this, action: $action);
    }

    public function activityLogs(): MorphMany
    {
        return $this->morphMany(
            related: ActivityLog::class,
            name: 'subject',
            type: 'subject_type',
            id: 'subject_id',
            localKey: 'id',
        );
    }
}
