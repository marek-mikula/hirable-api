<?php

declare(strict_types=1);

namespace Domain\Position\Models;

use Carbon\Carbon;
use Domain\Application\Models\Application;
use Domain\Candidate\Models\Candidate;
use Domain\Position\Database\Factories\PositionCandidateFactory;
use Domain\Position\Models\Builders\PositionCandidateBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
 * @property-read Position $position
 * @property-read Candidate $candidate
 * @property-read Application $application
 * @property-read PositionProcessStep $step
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

    protected $casts = [
        'score' => 'array',
    ];

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

    /**
     * @param  Builder  $query
     */
    public function newEloquentBuilder($query): PositionCandidateBuilder
    {
        return new PositionCandidateBuilder($query);
    }

    protected static function newFactory(): PositionCandidateFactory
    {
        return PositionCandidateFactory::new();
    }
}
