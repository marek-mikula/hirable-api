<?php

declare(strict_types=1);

namespace Domain\Position\Models;

use Carbon\Carbon;
use Domain\Position\Database\Factories\PositionCandidateShareFactory;
use Domain\Position\Models\Builders\PositionCandidateEvaluationBuilder;
use Domain\Position\Models\Builders\PositionCandidateShareBuilder;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

/**
 * @property-read int $id
 * @property int $position_candidate_id
 * @property int $user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read PositionCandidate $positionCandidate
 * @property-read User $user
 *
 * @method static PositionCandidateShareFactory factory($count = null, $state = [])
 * @method static PositionCandidateEvaluationBuilder query()
 */
class PositionCandidateShare extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'position_candidate_shares';

    public $timestamps = true;

    protected $fillable = [
        'position_candidate_id',
        'user_id',
    ];

    protected function casts(): array
    {
        return [];
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
    public function newEloquentBuilder($query): PositionCandidateShareBuilder // @pest-ignore-type
    {
        return new PositionCandidateShareBuilder($query);
    }

    protected static function newFactory(): PositionCandidateShareFactory
    {
        return PositionCandidateShareFactory::new();
    }
}
