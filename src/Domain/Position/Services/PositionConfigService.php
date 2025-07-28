<?php

declare(strict_types=1);

namespace Domain\Position\Services;

use App\Services\Service;
use Domain\Company\Enums\RoleEnum;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\ProcessStep\Enums\ProcessStepEnum;
use Illuminate\Support\Collection;

class PositionConfigService extends Service
{
    public function getAllowedFileExtensions(): array
    {
        return (array) config('position.files.extensions', []);
    }

    public function getMaxFileSize(): string
    {
        return (string) config('position.files.max_size');
    }

    public function getMaxFiles(): int
    {
        return (int) config('position.files.max_files');
    }

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
     * @return Collection<ProcessStepEnum>
     */
    public function getDefaultConfigurableProcessSteps(): Collection
    {
        return collect((array) config('position.default_configurable_process_steps'))
            ->map(fn (string $step) => ProcessStepEnum::from($step));
    }
}
