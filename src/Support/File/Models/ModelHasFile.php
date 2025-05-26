<?php

declare(strict_types=1);

namespace Support\File\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Support\File\Database\Factories\ModelHasFileFactory;

/**
 * @property-read int $id
 * @property int $file_id
 * @property class-string<Model> $fileable_type
 * @property int $fileable_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static ModelHasFileFactory factory($count = null, $state = [])
 */
class ModelHasFile extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'model_has_files';

    public $timestamps = true;

    protected $fillable = [
        'file_id',
        'fileable_type',
        'fileable_id',
    ];

    protected $casts = [];

    protected static function newFactory(): ModelHasFileFactory
    {
        return ModelHasFileFactory::new();
    }
}
