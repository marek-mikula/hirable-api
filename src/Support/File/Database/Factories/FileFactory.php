<?php

declare(strict_types=1);

namespace Support\File\Database\Factories;

use Database\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;
use Illuminate\Http\Testing\File as FakeFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Support\File\Enums\FileTypeEnum;
use Support\File\Models\File;

/**
 * @extends BaseFactory<File>
 */
class FileFactory extends Factory
{
    protected $model = File::class;

    public function definition(): array
    {
        $type = FileTypeEnum::TEMP;

        $filename = sprintf('%s.jpg', Str::uuid()->toString());

        $file = FakeFile::fake()->image($filename);

        $path = Storage::disk($type->getDomain()->getDisk())->putFile('/', $file);

        return [
            'type' => FileTypeEnum::TEMP,
            'name' => $filename,
            'mime' => 'image/jpeg',
            'path' => $path,
            'extension' => 'jpg',
            'size' => 300,
            'data' => [],
        ];
    }

    public function ofType(FileTypeEnum $type): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => $type,
        ]);
    }
}
