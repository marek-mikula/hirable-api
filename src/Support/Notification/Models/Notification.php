<?php

declare(strict_types=1);

namespace Support\Notification\Models;

use App\Models\Traits\HasArrayData;
use Carbon\Carbon;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;
use Support\Notification\Database\Factories\NotificationFactory;
use Support\Notification\Enums\NotificationTypeEnum;

/**
 * @property-read string $id UUID string
 * @property NotificationTypeEnum $type
 * @property class-string<Model> $notifiable_type
 * @property int $notifiable_id
 * @property array $data
 * @property Carbon|null $read_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User $notifiable
 *
 * @method static NotificationFactory factory($count = null, $state = [])
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

    protected static function newFactory(): NotificationFactory
    {
        return NotificationFactory::new();
    }
}
