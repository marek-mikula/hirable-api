<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Https\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\ProcessStep\Https\Requests\ProcessStepDeleteRequest;
use Domain\ProcessStep\Https\Requests\ProcessStepIndexRequest;
use Domain\ProcessStep\Https\Requests\ProcessStepStoreRequest;
use Domain\ProcessStep\Https\Resources\Collections\ProcessStepCollection;
use Domain\ProcessStep\Https\Resources\ProcessStepResource;
use Domain\ProcessStep\Models\ProcessStep;
use Domain\ProcessStep\Repositories\ProcessStepRepositoryInterface;
use Domain\ProcessStep\UseCases\ProcessStepDeleteUseCase;
use Domain\ProcessStep\UseCases\ProcessStepStoreUseCase;
use Illuminate\Http\JsonResponse;

class ProcessStepController extends ApiController
{
    public function __construct(
        private readonly ProcessStepRepositoryInterface $processStepRepository,
    ) {
    }

    public function store(ProcessStepStoreRequest $request): JsonResponse
    {
        $step = ProcessStepStoreUseCase::make()->handle($request->user(), $request->toData());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'step' => new ProcessStepResource($step),
        ]);
    }

    public function index(ProcessStepIndexRequest $request): JsonResponse
    {
        $steps = $this->processStepRepository->getByCompany($request->user()->company);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'steps' => new ProcessStepCollection($steps),
        ]);
    }

    public function delete(ProcessStepDeleteRequest $request, ProcessStep $processStep): JsonResponse
    {
        ProcessStepDeleteUseCase::make()->handle($processStep);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS);
    }
}
