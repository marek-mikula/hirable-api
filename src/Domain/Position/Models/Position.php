<?php

declare(strict_types=1);

namespace Domain\Position\Models;

use Carbon\Carbon;
use Domain\Application\Services\ApplicationTokenUrlService;
use Domain\Candidate\Enums\SourceEnum;
use Domain\Company\Models\Company;
use Domain\Company\Models\CompanyContact;
use Domain\Position\Database\Factories\PositionFactory;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Models\Builders\PositionBuilder;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Query\Builder;
use Support\File\Models\Concerns\HasFiles;

/**
 * @property-read int $id
 * @property int $company_id
 * @property int $user_id
 * @property string $name
 * @property string $extern_name
 * @property PositionStateEnum $state
 * @property Carbon|null $approve_until
 * @property string|null $approve_message
 * @property int|null $approve_round
 * @property string|null $department
 * @property string|null $field classifier value
 * @property string[] $workloads classifier values
 * @property string[] $employment_relationships classifier values
 * @property string[] $employment_forms classifier values
 * @property int $job_seats_num
 * @property string $description
 * @property string|null $address
 * @property int $salary_from
 * @property int|null $salary_to
 * @property string $salary_type classifier value
 * @property string $salary_frequency classifier value
 * @property string $salary_currency classifier value
 * @property string|null $salary_var
 * @property string[] $benefits classifier values
 * @property string|null $min_education_level classifier value
 * @property string|null $education_field
 * @property string[] $seniority classifier values
 * @property int|null $experience
 * @property string|null $hard_skills
 * @property int $organisation_skills scale 0 - 100
 * @property int $team_skills scale 0 - 100
 * @property int $time_management scale 0 - 100
 * @property int $communication_skills scale 0 - 100
 * @property int $leadership scale 0 - 100
 * @property array[] $language_requirements array of classifier values
 * @property string|null $note
 * @property int $hard_skills_weight scale 0 - 100
 * @property int $soft_skills_weight scale 0 - 100
 * @property int $language_skills_weight scale 0 - 100
 * @property int $experience_weight scale 0 - 100
 * @property int $education_weight scale 0 - 100
 * @property boolean $share_salary
 * @property boolean $share_contact
 * @property string[] $tags
 * @property string|null $common_token
 * @property string|null $intern_token
 * @property string|null $referral_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read string|null $common_link
 * @property-read string|null $intern_link
 * @property-read string|null $referral_link
 * @property-read Company $company
 * @property-read User $user // todo rename to owner, so its more clear
 * @property-read Collection<ModelHasPosition> $models
 * @property-read Collection<CompanyContact> $companyContacts
 * @property-read Collection<CompanyContact> $externalApprovers
 * @property-read Collection<User> $users
 * @property-read Collection<User> $approvers
 * @property-read Collection<User> $hiringManagers
 * @property-read Collection<User> $recruiters
 * @property-read Collection<PositionApproval> $approvals
 * @property-read Collection<PositionProcessStep> $steps
 * @property-read Collection<PositionCandidate> $positionCandidates
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
        'name',
        'extern_name',
        'state',
        'approve_until',
        'approve_message',
        'approve_round',
        'department',
        'field',
        'workloads',
        'employment_relationships',
        'employment_forms',
        'job_seats_num',
        'description',
        'address',
        'salary_from',
        'salary_to',
        'salary_type',
        'salary_frequency',
        'salary_currency',
        'salary_var',
        'benefits',
        'min_education_level',
        'education_field',
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
        'hard_skills_weight',
        'soft_skills_weight',
        'language_skills_weight',
        'experience_weight',
        'education_weight',
        'share_salary',
        'share_contact',
        'tags',
        'common_token',
        'intern_token',
        'referral_token',
    ];

    protected $attributes = [
        'workloads' => '[]',
        'employment_relationships' => '[]',
        'employment_forms' => '[]',
        'benefits' => '[]',
        'seniority' => '[]',
        'language_requirements' => '[]',
        'tags' => '[]',
    ];

    protected function casts(): array
    {
        return [
            'state' => PositionStateEnum::class,
            'approve_until' => 'datetime:Y-m-d',
            'workloads' => 'array',
            'employment_relationships' => 'array',
            'employment_forms' => 'array',
            'benefits' => 'array',
            'seniority' => 'array',
            'language_requirements' => 'array',
            'share_salary' => 'boolean',
            'share_contact' => 'boolean',
            'tags' => 'array',
        ];
    }

    protected function commonLink(): Attribute
    {
        return Attribute::get(fn (): ?string => empty($this->common_token) ? null : ApplicationTokenUrlService::resolve()->getApplyUrl(SourceEnum::POSITION, $this->common_token));
    }

    protected function internLink(): Attribute
    {
        return Attribute::get(fn (): ?string => empty($this->intern_token) ? null : ApplicationTokenUrlService::resolve()->getApplyUrl(SourceEnum::INTERN, $this->intern_token));
    }

    protected function referralLink(): Attribute
    {
        return Attribute::get(fn (): ?string => empty($this->referral_token) ? null : ApplicationTokenUrlService::resolve()->getReferralUrl($this->referral_token));
    }

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

    public function steps(): HasMany
    {
        return $this->hasMany(
            related: PositionProcessStep::class,
            foreignKey: 'position_id',
            localKey: 'id',
        );
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

    public function recruiters(): MorphToMany
    {
        return $this->users()->wherePivot('role', PositionRoleEnum::RECRUITER->value);
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(
            related: PositionApproval::class,
            foreignKey: 'position_id',
            localKey: 'id',
        );
    }

    public function positionCandidates(): HasMany
    {
        return $this->hasMany(
            related: PositionCandidate::class,
            foreignKey: 'position_id',
            localKey: 'id',
        );
    }

    /**
     * @param  Builder  $query
     */
    public function newEloquentBuilder($query): PositionBuilder // @pest-ignore-type
    {
        return new PositionBuilder($query);
    }

    protected static function newFactory(): PositionFactory
    {
        return PositionFactory::new();
    }
}
