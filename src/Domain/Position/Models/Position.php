<?php

declare(strict_types=1);

namespace Domain\Position\Models;

use Carbon\Carbon;
use Domain\Company\Models\Company;
use Domain\Position\Database\Factories\PositionFactory;
use Domain\Position\Models\Builders\PositionBuilder;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

/**
 * @property-read int $id
 * @property int $company_id
 * @property int $user_id
 * @property string $name
 * @property string|null $department classifier value
 * @property string|null $field classifier value
 * @property boolean $is_technical
 * @property string|null $address
 * @property int $salary_from
 * @property int|null $salary_to
 * @property string $salary_frequency classifier value
 * @property string $salary_currency classifier value
 * @property string|null $salary_var
 * @property string|null $min_education_level classifier value
 * @property string|null $seniority classifier value
 * @property int|null $experience
 * @property string|null $note
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Company $company
 * @property-read User $user
 *
 * @method static PositionFactory factory($count = null, $state = [])
 * @method static PositionBuilder query()
 */
class Position extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'positions';

    public $timestamps = true;

    protected $fillable = [
        'company_id',
        'user_id',
        'name',
        'department',
        'field',
        'is_technical',
        'address',
        'salary_from',
        'salary_to',
        'salary_frequency',
        'salary_currency',
        'salary_var',
        'min_education_level',
        'seniority',
        'experience',
        'note',
    ];

    protected $casts = [
        'is_technical' => 'boolean'
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(
            related: Company::class,
            foreignKey: 'company_id',
            ownerKey: 'id',
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'user_id',
            ownerKey: 'id',
        );
    }

    /**
     * @param  Builder  $query
     */
    public function newEloquentBuilder($query): PositionBuilder
    {
        return new PositionBuilder($query);
    }

    protected static function newFactory(): PositionFactory
    {
        return PositionFactory::new();
    }
}
