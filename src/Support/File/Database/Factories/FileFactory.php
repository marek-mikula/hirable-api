<?php

declare(strict_types=1);

namespace Support\File\Database\Factories;

use Database\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Testing\File as FakeFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Support\File\Enums\FileTypeEnum;
use Support\File\Models\File;
use Support\File\Models\ModelHasFile;

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

    public function withFileable(Model $fileable): static
    {
        return $this->afterCreating(function (File $file) use ($fileable): void {
            ModelHasFile::factory()
                ->ofFileable($fileable)
                ->ofFile($file)
                ->create();
        });
    }
}
