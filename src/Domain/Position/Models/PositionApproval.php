<?php

declare(strict_types=1);

namespace Domain\Position\Models;

use Carbon\Carbon;
use Domain\Position\Database\Factories\PositionApprovalFactory;
use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Models\Builders\PositionApprovalBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;
use Support\Token\Models\Token;

/**
 * @property-read int $id
 * @property int|null $model_has_position_id
 * @property int $position_id
 * @property int|null $token_id
 * @property PositionApprovalStateEnum $state
 * @property string|null $note
 * @property Carbon|null $decided_at
 * @property Carbon|null $notified_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read ModelHasPosition|null $modelHasPosition
 * @property-read Position $position
 * @property-read Token|null $token
 *
 * @method static PositionApprovalFactory factory($count = null, $state = [])
 * @method static PositionApprovalBuilder query()
 */
class PositionApproval extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'position_approvals';

    public $timestamps = true;

    protected $fillable = [
        'model_has_position_id',
        'position_id',
        'token_id',
        'state',
        'note',
        'decided_at',
        'notified_at',
    ];

    protected $casts = [
        'state' => PositionApprovalStateEnum::class,
        'decided_at' => 'datetime',
        'notified_at' => 'datetime',
    ];

    public function modelHasPosition(): BelongsTo
    {
        return $this->belongsTo(
            related: ModelHasPosition::class,
            foreignKey: 'model_has_position_id',
            ownerKey: 'id',
        );
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(
            related: Position::class,
            foreignKey: 'position_id',
            ownerKey: 'id',
        );
    }

    public function token(): BelongsTo
    {
        return $this->belongsTo(
            related: Token::class,
            foreignKey: 'token_id',
            ownerKey: 'id',
        );
    }

    /**
     * @param  Builder  $query
     */
    public function newEloquentBuilder($query): PositionApprovalBuilder
    {
        return new PositionApprovalBuilder($query);
    }

    protected static function newFactory(): PositionApprovalFactory
    {
        return PositionApprovalFactory::new();
    }
}
