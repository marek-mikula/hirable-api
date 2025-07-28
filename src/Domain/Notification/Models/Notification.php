<?php

declare(strict_types=1);

namespace Domain\Notification\Models;

use App\Models\Traits\HasArrayData;
use Carbon\Carbon;
use Domain\Notification\Database\Factories\NotificationFactory;
use Domain\Notification\Enums\NotificationTypeEnum;
use Domain\Notification\Models\Builders\NotificationBuilder;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Database\Query\Builder;

/**
 * @property-read string $id UUID string
 * @property NotificationTypeEnum $type
 * @property class-string<User> $notifiable_type
 * @property int $notifiable_id
 * @property array $data
 * @property Carbon|null $read_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read bool $is_read
 * @property-read User $notifiable
 *
 * @method static NotificationFactory factory($count = null, $state = [])
 * @method static NotificationBuilder query()
 */
class Notification extends DatabaseNotification
{
    use HasArrayData;
    use HasFactory;

    protected $casts = [
        'type' => NotificationTypeEnum::class,
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    public function isRead(): Attribute
    {
        return Attribute::get(fn (): bool => !empty($this->read_at));
    }

    /**
     * @param  Builder  $query
     */
    public function newEloquentBuilder($query): NotificationBuilder // @pest-ignore-type
    {
        return new NotificationBuilder($query);
    }

    protected static function newFactory(): NotificationFactory
    {
        return NotificationFactory::new();
    }
}
