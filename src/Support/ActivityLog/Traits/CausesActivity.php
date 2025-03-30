<?php

namespace Support\ActivityLog\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Support\ActivityLog\Data\LogBuilder;
use Support\ActivityLog\Facades\ActivityLog as ActivityLogFacade;

/**
 * @mixin Model
 *
 * @property-read Collection<ActivityLog> $activities
 */
trait CausesActivity
{
    /**
     * Creates new log build instance with current
     * model as causer
     */
    public function causeActivity(Model $subject, string $action): LogBuilder
    {
        return ActivityLogFacade::log(subject: $subject, action: $action)->withCauser($this);
    }

    public function activities(): MorphMany
    {
        return $this->morphMany(
            related: ActivityLog::class,
            name: 'causer',
            type: 'causer_type',
            id: 'causer_id',
            localKey: 'id',
        );
    }
}
