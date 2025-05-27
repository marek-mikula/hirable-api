<?php

declare(strict_types=1);

namespace Domain\Position\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Position\Http\Request\PositionIndexRequest;
use Domain\Position\Http\Request\PositionShowRequest;
use Domain\Position\Http\Request\PositionStoreRequest;
use Domain\Position\Http\Resources\Collections\PositionPaginatedCollection;
use Domain\Position\Http\Resources\PositionResource;
use Domain\Position\Models\Position;
use Domain\Position\UseCases\GetPositionsForIndexUseCase;
use Domain\Position\UseCases\StorePositionUseCase;
use Illuminate\Http\JsonResponse;
use Support\Grid\Actions\SaveGridRequestQueryAction;
use Support\Grid\Enums\GridEnum;

use function Illuminate\Support\defer;

class PositionController extends ApiController
{
    public function index(PositionIndexRequest $request): JsonResponse
    {
        $user = $request->user();

        $gridQuery = $request->getGridQuery();

        $positions = GetPositionsForIndexUseCase::make()->handle($request->user(), $request->getGridQuery());

        defer(fn () => SaveGridRequestQueryAction::make()->handle($user, GridEnum::POSITION, $gridQuery));

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'positions' => new PositionPaginatedCollection($positions),
        ]);
    }

    public function store(PositionStoreRequest $request): JsonResponse
    {
        $position = StorePositionUseCase::make()->handle($request->user(), $request->toData());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'position' => new PositionResource($position),
        ]);
    }

    public function show(PositionShowRequest $request, Position $position): JsonResponse
    {
        $position->loadMissing('files');

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'position' => new PositionResource($position),
        ]);
    }
}
