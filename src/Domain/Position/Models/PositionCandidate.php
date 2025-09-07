<?php

declare(strict_types=1);

namespace Domain\Position\Models;

use Carbon\Carbon;
use Domain\Application\Models\Application;
use Domain\Candidate\Models\Candidate;
use Domain\Position\Database\Factories\PositionCandidateFactory;
use Domain\Position\Models\Builders\PositionCandidateBuilder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder;

/**
 * @property-read int $id
 * @property int $position_id
 * @property int $candidate_id
 * @property int $application_id
 * @property int $step_id
 * @property array $score
 * @property int|null $total_score
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read bool $is_score_calculated
 * @property-read int $idle_days
 * @property-read Position $position
 * @property-read Candidate $candidate
 * @property-read Application $application
 * @property-read PositionProcessStep $step
 * @property-read Collection<PositionCandidateAction> $actions
 * @property-read PositionCandidateAction|null $latestAction
 * @property-read Collection<PositionCandidateEvaluation> $evaluations
 * @property-read Collection<PositionCandidateShare> $shares
 * @property-read int|null $actions_count
 * @property-read int|null $shares_count
 *
 * @method static PositionCandidateFactory factory($count = null, $state = [])
 * @method static PositionCandidateBuilder query()
 */
class PositionCandidate extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'position_candidates';

    public $timestamps = true;

    protected $fillable = [
        'position_id',
        'candidate_id',
        'application_id',
        'step_id',
        'score',
        'total_score',
    ];

    protected $attributes = [
        'score' => '{}'
    ];

    protected function casts(): array
    {
        return [
            'score' => 'array',
        ];
    }

    protected function isScoreCalculated(): Attribute
    {
        return Attribute::get(fn (): bool => $this->total_score !== null && !empty($this->score));
    }

    protected function idleDays(): Attribute
    {
        return Attribute::get(fn (): int => (int) $this->updated_at->diffInDays(now()));
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(
            related: Position::class,
            foreignKey: 'position_id',
            ownerKey: 'id',
        );
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(
            related: Candidate::class,
            foreignKey: 'candidate_id',
            ownerKey: 'id',
        );
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(
            related: Application::class,
            foreignKey: 'application_id',
            ownerKey: 'id',
        );
    }

    public function step(): BelongsTo
    {
        return $this->belongsTo(
            related: PositionProcessStep::class,
            foreignKey: 'step_id',
            ownerKey: 'id',
        );
    }

    public function actions(): HasMany
    {
        return $this->hasMany(
            related: PositionCandidateAction::class,
            foreignKey: 'position_candidate_id',
            localKey: 'id',
        )->latest('id');
    }

    public function latestAction(): HasOne
    {
        return $this->actions()->one();
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(
            related: PositionCandidateEvaluation::class,
            foreignKey: 'position_candidate_id',
            localKey: 'id',
        )->latest('id');
    }

    public function shares(): HasMany
    {
        return $this->hasMany(
            related: PositionCandidateShare::class,
            foreignKey: 'position_candidate_id',
            localKey: 'id',
        );
    }

    /**
     * @param  Builder  $query
     */
    public function newEloquentBuilder($query): PositionCandidateBuilder // @pest-ignore-type
    {
        return new PositionCandidateBuilder($query);
    }

    protected static function newFactory(): PositionCandidateFactory
    {
        return PositionCandidateFactory::new();
    }
}
