<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\File;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Testing\File as FakeFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Support\File\Enums\FileTypeEnum;

/**
 * @extends BaseFactory<File>
 */
class FileFactory extends Factory
{
    protected $model = File::class;

    public function definition(): array
    {
        $type = FileTypeEnum::TEMP;

        $filename = Str::uuid().'.jpg';

        $file = FakeFile::fake()->image($filename);

        $path = Storage::disk($type->getDomain()->getDisk())->putFile('/', $file);

        return [
            'type' => FileTypeEnum::TEMP,
            'name' => $filename,
            'mime' => 'image/jpeg',
            'path' => $path,
            'extension' => 'jpg',
            'size' => 300,
            'fileable_type' => User::class,
            'fileable_id' => $this->isMaking ? null : User::factory(),
            'data' => [],
        ];
    }

    public function ofType(FileTypeEnum $type): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => $type,
        ]);
    }

    public function ofFileable(Model $model): static
    {
        return $this->state(fn (array $attributes) => [
            'fileable_type' => $model::class,
            'fileable_id' => (int) $model->getAttribute('id'),
        ]);
    }
}
