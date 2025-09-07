<?php

declare(strict_types=1);

namespace Domain\Position\Models;

use Carbon\Carbon;
use Domain\Position\Database\Factories\PositionCandidateEvaluationFactory;
use Domain\Position\Enums\EvaluationResultEnum;
use Domain\Position\Models\Builders\PositionCandidateEvaluationBuilder;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

/**
 * @property-read int $id
 * @property int $position_candidate_id
 * @property int $user_id
 * @property string|null $evaluation
 * @property EvaluationResultEnum|null $result
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read PositionCandidate $positionCandidate
 * @property-read User $user
 *
 * @method static PositionCandidateEvaluationFactory factory($count = null, $state = [])
 * @method static PositionCandidateEvaluationBuilder query()
 */
class PositionCandidateEvaluation extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'position_candidate_evaluations';

    public $timestamps = true;

    protected $fillable = [
        'position_candidate_id',
        'user_id',
        'evaluation',
        'result',
    ];

    protected function casts(): array
    {
        return [
            'result' => EvaluationResultEnum::class,
        ];
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
    public function newEloquentBuilder($query): PositionCandidateEvaluationBuilder // @pest-ignore-type
    {
        return new PositionCandidateEvaluationBuilder($query);
    }

    protected static function newFactory(): PositionCandidateEvaluationFactory
    {
        return PositionCandidateEvaluationFactory::new();
    }
}
