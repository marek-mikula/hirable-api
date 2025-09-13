<?php

declare(strict_types=1);

namespace Support\Grid\UseCases;

use App\UseCases\UseCase;
use Domain\User\Models\User;
use Support\Grid\Enums\GridEnum;
use Support\Setting\Mappers\GridSettingMapper;
use Support\Setting\Repositories\SettingRepositoryInterface;

class ResetGridSettingUseCase extends UseCase
{
    public function __construct(
        private readonly SettingRepositoryInterface $settingRepository,
        private readonly GridSettingMapper $mapper,
    ) {
    }

    public function handle(User $user, GridEnum $grid): void
    {
        $setting = $this->settingRepository->find($user, $grid->getSettingKey());

        if ($setting === null) {
            return;
        }

        $mappedSetting = $this->mapper->fromArray($setting->data);

        $mappedSetting->perPage = null;
        $mappedSetting->stickyHeader = null;
        $mappedSetting->stickyFooter = null;
        $mappedSetting->columns = [];
        $mappedSetting->order = [];

        $setting->data = $this->mapper->toArray($mappedSetting);

        // there was no data to save, used had no query
        // string saved, so we can safely delete the row
        if (!$setting->hasData()) {
            $this->settingRepository->delete($setting);

            return;
        }

        $this->settingRepository->save($setting);
    }
}
