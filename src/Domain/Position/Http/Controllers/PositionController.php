<?php

declare(strict_types=1);

namespace Domain\Position\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Position\Http\Request\PositionDeleteRequest;
use Domain\Position\Http\Request\PositionIndexRequest;
use Domain\Position\Http\Request\PositionShowRequest;
use Domain\Position\Http\Request\PositionStoreRequest;
use Domain\Position\Http\Request\PositionUpdateRequest;
use Domain\Position\Http\Resources\Collections\PositionListPaginatedCollection;
use Domain\Position\Http\Resources\PositionResource;
use Domain\Position\Models\Position;
use Domain\Position\UseCases\PositionDeleteUseCase;
use Domain\Position\UseCases\PositionIndexUseCase;
use Domain\Position\UseCases\PositionStoreUseCase;
use Domain\Position\UseCases\PositionUpdateUseCase;
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

        $positions = PositionIndexUseCase::make()->handle($request->user(), $request->getGridQuery());

        defer(fn () => SaveGridRequestQueryAction::make()->handle($user, GridEnum::POSITION, $gridQuery));

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'positions' => new PositionListPaginatedCollection($positions),
        ]);
    }

    public function store(PositionStoreRequest $request): JsonResponse
    {
        $position = PositionStoreUseCase::make()->handle($request->user(), $request->toData());

        $position->loadMissing([
            'files',
            'hiringManagers',
            'recruiters',
            'approvers',
            'externalApprovers',
            'approvals',
            'approvals.modelHasPosition',
            'approvals.modelHasPosition.model',
        ]);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'position' => new PositionResource($position),
        ]);
    }

    public function update(PositionUpdateRequest $request, Position $position): JsonResponse
    {
        $position = PositionUpdateUseCase::make()->handle($request->user(), $position, $request->toData());

        $position->loadMissing([
            'files',
            'hiringManagers',
            'recruiters',
            'approvers',
            'externalApprovers',
            'approvals',
            'approvals.modelHasPosition',
            'approvals.modelHasPosition.model',
        ]);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'position' => new PositionResource($position),
        ]);
    }

    public function show(PositionShowRequest $request, Position $position): JsonResponse
    {
        $position->loadMissing([
            'files',
            'hiringManagers',
            'recruiters',
            'approvers',
            'externalApprovers',
            'approvals',
            'approvals.modelHasPosition',
            'approvals.modelHasPosition.model',
        ]);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'position' => new PositionResource($position),
        ]);
    }

    public function delete(PositionDeleteRequest $request, Position $position): JsonResponse
    {
        PositionDeleteUseCase::make()->handle($request->user(), $position);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS);
    }
}
