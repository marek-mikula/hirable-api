<?php

declare(strict_types=1);

namespace Support\Grid\UseCases;

use App\UseCases\UseCase;
use Domain\User\Models\User;
use Support\Grid\Data\Definition\GridColumnDefinition;
use Support\Grid\Enums\GridEnum;
use Support\Grid\Grids\Grid;
use Support\Grid\Http\Requests\Data\GridColumnWidthData;
use Support\Setting\Mappers\GridSettingMapper;
use Support\Setting\Repositories\SettingRepositoryInterface;

class SetColumnWidthUseCase extends UseCase
{
    public function __construct(
        private readonly SettingRepositoryInterface $settingRepository,
        private readonly GridSettingMapper $mapper,
    ) {
    }

    public function handle(User $user, GridEnum $grid, GridColumnWidthData $data): void
    {
        /** @var Grid $instance */
        $instance = app($grid->getClass());

        $definition = $instance->getDefinition($user);

        // grid does not allow user
        // to change the settings
        if (!$definition->allowSettings) {
            return;
        }

        $column = $definition->getColumn($data->key);

        if (!$column) {
            throw new \Exception(sprintf('Undefined grid column %s.', $data->key));
        }

        $setting = $this->settingRepository->findOrNew($user, $grid->getSettingKey());

        $mappedSetting = $this->mapper->fromArray($setting->data);

        $mappedSetting->setColumnWidth($data->key, $this->validateWidth($column, $data->width));

        $setting->data = $this->mapper->toArray($mappedSetting);

        $this->settingRepository->save($setting);
    }

    private function validateWidth(GridColumnDefinition $column, int $width): int
    {
        if ($column->minWidth !== null && $width < $column->minWidth) {
            return $column->minWidth;
        }

        if ($column->maxWidth !== null && $width > $column->maxWidth) {
            return $column->maxWidth;
        }

        return $width;
    }
}
