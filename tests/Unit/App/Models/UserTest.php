<?php

namespace Tests\Unit\App\Models;

use App\Models\User;

use function PHPUnit\Framework\assertSame;

/** @covers \App\Models\User::fullName */
it('correctly handles fullName attribute', function (): void {
    $user = new User();
    $user->firstname = 'Thomas';
    $user->lastname = 'Example';
    $user->prefix = 'Ing.';
    $user->postfix = 'MBA';

    assertSame('Ing. Thomas Example, MBA', $user->full_name);
});

/** @covers \App\Models\User::isEmailVerified */
it('correctly handles isEmailVerified attribute', function (): void {
    $user = new User();
    $user->email_verified_at = null;

    assertSame(false, $user->is_email_verified);

    $user->email_verified_at = now();

    assertSame(true, $user->is_email_verified);
});
