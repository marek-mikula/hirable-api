<?php

declare(strict_types=1);

namespace Domain\Position\Services;

use App\Services\Service;
use Domain\Company\Enums\RoleEnum;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\ProcessStep\Enums\StepEnum;
use Illuminate\Support\Collection;

class PositionConfigService extends Service
{
    public function getApprovalRemindDays(): int
    {
        return (int) config('position.approval.remind_days');
    }

    /**
     * @return RoleEnum[]
     */
    public function getRolesForPositionRole(PositionRoleEnum $role): array
    {
        $roles = config(sprintf('position.roles.%s', $role->value), []);

        return array_map(fn (mixed $value) => RoleEnum::from((string) $value), $roles);
    }

    /**
     * @return Collection<StepEnum>
     */
    public function getDefaultConfigurableProcessSteps(): Collection
    {
        return collect((array) config('position.default_configurable_process_steps'))
            ->map(fn (string $step) => StepEnum::from($step));
    }

    public function getMaxTags(): int
    {
        return (int) config('position.max_tags');
    }
}
