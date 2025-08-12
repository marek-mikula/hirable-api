<?php

declare(strict_types=1);

namespace Tests\Unit\Support\File\Jobs;

use Domain\User\Models\User;
use Support\File\Jobs\DeleteHangingFilesJob;
use Support\File\Models\File;

use function Pest\Laravel\assertNotSoftDeleted;
use function Pest\Laravel\assertSoftDeleted;

/** @covers \Support\File\Jobs\DeleteHangingFilesJob::handle */
it('correctly deletes hanging files', function (): void {
    $user = User::factory()->create();

    $file1 = File::factory()->create();
    $file2 = File::factory()->withFileable($user)->create();

    app()->call([new DeleteHangingFilesJob(), 'handle']);

    assertSoftDeleted(File::class, ['id' => $file1->id]);
    assertNotSoftDeleted(File::class, ['id' => $file2->id]);
});
