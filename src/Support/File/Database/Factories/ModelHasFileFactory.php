<?php

declare(strict_types=1);

namespace Support\File\Database\Factories;

use Database\Factories\Factory;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;
use Illuminate\Database\Eloquent\Model;
use Support\File\Models\File;
use Support\File\Models\ModelHasFile;

/**
 * @extends BaseFactory<ModelHasFile>
 */
class ModelHasFileFactory extends Factory
{
    protected $model = ModelHasFile::class;

    public function definition(): array
    {
        return [
            'file_id' => $this->isMaking ? null : File::factory(),
            'fileable_id' => User::factory(),
            'fileable_type' => User::class,
        ];
    }

    public function ofFile(File $file): static
    {
        return $this->state(fn (array $attributes) => [
            'file_id' => $file->id,
        ]);
    }

    public function ofFileable(Model $fileable): static
    {
        return $this->state(fn (array $attributes) => [
            'fileable_id' => (int) $fileable->getKey(),
            'fileable_type' => $fileable::class,
        ]);
    }
}
