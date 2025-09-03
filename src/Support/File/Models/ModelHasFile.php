<?php

declare(strict_types=1);

namespace Support\File\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Support\File\Database\Factories\ModelHasFileFactory;

/**
 * @property-read int $id
 * @property int $file_id
 * @property class-string<Model> $fileable_type
 * @property int $fileable_id
 * @property-read File $file
 * @property-read Model $fileable
 *
 * @method static ModelHasFileFactory factory($count = null, $state = [])
 */
class ModelHasFile extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'model_has_files';

    public $timestamps = false;

    protected $fillable = [
        'file_id',
        'fileable_type',
        'fileable_id',
    ];

    public function file(): BelongsTo
    {
        return $this->belongsTo(
            related: File::class,
            foreignKey: 'file_id',
            ownerKey: 'id',
        );
    }

    public function fileable(): MorphTo
    {
        return $this->morphTo(
            name: 'fileable',
            ownerKey: 'id',
        );
    }

    protected static function newFactory(): ModelHasFileFactory
    {
        return ModelHasFileFactory::new();
    }
}
