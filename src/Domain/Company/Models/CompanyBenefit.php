<?php

declare(strict_types=1);

namespace Domain\Company\Models;

use Domain\Company\Database\Factories\CompanyBenefitFactory;
use Domain\Company\Models\Builders\CompanyBenefitBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * @property-read int $id
 * @property int $company_id
 * @property int $benefit_id
 *
 * @method static CompanyBenefitFactory factory($count = null, $state = [])
 * @method static CompanyBenefitBuilder query()
 */
class CompanyBenefit extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'company_benefits';

    public $timestamps = false;

    protected $fillable = [
        'company_id',
        'benefit_id',
    ];

    protected $casts = [];

    /**
     * @param  Builder  $query
     */
    public function newEloquentBuilder($query): CompanyBenefitBuilder // @pest-ignore-type
    {
        return new CompanyBenefitBuilder($query);
    }

    protected static function newFactory(): CompanyBenefitFactory
    {
        return CompanyBenefitFactory::new();
    }
}
