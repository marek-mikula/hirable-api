<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Https\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\ProcessStep\Https\Requests\ProcessStepStoreRequest;
use Domain\ProcessStep\Https\Resources\ProcessStepResource;
use Domain\ProcessStep\UseCases\ProcessStepStoreUseCase;
use Illuminate\Http\JsonResponse;

class ProcessStepController extends ApiController
{
    public function store(ProcessStepStoreRequest $request): JsonResponse
    {
        $step = ProcessStepStoreUseCase::make()->handle($request->user(), $request->toData());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'step' => new ProcessStepResource($step),
        ]);
    }
}
