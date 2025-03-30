<?php

namespace Tests\Unit\App\Repositories;

use App\Models\File;
use App\Models\User;
use App\Repositories\File\FileRepositoryInterface;
use App\Repositories\File\Input\StoreInput;
use Illuminate\Support\Str;
use Support\File\Enums\FileTypeEnum;

use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\assertModelMissing;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

/** @covers \App\Repositories\File\FileRepository::save */
it('tests save method', function (): void {
    /** @var FileRepositoryInterface $repository */
    $repository = app(FileRepositoryInterface::class);

    $file = File::factory()->create();
    $file->setDataValue('processed', -1);

    $file = $repository->save($file);

    assertTrue($file->wasChanged('data'));
});

/** @covers \App\Repositories\File\FileRepository::store */
it('tests store method', function (): void {
    /** @var FileRepositoryInterface $repository */
    $repository = app(FileRepositoryInterface::class);

    $user = User::factory()->create();

    $input = StoreInput::from([
        'fileable' => $user,
        'type' => FileTypeEnum::TEMP,
        'path' => Str::uuid()->toString().'.jpg',
        'extension' => 'jpg',
        'name' => 'thumbnail.jpg',
        'mime' => 'image/jpeg',
        'size' => 340,
        'data' => [
            'key' => 'value',
        ],
    ]);

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
    assertTrue($file->relationLoaded('fileable'));
    assertTrue($file->hasDataValue('key'));
    assertSame('value', $file->getDataValue('key'));
});

/** @covers \App\Repositories\File\FileRepository::delete */
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

/** @covers \App\Repositories\File\FileRepository::deleteMany */
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
