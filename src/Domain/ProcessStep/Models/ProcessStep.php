<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Models;

use App\Casts\EnumOrValue;
use Domain\Company\Models\Company;
use Domain\ProcessStep\Database\Factories\ProcessStepFactory;
use Domain\ProcessStep\Enums\ProcessStepEnum;
use Domain\ProcessStep\Models\Builders\ProcessStepBuilder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

/**
 * @property-read int $id
 * @property int|null $company_id
 * @property ProcessStepEnum|string $step
 * @property-read bool $is_custom
 * @property-read Company|null $company
 *
 * @method static ProcessStepFactory factory($count = null, $state = [])
 * @method static ProcessStepBuilder query()
 */
class ProcessStep extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'process_steps';

    public $timestamps = false;

    protected $fillable = [
        'company_id',
        'step',
    ];

    protected $casts = [
        'step' => EnumOrValue::class . ':' . ProcessStepEnum::class,
    ];

    public function isCustom(): Attribute
    {
        return Attribute::get(fn () => is_string($this->step));
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
    public function newEloquentBuilder($query): ProcessStepBuilder
    {
        return new ProcessStepBuilder($query);
    }

    protected static function newFactory(): ProcessStepFactory
    {
        return ProcessStepFactory::new();
    }
}
