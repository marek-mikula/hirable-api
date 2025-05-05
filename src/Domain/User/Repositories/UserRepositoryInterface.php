<?php

declare(strict_types=1);

namespace Domain\User\Repositories;

use Carbon\Carbon;
use Domain\User\Models\User;
use Domain\User\Repositories\Input\UserStoreInput;
use Domain\User\Repositories\Input\UserUpdateInput;

interface UserRepositoryInterface
{
    public function store(UserStoreInput $input): User;

    public function update(User $user, UserUpdateInput $input): User;

    public function verifyEmail(User $user, ?Carbon $timestamp = null): User;

    public function changePassword(User $user, string $password): User;

    public function findByEmail(string $email): ?User;
}
