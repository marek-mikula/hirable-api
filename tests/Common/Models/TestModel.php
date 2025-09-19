<?php

declare(strict_types=1);

namespace Tests\Common\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Support\ActivityLog\Data\LogOptions;
use Support\ActivityLog\Concerns\LogsActivity;

/**
 * @property-read int $id
 * @property string|null $value
 * @property Carbon $deleted_at
 */
class TestModel extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected $primaryKey = 'id';

    protected $table = 'test_models';

    protected $fillable = ['value'];

    protected static function configureLogging(): LogOptions
    {
        return LogOptions::defaults()
            ->logUpdatedAttributes([
                'value',
            ])
            ->logCreatedAttributes([
                'value',
            ])
            ->logEmptyUpdates(false);
    }
}
