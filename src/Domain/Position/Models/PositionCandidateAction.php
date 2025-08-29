<?php

declare(strict_types=1);

namespace Domain\Position\Models;

use Carbon\Carbon;
use Domain\Position\Database\Factories\PositionCandidateActionFactory;
use Domain\Position\Enums\ActionStateEnum;
use Domain\Position\Enums\ActionTypeEnum;
use Domain\Position\Models\Builders\PositionCandidateActionBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

/**
 * @property-read int $id
 * @property int $position_candidate_id
 * @property ActionTypeEnum $type
 * @property ActionStateEnum $state
 * @property Carbon|null $datetime_start
 * @property Carbon|null $datetime_end
 * @property string|null $note
 * @property string|null $address
 * @property string|null $instructions
 * @property string|null $result
 * @property string|null $interview_form
 * @property string|null $interview_type
 * @property string|null $rejection_reason
 * @property string|null $refusal_reason
 * @property string|null $test_type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read PositionCandidate $positionCandidate
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
        'position_candidate_id',
        'type',
        'state',
        'datetime_start',
        'datetime_end',
        'note',
        'address',
        'instructions',
        'result',
        'interview_form',
        'interview_type',
        'rejection_reason',
        'refusal_reason',
        'test_type',
    ];

    protected function casts(): array
    {
        return [
            'type' => ActionTypeEnum::class,
            'state' => ActionStateEnum::class,
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

    /**
     * @param  Builder  $query
     */
    public function newEloquentBuilder($query): PositionCandidateActionBuilder
    {
        return new PositionCandidateActionBuilder($query);
    }

    protected static function newFactory(): PositionCandidateActionFactory
    {
        return PositionCandidateActionFactory::new();
    }
}
