<?php

namespace Tests\Feature\File\Services;

use App\Models\File;
use App\Models\User;
use App\Repositories\File\FileRepositoryInterface;
use Illuminate\Http\Testing\File as TestingFile;
use Illuminate\Support\Facades\Storage;
use Support\File\Data\FileData;
use Support\File\Enums\FileTypeEnum;
use Support\File\Exceptions\UnableToSaveFileException;
use Support\File\Services\FileSaver;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseEmpty;
use function Pest\Laravel\mock;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertStringStartsWith;
use function PHPUnit\Framework\assertTrue;
use function Tests\Common\Helpers\assertException;

/** @covers \Support\File\Services\FileSaver::saveFiles */
it('correctly saves collection of files', function (): void {
    $user = User::factory()->create();

    $type = FileTypeEnum::TEMP;

    $storage = Storage::disk($type->getDomain()->getDisk());

    /** @var FileSaver $saver */
    $saver = app(FileSaver::class);

    assertDatabaseEmpty(File::class);

    $files = $saver->saveFiles(
        fileable: $user,
        type: $type,
        files: [
            FileData::make(TestingFile::fake()->image('test1.jpg')->size(1), ['key0' => 'value0']),
            FileData::make(TestingFile::fake()->image('test2.png')->size(2), ['key1' => 'value1']),
            FileData::make(TestingFile::fake()->image('test3.gif')->size(3), ['key2' => 'value2']),
        ],
        folders: [
            'images',
            'thumbnails',
        ],
    );

    assertDatabaseCount(File::class, 3);

    $mimes = [
        'test1.jpg' => 'image/jpeg',
        'test2.png' => 'image/png',
        'test3.gif' => 'image/gif',
    ];

    $sizes = [
        'test1.jpg' => 1024,
        'test2.png' => 2 * 1024,
        'test3.gif' => 3 * 1024,
    ];

    foreach ($files as $i => $file) {
        // assert that file exists on a disk
        $storage->assertExists($file->path);

        // assert correct path folders
        assertStringStartsWith('images/thumbnails', $file->path);

        // assert correct mime type
        assertSame($mimes[$file->name], $file->mime);

        // assert correct size
        assertSame($sizes[$file->name], $file->size);

        // assert meta data
        assertTrue($file->hasDataValue("key{$i}"));
        assertSame("value{$i}", $file->getDataValue("key{$i}"));
    }
});

/** @covers \Support\File\Services\FileSaver::saveFiles */
it('correctly removes files and folders when saving fails', function (): void {
    $user = User::factory()->create();

    $type = FileTypeEnum::TEMP;

    $storage = Storage::disk($type->getDomain()->getDisk());

    mock(FileRepositoryInterface::class)
        ->shouldReceive('store')
        ->andThrow(new \Exception());

    /** @var FileSaver $saver */
    $saver = app(FileSaver::class);

    assertException(function () use (
        $user,
        $type,
        $saver,
    ): void {
        $saver->saveFiles(
            fileable: $user,
            type: $type,
            files: [
                FileData::make(TestingFile::fake()->image('test.jpg')),
            ],
            folders: [
                'images',
                'users',
            ]
        );
    }, function (\Exception $e): void {
        assertInstanceOf(UnableToSaveFileException::class, $e);
    });

    assertDatabaseEmpty(File::class);

    // assert that folders were removed when exception was thrown
    $storage->assertMissing('/images/users');
    $storage->assertMissing('/images');
    $storage->assertDirectoryEmpty('/');
});
