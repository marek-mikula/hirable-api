<?php

declare(strict_types=1);

namespace Support\Token\Models;

use App\Models\Traits\HasArrayData;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Crypt;
use Support\Token\Actions\GetTokenLinkAction;
use Support\Token\Database\Factories\TokenFactory;
use Support\Token\Enums\TokenTypeEnum;
use Support\Token\Models\Builders\TokenBuilder;

/**
 * @property-read int $id
 * @property int|null $user_id
 * @property TokenTypeEnum $type
 * @property string $token
 * @property string|null $code
 * @property array $data
 * @property Carbon|null $used_at
 * @property Carbon $valid_until
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read bool $is_expired
 * @property-read bool $is_used
 * @property-read string $secret_token
 * @property-read string $link
 * @property-read User|null $user
 *
 * @method static TokenFactory factory($count = null, $state = [])
 * @method static TokenBuilder query()
 */
class Token extends Model
{
    use HasArrayData;
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'tokens';

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'type',
        'token',
        'data',
        'used_at',
        'valid_until',
    ];

    protected $casts = [
        'type' => TokenTypeEnum::class,
        'data' => 'array',
        'used_at' => 'datetime',
        'valid_until' => 'datetime',
    ];

    protected function link(): Attribute
    {
        return Attribute::get(fn (): string => GetTokenLinkAction::make()->handle($this))->shouldCache();
    }

    protected function secretToken(): Attribute
    {
        return Attribute::get(fn (): string => Crypt::encryptString($this->token))->shouldCache();
    }

    protected function isExpired(): Attribute
    {
        return Attribute::get(fn (): bool => !$this->valid_until->isFuture());
    }

    protected function isUsed(): Attribute
    {
        return Attribute::get(fn (): bool => !empty($this->used_at));
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'user_id',
            ownerKey: 'id',
        );
    }

    /**
     * @param  Builder  $query
     */
    public function newEloquentBuilder($query): TokenBuilder
    {
        return new TokenBuilder($query);
    }

    protected static function newFactory(): TokenFactory
    {
        return TokenFactory::new();
    }
}
