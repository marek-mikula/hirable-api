<?php

declare(strict_types=1);

namespace Domain\Position\Models;

use Carbon\Carbon;
use Domain\Company\Models\Company;
use Domain\Company\Models\CompanyContact;
use Domain\Position\Database\Factories\PositionFactory;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Models\Builders\PositionBuilder;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Query\Builder;
use Support\File\Models\Traits\HasFiles;

/**
 * @property-read int $id
 * @property int $company_id
 * @property int $user_id
 * @property PositionStateEnum $state
 * @property int|null $approval_round
 * @property Carbon|null $approve_until
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
 * @property string|null $hard_skills
 * @property int $organisation_skills scale 0 - 10
 * @property int $team_skills scale 0 - 10
 * @property int $time_management scale 0 - 10
 * @property int $communication_skills scale 0 - 10
 * @property int $leadership scale 0 - 10
 * @property array[] $language_requirements array of classifier values
 * @property string|null $note
 * @property int $hard_skills_relevance scale 0 - 10
 * @property int $soft_skills_relevance scale 0 - 10
 * @property int $language_skills_relevance scale 0 - 10
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Company $company
 * @property-read User $user
 * @property-read Collection<ModelHasPosition> $models
 * @property-read Collection<CompanyContact> $companyContacts
 * @property-read Collection<CompanyContact> $externalApprovers
 * @property-read Collection<User> $users
 * @property-read Collection<User> $approvers
 * @property-read Collection<User> $hiringManagers
 * @property-read Collection<PositionApproval> $approvals
 *
 * @method static PositionFactory factory($count = null, $state = [])
 * @method static PositionBuilder query()
 */
class Position extends Model
{
    use HasFactory;
    use HasFiles;

    protected $primaryKey = 'id';

    protected $table = 'positions';

    public $timestamps = true;

    protected $fillable = [
        'company_id',
        'user_id',
        'state',
        'approval_round',
        'approve_until',
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
        'salary_type',
        'salary_frequency',
        'salary_currency',
        'salary_var',
        'benefits',
        'min_education_level',
        'seniority',
        'experience',
        'hard_skills',
        'organisation_skills',
        'team_skills',
        'time_management',
        'communication_skills',
        'leadership',
        'language_requirements',
        'note',
        'hard_skills_relevance',
        'soft_skills_relevance',
        'language_skills_relevance',
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
        'approve_until' => 'date',
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

    public function models(): HasMany
    {
        return $this->hasMany(
            related: ModelHasPosition::class,
            foreignKey: 'position_id',
            localKey: 'id',
        );
    }

    public function companyContacts(): MorphToMany
    {
        return $this->morphedByMany(
            related: CompanyContact::class,
            name: 'model',
            table: 'model_has_positions',
            foreignPivotKey: 'position_id',
            relatedPivotKey: 'model_id',
            parentKey: 'id',
            relatedKey: 'id',
        )->withPivot(['role']);
    }

    public function users(): MorphToMany
    {
        return $this->morphedByMany(
            related: User::class,
            name: 'model',
            table: 'model_has_positions',
            foreignPivotKey: 'position_id',
            relatedPivotKey: 'model_id',
            parentKey: 'id',
            relatedKey: 'id',
        )->withPivot(['role']);
    }

    public function externalApprovers(): MorphToMany
    {
        return $this->companyContacts()->wherePivot('role', PositionRoleEnum::EXTERNAL_APPROVER->value);
    }

    public function approvers(): MorphToMany
    {
        return $this->users()->wherePivot('role', PositionRoleEnum::APPROVER->value);
    }

    public function hiringManagers(): MorphToMany
    {
        return $this->users()->wherePivot('role', PositionRoleEnum::HIRING_MANAGER->value);
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(
            related: PositionApproval::class,
            foreignKey: 'position_id',
            localKey: 'id',
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
