<?php

declare(strict_types=1);

namespace Domain\Candidate\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use App\Http\Resources\Collections\PaginatedResourceCollection;
use Domain\Candidate\Http\Request\CandidateIndexRequest;
use Domain\Candidate\Http\Request\CandidateShowRequest;
use Domain\Candidate\Http\Request\CandidateUpdateRequest;
use Domain\Candidate\Http\Resources\CandidateResource;
use Domain\Candidate\Http\Resources\CandidateShowResource;
use Domain\Candidate\Models\Candidate;
use Domain\Candidate\UseCases\CandidateIndexUseCase;
use Domain\Candidate\UseCases\CandidateUpdateUseCase;
use Illuminate\Http\JsonResponse;
use Support\Grid\Actions\SaveGridRequestQueryAction;
use Support\Grid\Enums\GridEnum;

use function Illuminate\Support\defer;

class CandidateController extends ApiController
{
    public function index(CandidateIndexRequest $request): JsonResponse
    {
        $user = $request->user();

        $gridQuery = $request->getGridQuery();

        $candidates = CandidateIndexUseCase::make()->handle($user, $gridQuery);

        defer(fn () => SaveGridRequestQueryAction::make()->handle($user, GridEnum::CANDIDATE, $gridQuery));

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'candidates' => new PaginatedResourceCollection(CandidateResource::class, $candidates),
        ]);
    }

    public function show(CandidateShowRequest $request, Candidate $candidate): JsonResponse
    {
        $candidate->loadMissing('files');

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'candidate' => new CandidateShowResource($candidate),
        ]);
    }

    public function update(CandidateUpdateRequest $request, Candidate $candidate): JsonResponse
    {
        $candidate = CandidateUpdateUseCase::make()->handle($request->user(), $candidate, $request->toData());

        $candidate->loadMissing('files');

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'candidate' => new CandidateShowResource($candidate),
        ]);
    }
}
