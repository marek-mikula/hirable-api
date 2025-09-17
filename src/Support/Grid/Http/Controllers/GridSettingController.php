<?php

declare(strict_types=1);

namespace Support\Grid\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Support\Grid\Enums\GridEnum;
use Support\Grid\Http\Requests\GridSettingResetRequest;
use Support\Grid\Http\Requests\GridSettingSetColumnWidthRequest;
use Support\Grid\Http\Requests\GridSettingUpdateRequest;
use Support\Grid\Http\Resources\GridResource;
use Support\Grid\UseCases\GetGridDefinitionUseCase;
use Support\Grid\UseCases\ResetGridSettingUseCase;
use Support\Grid\UseCases\SetColumnWidthUseCase;
use Support\Grid\UseCases\UpdateGridSettingsUseCase;

final class GridSettingController extends ApiController
{
    public function update(GridSettingUpdateRequest $request, GridEnum $grid): JsonResponse
    {
        $setting = UpdateGridSettingsUseCase::make()->handle($request->user(), $grid, $request->toData());

        $definition = GetGridDefinitionUseCase::make()->handle($request->user(), $grid, $setting);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'grid' => new GridResource($definition),
        ]);
    }

    public function setColumnWidth(GridSettingSetColumnWidthRequest $request, GridEnum $grid): JsonResponse
    {
        SetColumnWidthUseCase::make()->handle($request->user(), $grid, $request->toData());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS);
    }

    public function reset(GridSettingResetRequest $request, GridEnum $grid): JsonResponse
    {
        ResetGridSettingUseCase::make()->handle($request->user(), $grid);

        $definition = GetGridDefinitionUseCase::make()->handle($request->user(), $grid, null);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'grid' => new GridResource($definition),
        ]);
    }
}
