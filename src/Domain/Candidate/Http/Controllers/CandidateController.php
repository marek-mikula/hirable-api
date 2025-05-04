<?php

declare(strict_types=1);

namespace Domain\Candidate\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Candidate\Http\Request\CandidateIndexRequest;
use Domain\Candidate\Http\Resources\Collections\CandidatePaginatedCollection;
use Domain\Candidate\UseCases\GetCandidatesForIndexUseCase;
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

        $candidates = GetCandidatesForIndexUseCase::make()->handle($user, $gridQuery);

        defer(fn () => SaveGridRequestQueryAction::make()->handle($user, GridEnum::CANDIDATE, $gridQuery));

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'candidates' => new CandidatePaginatedCollection($candidates),
        ]);
    }
}
