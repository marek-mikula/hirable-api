<?php

declare(strict_types=1);

namespace Domain\Candidate\Models;

use App\Casts\Capitalize;
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
use Illuminate\Database\Eloquent\Relations\MorphToMany;
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
 * @property string $email
 * @property string $phone_prefix
 * @property string $phone_number
 * @property-read string $phone
 * @property string|null $linkedin
 * @property string|null $instagram
 * @property string|null $github
 * @property string|null $portfolio
 * @property Carbon|null $birth_date
 * @property array $experience
 * @property array $tags
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read string $full_name
 * @property-read File $cvs
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
        'tags',
    ];

    protected $attributes = [
        'experience' => '[]',
        'tags' => '[]',
    ];

    protected $casts = [
        'firstname' => Capitalize::class,
        'lastname' => Capitalize::class,
        'language' => LanguageEnum::class,
        'gender' => GenderEnum::class,
        'birth_date' => 'date',
        'experience' => 'array',
        'tags' => 'array',
    ];

    protected function fullName(): Attribute
    {
        return Attribute::get(fn (): string => sprintf('%s %s', $this->firstname, $this->lastname));
    }

    protected function phone(): Attribute
    {
        return Attribute::get(fn (): string => $this->phone_prefix . $this->phone_number);
    }

    public function cvs(): MorphToMany
    {
        return $this->files()->where('type', FileTypeEnum::CANDIDATE_CV)->latest('id');
    }

    public function otherFiles(): MorphToMany
    {
        return $this->files()->where('type', FileTypeEnum::CANDIDATE_OTHER)->latest('id');
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
