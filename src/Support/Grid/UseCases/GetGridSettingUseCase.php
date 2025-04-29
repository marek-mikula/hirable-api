<?php

declare(strict_types=1);

namespace Support\Grid\UseCases;

use App\Models\User;
use App\Repositories\Setting\SettingRepositoryInterface;
use App\UseCases\UseCase;
use Support\Grid\Data\Settings\GridSetting;
use Support\Grid\Enums\GridEnum;
use Support\Setting\Mappers\GridSettingMapper;

class GetGridSettingUseCase extends UseCase
{
    public function __construct(
        private readonly SettingRepositoryInterface $settingRepository,
        private readonly GridSettingMapper $mapper,
    ) {
    }

    public function handle(User $user, GridEnum $grid): ?GridSetting
    {
        $setting = $this->settingRepository->find($user, $grid->getSettingKey());

        if (!$setting) {
            return null;
        }

        return $this->mapper->fromArray($setting->data);
    }
}
