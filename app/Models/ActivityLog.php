<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasArrayData;
use Carbon\Carbon;
use Database\Factories\ActivityLogFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property-read int $id
 * @property class-string<Model>|null $causer_type
 * @property int|null $causer_id
 * @property class-string<Model> $subject_type
 * @property int $subject_id
 * @property string $action
 * @property array $data
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read bool $is_automatic
 * @property-read Model $subject
 * @property-read Model|null $causer
 *
 * @method static ActivityLogFactory factory($count = null, $state = [])
 */
class ActivityLog extends Model
{
    use HasArrayData;
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'activity_logs';

    public $timestamps = true;

    protected $fillable = [
        'causer_type',
        'causer_id',
        'subject_type',
        'subject_id',
        'action',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    protected function isAutomatic(): Attribute
    {
        return Attribute::get(fn (): bool => empty($this->causer_id));
    }

    public function subject(): MorphTo
    {
        return $this->morphTo(
            name: 'subject',
            type: 'subject_type',
            id: 'subject_id',
            ownerKey: 'id',
        );
    }

    public function causer(): MorphTo
    {
        return $this->morphTo(
            name: 'causer',
            type: 'causer_type',
            id: 'causer_id',
            ownerKey: 'id',
        );
    }

    protected static function newFactory(): ActivityLogFactory
    {
        return ActivityLogFactory::new();
    }
}
