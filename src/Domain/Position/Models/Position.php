<?php

declare(strict_types=1);

namespace Domain\Position\Models;

use Carbon\Carbon;
use Domain\Company\Models\Company;
use Domain\Position\Database\Factories\PositionFactory;
use Domain\Position\Enums\PositionStateEnum;
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
 * @property PositionStateEnum $state
 * @property string $name
 * @property string|null $department
 * @property string|null $field classifier value
 * @property string[] $workloads classifier values
 * @property string[] $employment_relationships classifier values
 * @property string[] $employment_forms classifier values
 * @property int $job_seats_num
 * @property string $description
 * @property boolean $is_technical
 * @property string|null $address
 * @property int $salary_from
 * @property int|null $salary_to
 * @property string $salary_type classifier value
 * @property string $salary_frequency classifier value
 * @property string $salary_currency classifier value
 * @property string|null $salary_var
 * @property string[] $benefits classifier values
 * @property string|null $min_education_level classifier value
 * @property string|null $seniority classifier value
 * @property int|null $experience
 * @property string|null $driving_licence classifier value
 * @property int $organisation_skills scale 0 - 10
 * @property int $team_skills scale 0 - 10
 * @property int $time_management scale 0 - 10
 * @property int $communication_skills scale 0 - 10
 * @property int $leadership scale 0 - 10
 * @property array[] $language_requirements array of classifier values
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
        'state',
        'name',
        'department',
        'field',
        'workloads',
        'employment_relationships',
        'employment_forms',
        'job_seats_num',
        'description',
        'is_technical',
        'address',
        'salary_from',
        'salary_to',
        'salary_frequency',
        'salary_currency',
        'salary_var',
        'benefits',
        'min_education_level',
        'seniority',
        'experience',
        'driving_licence',
        'organisation_skills',
        'team_skills',
        'time_management',
        'communication_skills',
        'leadership',
        'language_requirements',
        'note',
    ];

    protected $attributes = [
        'workloads' => '[]',
        'employment_relationships' => '[]',
        'employment_forms' => '[]',
        'benefits' => '[]',
        'language_requirements' => '[]',
    ];

    protected $casts = [
        'state' => PositionStateEnum::class,
        'workloads' => 'array',
        'employment_relationships' => 'array',
        'employment_forms' => 'array',
        'is_technical' => 'boolean',
        'benefits' => 'array',
        'language_requirements' => 'array',
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
