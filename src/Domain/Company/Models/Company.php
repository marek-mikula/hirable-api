<?php

declare(strict_types=1);

namespace Domain\Company\Models;

use App\Casts\Capitalize;
use App\Casts\Lowercase;
use Carbon\Carbon;
use Domain\Company\Database\Factories\CompanyFactory;
use Domain\Company\Models\Builders\CompanyBuilder;
use Domain\Position\Models\Position;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

/**
 * @property-read int $id
 * @property string $name
 * @property string $email
 * @property string $id_number
 * @property string|null $website
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection<User> $users
 * @property-read Collection<CompanyContact> $contacts
 * @property-read Collection<Position> $positions
 *
 * @method static CompanyFactory factory($count = null, $state = [])
 * @method static CompanyBuilder query()
 */
class Company extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'companies';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'email',
        'id_number',
        'website',
    ];

    protected $attributes = [];

    protected $casts = [
        'name' => Capitalize::class,
        'email' => Lowercase::class,
    ];

    public function users(): HasMany
    {
        return $this->hasMany(
            related: User::class,
            foreignKey: 'company_id',
            localKey: 'id',
        );
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(
            related: CompanyContact::class,
            foreignKey: 'company_id',
            localKey: 'id',
        );
    }

    public function positions(): HasMany
    {
        return $this->hasMany(
            related: Position::class,
            foreignKey: 'company_id',
            localKey: 'id',
        );
    }

    /**
     * @param  Builder  $query
     */
    public function newEloquentBuilder($query): CompanyBuilder // @pest-ignore-type
    {
        return new CompanyBuilder($query);
    }

    protected static function newFactory(): CompanyFactory
    {
        return CompanyFactory::new();
    }
}
