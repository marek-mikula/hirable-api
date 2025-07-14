<?php

declare(strict_types=1);

namespace Domain\Application\Models;

use Carbon\Carbon;
use Domain\Application\Database\Factories\ApplicationFactory;
use Domain\Application\Models\Builders\ApplicationBuilder;
use Domain\Candidate\Enums\SourceEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

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
 *
 * @method static ApplicationFactory factory($count = null, $state = [])
 * @method static ApplicationBuilder query()
 */
class Application extends Model
{
    use HasFactory;

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
