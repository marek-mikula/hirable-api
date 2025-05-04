<?php

declare(strict_types=1);

namespace Tests\Unit\Support\File\Repositories;

use Domain\User\Models\User;
use Illuminate\Support\Str;
use Support\File\Enums\FileTypeEnum;
use Support\File\Models\File;
use Support\File\Repositories\FileRepositoryInterface;
use Support\File\Repositories\Input\FileStoreInput;

use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\assertModelMissing;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

/** @covers \Support\File\Repositories\FileRepository::save */
it('tests save method', function (): void {
    /** @var FileRepositoryInterface $repository */
    $repository = app(FileRepositoryInterface::class);

    $file = File::factory()->create();
    $file->setDataValue('processed', -1);

    $file = $repository->save($file);

    assertTrue($file->wasChanged('data'));
});

/** @covers \Support\File\Repositories\FileRepository::store */
it('tests store method', function (): void {
    /** @var FileRepositoryInterface $repository */
    $repository = app(FileRepositoryInterface::class);

    $user = User::factory()->create();

    $input = new FileStoreInput(
        fileable: $user,
        type: FileTypeEnum::TEMP,
        path: Str::uuid()->toString().'.jpg',
        extension: 'jpg',
        name: 'thumbnail.jpg',
        mime: 'image/jpeg',
        size: 340,
        data: ['key' => fake()->word],
    );

    $file = $repository->store($input);

    assertModelExists($file);
    assertSame($user->id, $file->fileable_id);
    assertSame($user::class, $file->fileable_type);
    assertSame($input->type, $file->type);
    assertSame($input->path, $file->path);
    assertSame($input->extension, $file->extension);
    assertSame($input->name, $file->name);
    assertSame($input->mime, $file->mime);
    assertSame($input->size, $file->size);
    assertTrue($file->hasDataValue('key'));
    assertSame($input->data['key'], $file->getDataValue('key'));
    assertTrue($file->relationLoaded('fileable'));
});

/** @covers \Support\File\Repositories\FileRepository::delete */
it('tests store delete method', function (): void {
    /** @var FileRepositoryInterface $repository */
    $repository = app(FileRepositoryInterface::class);

    $file = File::factory()->create();

    assertNull($file->deleted_at);

    $file = $repository->delete($file);

    assertModelExists($file);
    assertNotNull($file->deleted_at);

    $file = $repository->delete($file, force: true);

    assertModelMissing($file);
});

/** @covers \Support\File\Repositories\FileRepository::deleteMany */
it('tests store deleteMany method', function (): void {
    /** @var FileRepositoryInterface $repository */
    $repository = app(FileRepositoryInterface::class);

    $file1 = File::factory()->create();
    $file2 = File::factory()->create();

    assertNull($file1->deleted_at);
    assertNull($file2->deleted_at);

    $repository->deleteMany([
        $file1,
        $file2,
    ]);

    $file1->refresh();
    $file2->refresh();

    assertModelExists($file1);
    assertNotNull($file1->deleted_at);

    assertModelExists($file2);
    assertNotNull($file2->deleted_at);

    $repository->deleteMany([
        $file1,
        $file2,
    ], force: true);

    assertModelMissing($file1);
    assertModelMissing($file2);
});
