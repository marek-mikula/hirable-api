<?php

declare(strict_types=1);

namespace Support\Classifier\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Support\Classifier\Database\Factories\ClassifierFactory;
use Support\Classifier\Enums\ClassifierTypeEnum;
use Support\Classifier\Models\Builders\ClassifierBuilder;
use Support\Classifier\Services\ClassifierTranslateService;

/**
 * @property-read int $id
 * @property ClassifierTypeEnum $type
 * @property string $value
 * @property-read string $label
 *
 * @method static ClassifierFactory factory($count = null, $state = [])
 * @method static ClassifierBuilder query()
 */
class Classifier extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'classifiers';

    public $timestamps = false;

    protected $fillable = [
        'type',
        'value',
    ];

    protected function casts(): array
    {
        return [
            'type' => ClassifierTypeEnum::class,
        ];
    }

    protected function label(): Attribute
    {
        return Attribute::get(injectClosure(fn (ClassifierTranslateService $translateService) => $translateService->translate($this)));
    }

    /**
     * @param  Builder  $query
     */
    public function newEloquentBuilder($query): ClassifierBuilder // @pest-ignore-type
    {
        return new ClassifierBuilder($query);
    }

    protected static function newFactory(): ClassifierFactory
    {
        return ClassifierFactory::new();
    }
}
