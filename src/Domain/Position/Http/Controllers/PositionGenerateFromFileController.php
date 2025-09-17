<?php

declare(strict_types=1);

namespace Domain\Position\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Position\Http\Request\PositionGenerateFromFileRequest;
use Domain\Position\Http\Resources\GeneratedPositionResource;
use Domain\Position\UseCases\PositionGenerateFromFileUseCase;
use Illuminate\Http\JsonResponse;

final class PositionGenerateFromFileController extends ApiController
{
    public function __invoke(PositionGenerateFromFileRequest $request): JsonResponse
    {
        $position = PositionGenerateFromFileUseCase::make()->handle($request->user(), $request->getFile());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'position' => new GeneratedPositionResource($position),
        ]);
    }
}
