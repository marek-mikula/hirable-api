<?php

declare(strict_types=1);

namespace Domain\Candidate\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Candidate\Http\Request\CandidateIndexRequest;
use Domain\Candidate\Http\Request\CandidateShowRequest;
use Domain\Candidate\Http\Resources\CandidateResource;
use Domain\Candidate\Http\Resources\Collections\CandidateListPaginatedCollection;
use Domain\Candidate\Models\Candidate;
use Domain\Candidate\UseCases\CandidateIndexUseCase;
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
            'candidates' => new CandidateListPaginatedCollection($candidates),
        ]);
    }

    public function show(CandidateShowRequest $request, Candidate $candidate): JsonResponse
    {
        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'candidate' => new CandidateResource($candidate),
        ]);
    }
}
