<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\Capitalize;
use App\Casts\Lowercase;
use App\Enums\LanguageEnum;
use App\Enums\TimezoneEnum;
use App\Models\Builders\UserBuilder;
use App\Traits\Notifiable;
use Carbon\Carbon;
use Database\Factories\UserFactory;
use Domain\Company\Enums\RoleEnum;
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
 * @property LanguageEnum $language
 * @property TimezoneEnum|null $timezone
 * @property string $firstname
 * @property string $lastname
 * @property string|null $prefix
 * @property string|null $postfix
 * @property string|null $phone
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property bool $notification_crucial_mail
 * @property bool $notification_crucial_app
 * @property bool $notification_technical_mail
 * @property bool $notification_technical_app
 * @property bool $notification_marketing_mail
 * @property bool $notification_marketing_app
 * @property bool $notification_application_mail
 * @property bool $notification_application_app
 * @property string $agreement_ip
 * @property Carbon $agreement_accepted_at
 * @property Carbon|null $email_verified_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read string $full_name
 * @property-read bool $is_email_verified
 * @property-read Collection<Token> $tokens
 * @property-read Company $company
 *
 * @method static UserFactory factory($count = null, $state = [])
 * @method static UserBuilder query()
 */
class User extends Authenticatable
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
        'notification_crucial_mail',
        'notification_crucial_app',
        'notification_technical_mail',
        'notification_technical_app',
        'notification_marketing_mail',
        'notification_marketing_app',
        'notification_application_mail',
        'notification_application_app',
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

    protected $casts = [
        'company_role' => RoleEnum::class,
        'language' => LanguageEnum::class,
        'timezone' => TimezoneEnum::class,
        'firstname' => Capitalize::class,
        'lastname' => Capitalize::class,
        'email' => Lowercase::class,
        'password' => 'hashed',
        'notification_crucial_mail' => 'boolean',
        'notification_crucial_app' => 'boolean',
        'notification_technical_mail' => 'boolean',
        'notification_technical_app' => 'boolean',
        'notification_marketing_mail' => 'boolean',
        'notification_marketing_app' => 'boolean',
        'notification_application_mail' => 'boolean',
        'notification_application_app' => 'boolean',
        'email_verified_at' => 'datetime',
        'agreement_accepted_at' => 'datetime',
    ];

    protected function isEmailVerified(): Attribute
    {
        return Attribute::get(fn (): bool => !empty($this->email_verified_at));
    }

    protected function fullName(): Attribute
    {
        return Attribute::get(function (): string {
            $name = sprintf('%s %s', $this->firstname, $this->lastname);

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
    public function newEloquentBuilder($query): UserBuilder
    {
        return new UserBuilder($query);
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
