<?php

declare(strict_types=1);

namespace Domain\Candidate\Models;

use App\Enums\LanguageEnum;
use Carbon\Carbon;
use Domain\Candidate\Database\Factories\CandidateFactory;
use Domain\Candidate\Enums\GenderEnum;
use Domain\Candidate\Models\Builders\CandidateBuilder;
use Domain\Notification\Traits\Notifiable;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Query\Builder;
use Support\File\Enums\FileTypeEnum;
use Support\File\Models\File;
use Support\File\Models\Traits\HasFiles;

/**
 * @property-read int $id
 * @property int $company_id
 * @property LanguageEnum $language
 * @property GenderEnum|null $gender
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
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read File $cv
 * @property-read Collection<File> $otherFiles
 *
 * @method static CandidateFactory factory($count = null, $state = [])
 * @method static CandidateBuilder query()
 */
class Candidate extends Model implements HasLocalePreference
{
    use HasFactory;
    use Notifiable;
    use HasFiles;

    protected $primaryKey = 'id';

    protected $table = 'candidates';

    public $timestamps = true;

    protected $fillable = [
        'company_id',
        'language',
        'gender',
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
    ];

    protected $attributes = [
        'experience' => '[]',
    ];

    protected $casts = [
        'language' => LanguageEnum::class,
        'gender' => GenderEnum::class,
        'experience' => 'array',
    ];

    protected function fullName(): Attribute
    {
        return Attribute::get(fn (): string => sprintf('%s %s', $this->firstname, $this->lastname));
    }

    public function cv(): MorphOne
    {
        return $this->files()->where('type', FileTypeEnum::CANDIDATE_CV->value)->one();
    }

    public function otherFiles(): MorphMany
    {
        return $this->files()->where('type', FileTypeEnum::CANDIDATE_OTHER->value);
    }

    /**
     * @param  Builder  $query
     */
    public function newEloquentBuilder($query): CandidateBuilder // @pest-ignore-type
    {
        return new CandidateBuilder($query);
    }

    protected static function newFactory(): CandidateFactory
    {
        return CandidateFactory::new();
    }

    public function preferredLocale(): string
    {
        return $this->language->value;
    }
}
