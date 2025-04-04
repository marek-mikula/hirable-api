<?php

use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Support\ActivityLog\Facades\ActivityLog;
use Support\File\Enums\FileDomainEnum;
use Tests\TestCase;

use function Pest\Laravel\withHeader;

$folderGroups = [
    // Global groups
    [['feature'], ['Feature']],
    [['process'], ['Process']],
    [['unit'], ['Unit']],

    // Domain groups
    [['domain', 'domain-auth'], ['Feature/Domain/Auth', 'Unit/Domain/Auth']],
    [['domain', 'domain-candidate'], ['Feature/Domain/Candidate', 'Unit/Domain/Candidate']],
    [['domain', 'domain-company'], ['Feature/Domain/Company', 'Unit/Domain/Company']],
    [['domain', 'domain-password'], ['Feature/Domain/Password', 'Unit/Domain/Password']],
    [['domain', 'domain-register'], ['Feature/Domain/Register', 'Unit/Domain/Register']],
    [['domain', 'domain-search'], ['Feature/Domain/Search', 'Unit/Domain/Search']],
    [['domain', 'domain-verification'], ['Feature/Domain/Verification', 'Unit/Domain/Verification']],

    // Support groups
    [['support', 'support-activity-log'], ['Feature/Support/ActivityLog', 'Unit/Support/ActivityLog']],
    [['support', 'support-file'], ['Feature/Support/File', 'Unit/Support/File']],
    [['support', 'support-grid'], ['Feature/Support/Grid', 'Unit/Support/Grid']],
    [['support', 'support-setting'], ['Feature/Support/Setting', 'Unit/Support/Setting']],
    [['support', 'support-token'], ['Feature/Support/Token', 'Unit/Support/Token']],

    // Common groups
    [['app'], ['Feature/App', 'Unit/App']],
];

foreach ($folderGroups as [$groups, $folders]) {
    uses()->group(...$groups)->in(...$folders);
}

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

        // fake local storage disk so no files
        // are created directly on the disk
        Storage::fake('local');

        // fake other storage disks based on
        // file domain enum
        foreach (FileDomainEnum::cases() as $fileDomain) {
            Storage::fake($fileDomain->getDisk());
        }

        // disable activity logging, if it needs to be tested
        // it should be explicitly turned on
        ActivityLog::disable();

        // set Referer header, so EnsureFrontendRequestsAreStateful
        // starts the session
        withHeader('Referer', (string) config('app.frontend_url'));
    })
    ->in('Feature', 'Process', 'Unit');

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
require_once __DIR__.'/Common/Helpers/CollectionHelpers.php';
require_once __DIR__.'/Common/Helpers/DatetimeHelpers.php';
require_once __DIR__.'/Common/Helpers/ExceptionHelpers.php';
require_once __DIR__.'/Common/Helpers/ResponseHelpers.php';
require_once __DIR__.'/Common/Helpers/RequestHelpers.php';
require_once __DIR__.'/Common/Helpers/TestHelpers.php';
