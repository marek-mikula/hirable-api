<?php

namespace App\Models;

use App\Casts\Capitalize;
use App\Casts\Lowercase;
use App\Models\Builders\CompanyBuilder;
use Carbon\Carbon;
use Database\Factories\CompanyFactory;
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

    /**
     * @param  Builder  $query
     */
    public function newEloquentBuilder($query): CompanyBuilder
    {
        return new CompanyBuilder($query);
    }

    protected static function newFactory(): CompanyFactory
    {
        return CompanyFactory::new();
    }
}
