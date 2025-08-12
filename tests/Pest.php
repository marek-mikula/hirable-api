<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Support\ActivityLog\Facades\ActivityLog;
use Support\File\Enums\FileDiskEnum;
use Tests\TestCase;

use function Pest\Laravel\withHeader;
use function Pest\Laravel\withoutDefer;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(TestCase::class)
    ->beforeEach(function (): void {
        // do not send any notification
        Notification::fake();

        // fake all storage disks
        foreach (FileDiskEnum::cases() as $disk) {
            Storage::fake($disk->value);
        }

        // disable activity logging, if it needs to be tested
        // it should be explicitly turned on
        ActivityLog::disable();

        // set Referer header, so EnsureFrontendRequestsAreStateful
        // starts the session
        withHeader('Referer', (string) config('app.frontend_url'));

        // invoke all deferred functions immediatelly
        withoutDefer();
    })
    ->in('Feature', 'Process', 'Unit');

uses()
    ->beforeEach(function (): void {
        // fake all events, because unit tests
        // should check only if the event was
        // triggered
        Event::fakeExcept([]);
    })
    ->in('Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

require_once __DIR__.'/Common/Helpers/AuthHelpers.php';
require_once __DIR__.'/Common/Helpers/ArrayHelpers.php';
require_once __DIR__.'/Common/Helpers/CollectionHelpers.php';
require_once __DIR__.'/Common/Helpers/DatetimeHelpers.php';
require_once __DIR__.'/Common/Helpers/ExceptionHelpers.php';
require_once __DIR__.'/Common/Helpers/ResponseHelpers.php';
require_once __DIR__.'/Common/Helpers/RequestHelpers.php';
require_once __DIR__.'/Common/Helpers/TestHelpers.php';
require_once __DIR__.'/Common/Helpers/FakeHelpers.php';
