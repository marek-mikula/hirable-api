<?php

declare(strict_types=1);

namespace Domain\Position\Models;

use App\Casts\EnumOrValue;
use Domain\Company\Models\Company;
use Domain\Position\Database\Factories\PositionApprovalFactory;
use Domain\Position\Database\Factories\PositionProcessStepFactory;
use Domain\Position\Enums\PositionProcessStepEnum;
use Domain\Position\Models\Builders\PositionApprovalBuilder;
use Domain\Position\Models\Builders\PositionProcessStepBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

/**
 * @property-read int $id
 * @property int|null $company_id
 * @property PositionProcessStepEnum|string $step
 * @property-read Company|null $company
 *
 * @method static PositionApprovalFactory factory($count = null, $state = [])
 * @method static PositionApprovalBuilder query()
 */
class PositionProcessStep extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'position_process_steps';

    public $timestamps = false;

    protected $fillable = [
        'company_id',
        'step',
    ];

    protected $casts = [
        'step' => EnumOrValue::class . ':' . PositionProcessStepEnum::class,
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(
            related: Company::class,
            foreignKey: 'company_id',
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
