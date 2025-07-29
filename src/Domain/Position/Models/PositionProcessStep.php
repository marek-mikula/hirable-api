<?php

declare(strict_types=1);

namespace Domain\Position\Models;

use App\Casts\EnumOrValue;
use Domain\Position\Database\Factories\PositionProcessStepFactory;
use Domain\Position\Models\Builders\PositionProcessStepBuilder;
use Domain\ProcessStep\Enums\StepEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

/**
 * @property-read int $id
 * @property int $position_id
 * @property StepEnum|string $step
 * @property int $order zero-based order index
 * @property boolean $is_fixed
 * @property boolean $is_repeatable
 * @property-read bool $is_custom
 * @property-read Position $position
 * @property-read Collection<PositionCandidate> $positionCandidates
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
        'order',
        'is_fixed',
        'is_repeatable',
    ];

    protected $casts = [
        'step' => EnumOrValue::class . ':' . StepEnum::class,
        'is_fixed' => 'boolean',
        'is_repeatable' => 'boolean',
    ];

    public function isCustom(): Attribute
    {
        return Attribute::get(fn () => is_string($this->step));
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(
            related: Position::class,
            foreignKey: 'position_id',
            ownerKey: 'id',
        );
    }

    public function positionCandidates(): HasMany
    {
        return $this->hasMany(
            related: PositionCandidate::class,
            foreignKey: 'step_id',
            localKey: 'id',
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
