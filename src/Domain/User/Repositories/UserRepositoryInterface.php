<?php

declare(strict_types=1);

namespace Domain\User\Repositories;

use Carbon\Carbon;
use Domain\Company\Models\Company;
use Domain\User\Models\User;
use Domain\User\Repositories\Input\UserStoreInput;
use Domain\User\Repositories\Input\UserUpdateInput;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function store(UserStoreInput $input): User;

    public function update(User $user, UserUpdateInput $input): User;

    public function verifyEmail(User $user, ?Carbon $timestamp): User;

    public function changePassword(User $user, string $password): User;

    public function findByEmail(string $email): ?User;

    /**
     * @param int[] $ids
     * @return Collection<User>
     */
    public function getByIdsAndCompany(Company $company, array $ids): Collection;
}
