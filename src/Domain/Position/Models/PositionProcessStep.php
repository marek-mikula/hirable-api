<?php

declare(strict_types=1);

namespace Domain\Position\Models;

use App\Casts\EnumOrValue;
use Domain\Position\Database\Factories\PositionProcessStepFactory;
use Domain\Position\Models\Builders\PositionProcessStepBuilder;
use Domain\ProcessStep\Enums\ProcessStepEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

/**
 * @property-read int $id
 * @property int $position_id
 * @property ProcessStepEnum|string $step
 * @property int|null $round
 * @property-read Position $position
 *
 * @method static PositionProcessStepFactory factory($count = null, $state = [])
 * @method static PositionProcessStepBuilder query()
 */
class PositionProcessStep extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'position_process_steps';

    public $timestamps = false;

    protected $fillable = [
        'position_id',
        'step',
        'round',
    ];

    protected $casts = [
        'step' => EnumOrValue::class . ':' . ProcessStepEnum::class,
    ];

    public function position(): BelongsTo
    {
        return $this->belongsTo(
            related: Position::class,
            foreignKey: 'position_id',
            ownerKey: 'id',
        );
    }

    /**
     * @param  Builder  $query
     */
    public function newEloquentBuilder($query): PositionProcessStepBuilder
    {
        return new PositionProcessStepBuilder($query);
    }

    protected static function newFactory(): PositionProcessStepFactory
    {
        return PositionProcessStepFactory::new();
    }
}
