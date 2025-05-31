<?php

declare(strict_types=1);

namespace Domain\Position\Models;

use Carbon\Carbon;
use Domain\Company\Models\Company;
use Domain\Company\Models\CompanyContact;
use Domain\Position\Database\Factories\ModelHasPositionFactory;
use Domain\Position\Database\Factories\PositionFactory;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Models\Builders\ModelHasPositionBuilder;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

/**
 * @property-read int $id
 * @property int $position_id
 * @property class-string<Model> $model_type
 * @property int $model_id
 * @property PositionRoleEnum $role
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Company $company
 * @property-read User $user
 * @property-read User|CompanyContact $model
 *
 * @method static PositionFactory factory($count = null, $state = [])
 * @method static ModelHasPositionBuilder query()
 */
class ModelHasPosition extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'model_has_positions';

    public $timestamps = true;

    protected $fillable = [
        'position_id',
        'model_type',
        'model_id',
        'role',
    ];

    protected $attributes = [];

    protected $casts = [
        'role' => PositionRoleEnum::class,
    ];

    public function position(): BelongsTo
    {
        return $this->belongsTo(
            related: Position::class,
            foreignKey: 'position_id',
            ownerKey: 'id',
        );
    }

    public function model(): BelongsTo
    {
        return $this->morphTo(
            name: 'model',
            type: 'model_type',
            id: 'model_id',
            ownerKey: 'id',
        );
    }

    /**
     * @param  Builder  $query
     */
    public function newEloquentBuilder($query): ModelHasPositionBuilder
    {
        return new ModelHasPositionBuilder($query);
    }

    protected static function newFactory(): ModelHasPositionFactory
    {
        return ModelHasPositionFactory::new();
    }
}
