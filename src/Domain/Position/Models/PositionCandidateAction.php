<?php

declare(strict_types=1);

namespace Domain\Position\Models;

use Carbon\Carbon;
use Domain\Position\Database\Factories\PositionCandidateActionFactory;
use Domain\Position\Enums\ActionTypeEnum;
use Domain\Position\Enums\OfferStateEnum;
use Domain\Position\Models\Builders\PositionCandidateActionBuilder;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

/**
 * @property-read int $id
 * @property int|null $position_process_step_id
 * @property int $position_candidate_id
 * @property int $user_id
 * @property ActionTypeEnum $type
 * @property Carbon|null $date
 * @property Carbon|null $time_start
 * @property Carbon|null $time_end
 * @property string|null $place
 * @property string|null $evaluation
 * @property string|null $name
 * @property string|null $interview_form
 * @property string|null $interview_type
 * @property boolean|null $rejected_by_candidate
 * @property string|null $rejection_reason
 * @property string|null $refusal_reason
 * @property string|null $task_type
 * @property OfferStateEnum|null $offer_state
 * @property string|null $offer_job_title
 * @property string|null $offer_company
 * @property string[]|null $offer_employment_forms
 * @property string|null $offer_place
 * @property int|null $offer_salary
 * @property string|null $offer_salary_currency
 * @property string|null $offer_salary_frequency
 * @property string|null $offer_workload
 * @property string|null $offer_employment_relationship
 * @property Carbon|null $offer_start_date
 * @property string|null $offer_employment_duration
 * @property Carbon|null $offer_certain_period_to
 * @property int|null $offer_trial_period
 * @property Carbon|null $real_start_date
 * @property string|null $note
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read PositionProcessStep|null $positionProcessStep
 * @property-read PositionCandidate $positionCandidate
 * @property-read User $user
 *
 * @method static PositionCandidateActionFactory factory($count = null, $state = [])
 * @method static PositionCandidateActionBuilder query()
 */
class PositionCandidateAction extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'position_candidate_actions';

    protected $fillable = [
        'position_process_step_id',
        'position_candidate_id',
        'user_id',
        'type',
        'date',
        'time_start',
        'time_end',
        'place',
        'evaluation',
        'name',
        'interview_form',
        'interview_type',
        'rejected_by_candidate',
        'rejection_reason',
        'refusal_reason',
        'task_type',
        'offer_state',
        'offer_job_title',
        'offer_company',
        'offer_employment_forms',
        'offer_place',
        'offer_salary',
        'offer_salary_currency',
        'offer_salary_frequency',
        'offer_workload',
        'offer_employment_relationship',
        'offer_start_date',
        'offer_employment_duration',
        'offer_certain_period_to',
        'offer_trial_period',
        'real_start_date',
        'note',
    ];

    protected $touches = [
        'positionCandidate', // update positionCandidate timestamps when action is performed on action
    ];

    protected function casts(): array
    {
        return [
            'type' => ActionTypeEnum::class,
            'date' => 'datetime:Y-m-d',
            'time_start' => 'datetime:H:i:s',
            'time_end' => 'datetime:H:i:s',
            'rejected_by_candidate' => 'boolean',
            'offer_state' => OfferStateEnum::class,
            'offer_employment_forms' => 'array',
            'offer_start_date' => 'datetime:Y-m-d',
            'offer_certain_period_to' => 'datetime:Y-m-d',
            'real_start_date' => 'datetime:Y-m-d',
        ];
    }

    public function positionProcessStep(): BelongsTo
    {
        return $this->belongsTo(
            related: PositionProcessStep::class,
            foreignKey: 'position_process_step_id',
            ownerKey: 'id',
        );
    }

    public function positionCandidate(): BelongsTo
    {
        return $this->belongsTo(
            related: PositionCandidate::class,
            foreignKey: 'position_candidate_id',
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
    public function newEloquentBuilder($query): PositionCandidateActionBuilder // @pest-ignore-type
    {
        return new PositionCandidateActionBuilder($query);
    }

    protected static function newFactory(): PositionCandidateActionFactory
    {
        return PositionCandidateActionFactory::new();
    }
}
