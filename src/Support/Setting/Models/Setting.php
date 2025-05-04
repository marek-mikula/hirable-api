<?php

declare(strict_types=1);

namespace Support\Setting\Models;

use App\Models\Traits\HasArrayData;
use Carbon\Carbon;
use Database\Factories\SettingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Support\Setting\Enums\SettingKeyEnum;
use Support\Setting\Models\Builders\SettingBuilder;

/**
 * @property-read int $id
 * @property int $user_id
 * @property SettingKeyEnum $key
 * @property array $data
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static SettingFactory factory($count = null, $state = [])
 * @method static SettingBuilder query()
 */
class Setting extends Model
{
    use HasArrayData;
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'settings';

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'key',
        'data',
    ];

    protected $casts = [
        'key' => SettingKeyEnum::class,
        'data' => 'array',
    ];

    /**
     * @param  Builder  $query
     */
    public function newEloquentBuilder($query): SettingBuilder
    {
        return new SettingBuilder($query);
    }

    protected static function newFactory(): SettingFactory
    {
        return SettingFactory::new();
    }
}
