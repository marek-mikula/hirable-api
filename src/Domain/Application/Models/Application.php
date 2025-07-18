<?php

declare(strict_types=1);

namespace Domain\Application\Models;

use Carbon\Carbon;
use Domain\Application\Database\Factories\ApplicationFactory;
use Domain\Application\Models\Builders\ApplicationBuilder;
use Domain\Candidate\Enums\SourceEnum;
use Domain\Candidate\Models\Candidate;
use Domain\Position\Models\Position;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;
use Support\File\Models\Traits\HasFiles;

/**
 * @property-read int $id
 * @property int $position_id
 * @property int|null $candidate_id
 * @property SourceEnum $source
 * @property boolean $processed
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string $phone_prefix
 * @property string $phone_number
 * @property string|null $linkedin
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Position $position
 * @property-read Candidate|null $candidate
 *
 * @method static ApplicationFactory factory($count = null, $state = [])
 * @method static ApplicationBuilder query()
 */
class Application extends Model
{
    use HasFactory;
    use HasFiles;

    protected $primaryKey = 'id';

    protected $table = 'applications';

    public $timestamps = true;

    protected $fillable = [
        'position_id',
        'candidate_id',
        'source',
        'processed',
        'firstname',
        'lastname',
        'email',
        'phone_prefix',
        'phone_number',
        'linkedin',
    ];

    protected $casts = [
        'source' => SourceEnum::class,
        'processed' => 'boolean',
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

    /**
     * @param  Builder  $query
     */
    public function newEloquentBuilder($query): ApplicationBuilder
    {
        return new ApplicationBuilder($query);
    }

    protected static function newFactory(): ApplicationFactory
    {
        return ApplicationFactory::new();
    }
}
