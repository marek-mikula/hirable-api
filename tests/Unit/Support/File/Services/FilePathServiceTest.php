<?php

declare(strict_types=1);

namespace Tests\Unit\Support\File\Services;

use Support\File\Services\FilePathService;
use Tests\Common\Models\TestModel;

use function PHPUnit\Framework\assertSame;

/** @covers \Support\File\Services\FilePathService::getPathForModel */
it('correctly build folder structure', function (): void {
    config()->set(sprintf('file.model_folders.%s', TestModel::class), 'testModels');

    /** @var FilePathService $service */
    $service = app(FilePathService::class);

    $model1 = new TestModel();
    $model1->setAttribute('id', 1);

    $model2 = new TestModel();
    $model2->setAttribute('id', 500);

    $model3 = new TestModel();
    $model3->setAttribute('id', 501);

    assertSame('testModels/1-500/1/documents', $service->getPathForModel($model1, folders: ['documents']));
    assertSame('testModels/1-500/500/documents', $service->getPathForModel($model2, folders: ['documents']));
    assertSame('testModels/501-1000/501/documents', $service->getPathForModel($model3, folders: ['documents']));
});
