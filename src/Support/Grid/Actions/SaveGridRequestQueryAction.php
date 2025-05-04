<?php

declare(strict_types=1);

namespace Support\Grid\Actions;

use App\Models\User;
use Lorisleiva\Actions\Action;
use Support\Grid\Data\Query\GridQuery;
use Support\Grid\Data\Query\GridRequestQuery;
use Support\Grid\Enums\GridEnum;
use Support\Grid\UseCases\GetGridDefinitionUseCase;
use Support\Grid\UseCases\GetGridQueryUseCase;
use Support\Setting\Mappers\GridSettingMapper;
use Support\Setting\Repositories\SettingRepositoryInterface;

class SaveGridRequestQueryAction extends Action
{
    public function __construct(
        private readonly SettingRepositoryInterface $settingRepository,
        private readonly GridSettingMapper $mapper,
    ) {
    }

    public function handle(User $user, GridEnum $grid, GridRequestQuery $requestQuery): void
    {
        $setting = $this->settingRepository->findOrNew($user, $grid->getSettingKey());

        $mappedSetting = $this->mapper->fromArray($setting->data);

        $definition = GetGridDefinitionUseCase::make()->handle($user, $grid, $mappedSetting);

        $query = GetGridQueryUseCase::make()->handle($user, $definition, $mappedSetting);

        if (!$this->hasQueryChanged($requestQuery, $query)) {
            return;
        }

        $mappedSetting->searchQuery = $requestQuery->searchQuery;

        $sort = [];

        foreach ($requestQuery->sort as $key => $order) {
            $columnDefinition = $definition->getColumn($key);

            // column was probably removed from the definition => skip
            if (!$columnDefinition) {
                continue;
            }

            // column does not allow sort => skip
            if (!$columnDefinition->allowSort) {
                continue;
            }

            // column is disabled => skip
            if (!$columnDefinition->enabled) {
                continue;
            }

            $sort[$key] = $order;
        }

        $mappedSetting->sort = $sort;

        $setting->data = $this->mapper->toArray($mappedSetting);

        $this->settingRepository->save($setting);
    }

    private function hasQueryChanged(GridRequestQuery $requestQuery, GridQuery $query): bool
    {
        if ($requestQuery->searchQuery !== $query->searchQuery) {
            return true;
        }

        foreach ($requestQuery->sort as $key => $order) {
            if (!array_key_exists($key, $query->sort) || $requestQuery->sort[$key] !== $query->sort[$key]) {
                return true;
            }
        }

        foreach ($query->sort as $key => $order) {
            if (!array_key_exists($key, $requestQuery->sort) || $query->sort[$key] !== $requestQuery->sort[$key]) {
                return true;
            }
        }

        return false;
    }
}
