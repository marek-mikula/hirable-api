<?php

namespace Tests\Unit\App\Actions;

use App\Actions\GetModelSubFoldersAction;
use Tests\Common\Models\TestModel;

use function PHPUnit\Framework\assertSame;

/** @covers \App\Actions\GetModelSubFoldersAction::handle */
it('correctly build folder structure', function (): void {
    $action = GetModelSubFoldersAction::make();

    $model1 = new TestModel();
    $model1->setAttribute('id', 1);

    $model2 = new TestModel();
    $model2->setAttribute('id', 500);

    $model3 = new TestModel();
    $model3->setAttribute('id', 501);

    assertSame(['1-500', '1', 'documents'], $action->handle($model1, folders: ['documents']));
    assertSame(['1-500', '500', 'documents'], $action->handle($model2, folders: ['documents']));
    assertSame(['501-1000', '501', 'documents'], $action->handle($model3, folders: ['documents']));
});
