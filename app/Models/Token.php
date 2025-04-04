<?php

namespace App\Models;

use App\Models\Builders\TokenBuilder;
use App\Models\Traits\HasArrayData;
use Carbon\Carbon;
use Database\Factories\TokenFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Crypt;
use Support\Token\Actions\GetTokenLinkAction;
use Support\Token\Enums\TokenTypeEnum;

/**
 * @property-read int $id
 * @property int|null $user_id
 * @property TokenTypeEnum $type
 * @property string $token
 * @property string|null $code
 * @property array $data
 * @property Carbon $valid_until
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read bool $is_expired
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
        'valid_until',
    ];

    protected $casts = [
        'type' => TokenTypeEnum::class,
        'data' => 'array',
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
        return Attribute::get(fn (): bool => ! $this->valid_until->isFuture());
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
