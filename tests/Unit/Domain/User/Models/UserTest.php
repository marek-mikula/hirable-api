<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\User\Models;

use Domain\User\Models\User;

use function PHPUnit\Framework\assertSame;

/** @covers \Domain\User\Models\User::fullName */
it('correctly handles fullName attribute', function (): void {
    $user = new User();
    $user->firstname = 'Thomas';
    $user->lastname = 'Example';

    assertSame('Thomas Example', $user->full_name);
});

/** @covers \Domain\User\Models\User::fullQualifiedName */
it('correctly handles fullQualifiedName attribute', function (): void {
    $user = new User();
    $user->firstname = 'Thomas';
    $user->lastname = 'Example';
    $user->prefix = 'Ing.';
    $user->postfix = 'MBA';

    assertSame('Ing. Thomas Example, MBA', $user->full_qualified_name);
});

/** @covers \Domain\User\Models\User::isEmailVerified */
it('correctly handles isEmailVerified attribute', function (): void {
    $user = new User();
    $user->email_verified_at = null;

    assertSame(false, $user->is_email_verified);

    $user->email_verified_at = now();

    assertSame(true, $user->is_email_verified);
});
