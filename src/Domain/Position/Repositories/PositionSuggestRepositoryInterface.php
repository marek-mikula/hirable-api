<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use Domain\User\Models\User;

interface PositionSuggestRepositoryInterface
{
    /**
     * @return string[]
     */
    public function suggestDepartments(User $user, ?string $value): array;

    /**
     * @return string[]
     */
    public function suggestTechnologies(User $user, ?string $value): array;

    /**
     * @return string[]
     */
    public function suggestCertificates(User $user, ?string $value): array;
}
