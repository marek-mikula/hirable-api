<?php

declare(strict_types=1);

namespace Support\File\Models;

use App\Casts\Lowercase;
use App\Models\Traits\HasArrayData;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Support\File\Database\Factories\FileFactory;
use Support\File\Enums\FileTypeEnum;

/**
 * @property-read int $id
 * @property FileTypeEnum $type
 * @property string $name
 * @property string $mime
 * @property string $path
 * @property-read string $real_path
 * @property string $extension
 * @property int $size file size in bytes
 * @property array $data
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 *
 * @method static FileFactory factory($count = null, $state = [])
 */
class File extends Model
{
    use HasArrayData;
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'id';

    protected $table = 'files';

    public $timestamps = true;

    protected $fillable = [
        'type',
        'name',
        'mime',
        'path',
        'extension',
        'size',
        'data',
    ];

    protected $attributes = [
        'data' => '{}'
    ];

    protected $casts = [
        'type' => FileTypeEnum::class,
        'extension' => Lowercase::class,
        'data' => 'array',
    ];

    protected function realPath(): Attribute
    {
        return Attribute::get(fn (): string => Storage::disk($this->type->getDomain()->getDisk())->path($this->path));
    }

    protected static function newFactory(): FileFactory
    {
        return FileFactory::new();
    }
}
