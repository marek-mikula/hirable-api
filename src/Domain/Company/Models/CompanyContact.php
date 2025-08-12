<?php

declare(strict_types=1);

namespace Domain\Company\Models;

use App\Enums\LanguageEnum;
use Carbon\Carbon;
use Domain\Company\Database\Factories\CompanyContactFactory;
use Domain\Company\Models\Builders\CompanyContactBuilder;
use Domain\Notification\Traits\Notifiable;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

/**
 * @property-read int $id
 * @property int $company_id
 * @property LanguageEnum $language
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string|null $note
 * @property string|null $company_name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read string $label
 * @property-read string $full_name
 * @property-read Company $company
 *
 * @method static CompanyContactFactory factory($count = null, $state = [])
 * @method static CompanyContactBuilder query()
 */
class CompanyContact extends Model implements HasLocalePreference
{
    use HasFactory;
    use Notifiable;

    protected $primaryKey = 'id';

    protected $table = 'company_contacts';

    public $timestamps = true;

    protected $fillable = [
        'company_id',
        'language',
        'firstname',
        'lastname',
        'email',
        'note',
        'company_name',
    ];

    protected $casts = [
        'language' => LanguageEnum::class,
    ];

    protected function label(): Attribute
    {
        return Attribute::get(fn (): string => $this->company_name ? sprintf('%s (%s)', $this->full_name, $this->company_name) : $this->full_name);
    }

    protected function fullName(): Attribute
    {
        return Attribute::get(fn (): string => sprintf('%s %s', $this->firstname, $this->lastname));
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(
            related: Company::class,
            foreignKey: 'company_id',
            ownerKey: 'id',
        );
    }

    /**
     * @param  Builder  $query
     */
    public function newEloquentBuilder($query): CompanyContactBuilder
    {
        return new CompanyContactBuilder($query);
    }

    protected static function newFactory(): CompanyContactFactory
    {
        return CompanyContactFactory::new();
    }

    public function preferredLocale(): string
    {
        return $this->language->value;
    }
}
