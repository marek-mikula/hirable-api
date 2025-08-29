<?php

declare(strict_types=1);

namespace Domain\User\Models;

use App\Casts\Capitalize;
use App\Casts\Lowercase;
use App\Enums\LanguageEnum;
use Carbon\Carbon;
use Domain\Company\Enums\RoleEnum;
use Domain\Company\Models\Company;
use Domain\Notification\Traits\Notifiable;
use Domain\User\Database\Factories\UserFactory;
use Domain\User\Models\Builders\UserBuilder;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Support\ActivityLog\Traits\CausesActivity;
use Support\Token\Models\Token;

/**
 * @property-read int $id
 * @property int $company_id
 * @property RoleEnum $company_role
 * @property bool $company_owner
 * @property LanguageEnum $language
 * @property string $firstname
 * @property string $lastname
 * @property string|null $prefix
 * @property string|null $postfix
 * @property string|null $phone
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property string $agreement_ip
 * @property Carbon $agreement_accepted_at
 * @property Carbon|null $email_verified_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read string $full_name
 * @property-read string $full_qualified_name
 * @property-read string $label
 * @property-read bool $is_email_verified
 * @property-read Collection<Token> $tokens
 * @property-read Company $company
 *
 * @method static UserFactory factory($count = null, $state = [])
 * @method static UserBuilder query()
 */
class User extends Authenticatable implements HasLocalePreference
{
    use CausesActivity;
    use HasFactory;
    use Notifiable;

    protected $primaryKey = 'id';

    protected $table = 'users';

    public $timestamps = true;

    protected $fillable = [
        'company_id',
        'company_role',
        'company_owner',
        'language',
        'firstname',
        'lastname',
        'prefix',
        'postfix',
        'phone',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'agreement_ip',
        'agreement_accepted_at',
    ];

    protected $appends = [
        'full_name',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'company_role' => RoleEnum::class,
            'company_owner' => 'boolean',
            'language' => LanguageEnum::class,
            'firstname' => Capitalize::class,
            'lastname' => Capitalize::class,
            'email' => Lowercase::class,
            'password' => 'hashed',
            'email_verified_at' => 'datetime',
            'agreement_accepted_at' => 'datetime',
        ];
    }

    protected function isEmailVerified(): Attribute
    {
        return Attribute::get(fn (): bool => !empty($this->email_verified_at));
    }

    protected function label(): Attribute
    {
        return Attribute::get(fn (): string => $this->is(auth()->user()) ? sprintf('%s (%s)', $this->full_name, __('common.you')) : $this->full_name);
    }

    protected function fullName(): Attribute
    {
        return Attribute::get(fn (): string => sprintf('%s %s', $this->firstname, $this->lastname));
    }

    protected function fullQualifiedName(): Attribute
    {
        return Attribute::get(function (): string {
            $name = $this->full_name;

            if (!empty($this->prefix)) {
                $name = sprintf('%s %s', $this->prefix, $name);
            }

            if (!empty($this->postfix)) {
                $name = sprintf('%s, %s', $name, $this->postfix);
            }

            return $name;
        });
    }

    public function tokens(): HasMany
    {
        return $this->hasMany(
            related: Token::class,
            foreignKey: 'user_id',
            localKey: 'id',
        );
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(
            related: Company::class,
            foreignKey: 'company_id',
            ownerKey: 'id'
        );
    }

    /**
     * @param  Builder  $query
     */
    public function newEloquentBuilder($query): UserBuilder // @pest-ignore-type
    {
        return new UserBuilder($query);
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    public function preferredLocale(): string
    {
        return $this->language->value;
    }
}
