<?php

namespace Support\Grid\UseCases;

use App\Models\User;
use App\Repositories\Setting\SettingRepositoryInterface;
use App\UseCases\UseCase;
use Support\Grid\Contracts\Grid;
use Support\Grid\Data\Settings\GridSetting;
use Support\Grid\Enums\GridEnum;
use Support\Grid\Http\Requests\Data\GridSettingData;
use Support\Setting\Mappers\GridSettingMapper;

class UpdateGridSettingsUseCase extends UseCase
{
    public function __construct(
        private readonly SettingRepositoryInterface $settingRepository,
        private readonly GridSettingMapper $mapper,
    ) {}

    public function handle(User $user, GridEnum $grid, GridSettingData $data): ?GridSetting
    {
        /** @var Grid $instance */
        $instance = app($grid->getClass());

        $definition = $instance->getDefinition($user);

        // grid does not allow user
        // to change the settings
        if (! $definition->allowSettings) {
            return null;
        }

        $setting = $this->settingRepository->findOrNew($user, $grid->getSettingKey());

        $mappedSetting = $this->mapper->fromArray($setting->data);

        $mappedSetting->perPage = $data->perPage;
        $mappedSetting->stickyHeader = $data->stickyHeader;
        $mappedSetting->stickyFooter = $data->stickyFooter;

        $order = [];

        foreach ($data->columns as $column) {
            $columnDefinition = $definition->getColumn($column->key);

            // filter columns from user, so we
            // don't save any invalid columns
            if (! $columnDefinition) {
                continue;
            }

            $order[] = $column->key;

            // this column cannot be toggled
            if (! $columnDefinition->allowToggle) {
                continue;
            }

            $mappedSetting->setColumnState($column->key, $column->enabled);
        }

        $mappedSetting->order = $order;

        $setting->data = $this->mapper->toArray($mappedSetting);

        $this->settingRepository->save($setting);

        return $mappedSetting;
    }
}
