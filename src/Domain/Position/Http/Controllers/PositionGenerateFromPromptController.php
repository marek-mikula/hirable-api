<?php

declare(strict_types=1);

namespace Domain\Position\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Position\Http\Request\PositionGenerateFromPromptRequest;
use Domain\Position\Http\Resources\GeneratedPositionResource;
use Domain\Position\UseCases\PositionGenerateFromPromptUseCase;
use Illuminate\Http\JsonResponse;

final class PositionGenerateFromPromptController extends ApiController
{
    public function __invoke(PositionGenerateFromPromptRequest $request): JsonResponse
    {
        $position = PositionGenerateFromPromptUseCase::make()->handle($request->user(), $request->getPrompt());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'position' => new GeneratedPositionResource($position),
        ]);
    }
}
