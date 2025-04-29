<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Builders\CandidateBuilder;
use Carbon\Carbon;
use Database\Factories\CandidateFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * @property-read int $id
 * @property string $firstname
 * @property string $lastname
 * @property-read string $full_name
 * @property string $email
 * @property string|null $phone_prefix
 * @property string|null $phone
 * @property string|null $linkedin
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static CandidateFactory factory($count = null, $state = [])
 * @method static CandidateBuilder query()
 */
class Candidate extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'candidates';

    public $timestamps = true;

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'phone_prefix',
        'phone',
        'linkedin',
    ];

    protected $casts = [];

    protected function fullName(): Attribute
    {
        return Attribute::get(fn (): string => sprintf('%s %s', $this->firstname, $this->lastname));
    }

    /**
     * @param  Builder  $query
     */
    public function newEloquentBuilder($query): CandidateBuilder
    {
        return new CandidateBuilder($query);
    }

    protected static function newFactory(): CandidateFactory
    {
        return CandidateFactory::new();
    }
}
