<?php

declare(strict_types=1);

namespace Support\Grid\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Support\Grid\Enums\GridEnum;
use Support\Grid\Http\Requests\GridShowRequest;
use Support\Grid\Http\Resources\GridQueryResource;
use Support\Grid\Http\Resources\GridResource;
use Support\Grid\UseCases\GetGridDefinitionUseCase;
use Support\Grid\UseCases\GetGridQueryUseCase;
use Support\Grid\UseCases\GetGridSettingUseCase;

class GridController extends ApiController
{
    public function show(GridShowRequest $request, GridEnum $grid): JsonResponse
    {
        $user = $request->user();

        $setting = GetGridSettingUseCase::make()->handle($user, $grid);

        $definition = GetGridDefinitionUseCase::make()->handle($user, $grid, $setting);

        $query = GetGridQueryUseCase::make()->handle($user, $definition, $setting);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'grid' => new GridResource($definition),
            'query' => new GridQueryResource($query),
        ]);
    }
}
