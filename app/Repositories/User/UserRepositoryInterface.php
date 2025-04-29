<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\User\Input\StoreInput;
use App\Repositories\User\Input\UpdateInput;
use Carbon\Carbon;

interface UserRepositoryInterface
{
    public function store(StoreInput $input): User;

    public function update(User $user, UpdateInput $input): User;

    public function verifyEmail(User $user, ?Carbon $timestamp = null): User;

    public function changePassword(User $user, string $password): User;

    public function findByEmail(string $email): ?User;
}
