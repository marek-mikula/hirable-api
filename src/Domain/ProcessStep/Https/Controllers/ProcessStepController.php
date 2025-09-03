<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Https\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use App\Http\Resources\Collections\ResourceCollection;
use Domain\ProcessStep\Https\Requests\ProcessStepDeleteRequest;
use Domain\ProcessStep\Https\Requests\ProcessStepIndexRequest;
use Domain\ProcessStep\Https\Requests\ProcessStepStoreRequest;
use Domain\ProcessStep\Https\Requests\ProcessStepUpdateRequest;
use Domain\ProcessStep\Https\Resources\ProcessStepResource;
use Domain\ProcessStep\Models\ProcessStep;
use Domain\ProcessStep\Repositories\ProcessStepRepositoryInterface;
use Domain\ProcessStep\UseCases\ProcessStepDeleteUseCase;
use Domain\ProcessStep\UseCases\ProcessStepStoreUseCase;
use Domain\ProcessStep\UseCases\ProcessStepUpdateUseCase;
use Illuminate\Http\JsonResponse;

class ProcessStepController extends ApiController
{
    public function __construct(
        private readonly ProcessStepRepositoryInterface $processStepRepository,
    ) {
    }

    public function index(ProcessStepIndexRequest $request): JsonResponse
    {
        $processSteps = $this->processStepRepository->getByCompany($request->user()->company, $request->includeCommon());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'processSteps' => new ResourceCollection(ProcessStepResource::class, $processSteps),
        ]);
    }

    public function store(ProcessStepStoreRequest $request): JsonResponse
    {
        $processStep = ProcessStepStoreUseCase::make()->handle($request->user(), $request->toData());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'processStep' => new ProcessStepResource($processStep),
        ]);
    }

    public function update(ProcessStepUpdateRequest $request, ProcessStep $processStep): JsonResponse
    {
        ProcessStepUpdateUseCase::make()->handle($processStep, $request->toData());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'processStep' => new ProcessStepResource($processStep),
        ]);
    }

    public function delete(ProcessStepDeleteRequest $request, ProcessStep $processStep): JsonResponse
    {
        ProcessStepDeleteUseCase::make()->handle($processStep);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS);
    }
}
