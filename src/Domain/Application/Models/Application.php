<?php

declare(strict_types=1);

namespace Domain\Application\Models;

use App\Enums\LanguageEnum;
use Carbon\Carbon;
use Domain\Application\Database\Factories\ApplicationFactory;
use Domain\Application\Models\Builders\ApplicationBuilder;
use Domain\Candidate\Enums\GenderEnum;
use Domain\Candidate\Enums\SourceEnum;
use Domain\Candidate\Models\Candidate;
use Domain\Notification\Traits\Notifiable;
use Domain\Position\Models\Position;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Query\Builder;
use Support\File\Enums\FileTypeEnum;
use Support\File\Models\File;
use Support\File\Models\Traits\HasFiles;

/**
 * @property-read int $id
 * @property string $uuid
 * @property int $position_id
 * @property int|null $candidate_id
 * @property boolean $processed
 * @property LanguageEnum $language
 * @property GenderEnum|null $gender
 * @property SourceEnum $source
 * @property string $firstname
 * @property string $lastname
 * @property-read string $full_name
 * @property string $email
 * @property string $phone_prefix
 * @property string $phone_number
 * @property string|null $linkedin
 * @property string|null $instagram
 * @property string|null $github
 * @property string|null $portfolio
 * @property Carbon|null $birth_date
 * @property array $experience
 * @property array $score
 * @property int|null $total_score
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Position $position
 * @property-read Candidate|null $candidate
 * @property-read File $cv
 * @property-read Collection<File> $otherFiles
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
        'candidate_id',
        'processed',
        'language',
        'gender',
        'source',
        'firstname',
        'lastname',
        'email',
        'phone_prefix',
        'phone_number',
        'linkedin',
        'instagram',
        'github',
        'portfolio',
        'birth_date',
        'experience',
        'score',
        'total_score',
    ];

    protected $attributes = [
        'experience' => '[]',
        'score' => '{}',
    ];

    protected $casts = [
        'language' => LanguageEnum::class,
        'gender' => GenderEnum::class,
        'source' => SourceEnum::class,
        'processed' => 'boolean',
        'experience' => 'array',
        'score' => 'array',
    ];

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

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(
            related: Candidate::class,
            foreignKey: 'candidate_id',
            ownerKey: 'id',
        );
    }

    public function cv(): MorphOne
    {
        return $this->files()->where('type', FileTypeEnum::APPLICATION_CV->value)->one();
    }

    public function otherFiles(): MorphMany
    {
        return $this->files()->where('type', FileTypeEnum::APPLICATION_OTHER->value);
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

    public function preferredLocale(): string
    {
        return $this->language->value;
    }
}
