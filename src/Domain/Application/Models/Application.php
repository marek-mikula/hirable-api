<?php

declare(strict_types=1);

namespace Domain\Application\Models;

use App\Enums\LanguageEnum;
use Carbon\Carbon;
use Domain\Application\Database\Factories\ApplicationFactory;
use Domain\Application\Models\Builders\ApplicationBuilder;
use Domain\Candidate\Enums\SourceEnum;
use Domain\Notification\Traits\Notifiable;
use Domain\Position\Models\Position;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;
use Support\File\Models\Traits\HasFiles;

/**
 * @property-read int $id
 * @property string $uuid
 * @property int $position_id
 * @property LanguageEnum $language
 * @property SourceEnum $source
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string $phone_prefix
 * @property string $phone_number
 * @property string|null $linkedin
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read string $full_name
 * @property-read Position $position
 *
 * @method static ApplicationFactory factory($count = null, $state = [])
 * @method static ApplicationBuilder query()
 */
class Application extends Model implements HasLocalePreference
{
    use HasFactory;
    use HasFiles;
    use Notifiable;

    protected $primaryKey = 'id';

    protected $table = 'applications';

    public $timestamps = true;

    protected $fillable = [
        'uuid',
        'position_id',
        'language',
        'source',
        'firstname',
        'lastname',
        'email',
        'phone_prefix',
        'phone_number',
        'linkedin',
    ];

    protected function casts(): array
    {
        return [
            'language' => LanguageEnum::class,
            'source' => SourceEnum::class,
        ];
    }

    protected function fullName(): Attribute
    {
        return Attribute::get(fn (): string => sprintf('%s %s', $this->firstname, $this->lastname));
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(
            related: Position::class,
            foreignKey: 'position_id',
            ownerKey: 'id',
        );
    }

    /**
     * @param  Builder  $query
     */
    public function newEloquentBuilder($query): ApplicationBuilder // @pest-ignore-type
    {
        return new ApplicationBuilder($query);
    }

    protected static function newFactory(): ApplicationFactory
    {
        return ApplicationFactory::new();
    }

    public function preferredLocale(): string
    {
        return $this->language->value;
    }
}
