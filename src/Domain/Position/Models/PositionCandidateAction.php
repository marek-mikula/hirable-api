<?php

declare(strict_types=1);

namespace Domain\Position\Models;

use App\Casts\TimeCast;
use Carbon\Carbon;
use Domain\Position\Database\Factories\PositionCandidateActionFactory;
use Domain\Position\Enums\ActionStateEnum;
use Domain\Position\Enums\ActionTypeEnum;
use Domain\Position\Models\Builders\PositionCandidateActionBuilder;
use Domain\Position\Models\Casts\OfferCast;
use Domain\Position\Models\Data\OfferData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

/**
 * @property-read int $id
 * @property int $position_candidate_id
 * @property ActionTypeEnum $type
 * @property ActionStateEnum $state
 * @property Carbon|null $date
 * @property Carbon|null $time_start
 * @property Carbon|null $time_end
 * @property string|null $note
 * @property string|null $place
 * @property string|null $instructions
 * @property string|null $result
 * @property string|null $name
 * @property string|null $interview_form
 * @property string|null $interview_type
 * @property string|null $rejection_reason
 * @property string|null $refusal_reason
 * @property string|null $test_type
 * @property OfferData|null $offer
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
        'date',
        'time_start',
        'time_end',
        'note',
        'place',
        'instructions',
        'result',
        'name',
        'interview_form',
        'interview_type',
        'rejection_reason',
        'refusal_reason',
        'test_type',
        'offer',
    ];

    protected function casts(): array
    {
        return [
            'type' => ActionTypeEnum::class,
            'state' => ActionStateEnum::class,
            'date' => 'date',
            'time_start' => TimeCast::class,
            'time_end' => TimeCast::class,
            'offer' => OfferCast::class,
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
    public function newEloquentBuilder($query): PositionCandidateActionBuilder // @pest-ignore-type
    {
        return new PositionCandidateActionBuilder($query);
    }

    protected static function newFactory(): PositionCandidateActionFactory
    {
        return PositionCandidateActionFactory::new();
    }
}
