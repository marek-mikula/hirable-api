<?php

declare(strict_types=1);

namespace Tests\Feature\File\Services;

use Illuminate\Http\Testing\File as TestingFile;
use Illuminate\Support\Facades\Storage;
use Support\File\Data\FileData;
use Support\File\Enums\FileDiskEnum;
use Support\File\Enums\FileTypeEnum;
use Support\File\Models\File;
use Support\File\Services\FileSaver;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseEmpty;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertStringStartsWith;

/** @covers \Support\File\Services\FileSaver::saveFile */
it('correctly saves file', function (): void {
    $type = FileTypeEnum::CANDIDATE_CV;
    $disk = FileDiskEnum::LOCAL;

    /** @var FileSaver $saver */
    $saver = app(FileSaver::class);

    assertDatabaseEmpty(File::class);

    $fileData = FileData::make(TestingFile::fake()->create(
        name: 'cv.pdf',
        kilobytes: 1,
        mimeType: 'application/pdf'
    ));

    $file = $saver->saveFile(
        file: $fileData,
        path: 'candidates',
        type: $type,
        disk: $disk,
    );

    assertDatabaseCount(File::class, 1);

    Storage::disk($disk->value)->assertExists($file->path);
    assertSame($type, $file->type);
    assertSame($disk, $file->disk);
    assertStringStartsWith('candidates', $file->path);
    assertSame($fileData->getMime(), $file->mime);
    assertSame($fileData->getSize(), $file->size);
});

/** @covers \Support\File\Services\FileSaver::saveFile */
it('correctly saves file to a default disk', function (): void {
    $disk = FileDiskEnum::S3;

    // set default disk
    config()->set('filesystems.default', $disk->value);

    /** @var FileSaver $saver */
    $saver = app(FileSaver::class);

    $fileData = FileData::make(TestingFile::fake()->create(
        name: 'cv.pdf',
        kilobytes: 1,
        mimeType: 'application/pdf'
    ));

    $file = $saver->saveFile(
        file: $fileData,
        path: 'candidates',
        type: FileTypeEnum::CANDIDATE_CV,
    );


    Storage::disk($disk->value)->assertExists($file->path);
    assertSame($disk, $file->disk);
});
