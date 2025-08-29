<?php

declare(strict_types=1);

namespace Support\File\Models;

use App\Casts\Lowercase;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Support\File\Database\Factories\FileFactory;
use Support\File\Enums\FileDiskEnum;
use Support\File\Enums\FileTypeEnum;

/**
 * @property-read int $id
 * @property FileTypeEnum $type
 * @property FileDiskEnum $disk
 * @property string $name
 * @property string $filename
 * @property string $mime
 * @property string $path
 * @property string $extension
 * @property int $size file size in bytes
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property-read string $real_path
 * @property-read Collection<ModelHasFile> $modelHasFiles
 *
 * @method static FileFactory factory($count = null, $state = [])
 */
class File extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'id';

    protected $table = 'files';

    public $timestamps = true;

    protected $fillable = [
        'type',
        'disk',
        'name',
        'mime',
        'path',
        'extension',
        'size',
    ];

    protected function casts(): array
    {
        return [
            'type' => FileTypeEnum::class,
            'disk' => FileDiskEnum::class,
            'extension' => Lowercase::class,
        ];
    }

    protected function realPath(): Attribute
    {
        return Attribute::get(fn (): string => Storage::disk($this->disk->value)->path($this->path));
    }

    protected function filename(): Attribute
    {
        return Attribute::get(fn (): string => basename($this->path));
    }

    public function modelHasFiles(): HasMany
    {
        return $this->hasMany(
            related: ModelHasFile::class,
            foreignKey: 'file_id',
            localKey: 'id',
        );
    }

    protected static function newFactory(): FileFactory
    {
        return FileFactory::new();
    }
}
