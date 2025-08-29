<?php

declare(strict_types=1);

namespace Domain\Position\Models;

use Carbon\Carbon;
use Domain\Company\Models\Company;
use Domain\Company\Models\CompanyContact;
use Domain\Position\Database\Factories\ModelHasPositionFactory;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Models\Builders\ModelHasPositionBuilder;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
 * @property-read bool $is_external
 * @property-read Company $company
 * @property-read User $user
 * @property-read User|CompanyContact $model
 * @property-read Position $position
 *
 * @method static ModelHasPositionFactory factory($count = null, $state = [])
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

    protected function casts(): array
    {
        return [
            'role' => PositionRoleEnum::class,
        ];
    }

    protected function isExternal(): Attribute
    {
        return Attribute::get(fn (): bool => $this->model_type === CompanyContact::class);
    }

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
            ownerKey: 'id',
        );
    }

    /**
     * @param  Builder  $query
     */
    public function newEloquentBuilder($query): ModelHasPositionBuilder // @pest-ignore-type
    {
        return new ModelHasPositionBuilder($query);
    }

    protected static function newFactory(): ModelHasPositionFactory
    {
        return ModelHasPositionFactory::new();
    }
}
