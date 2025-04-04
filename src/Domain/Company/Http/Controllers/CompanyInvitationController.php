<?php

namespace Domain\Company\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use App\Http\Resources\Collections\TokenInvitationPaginatedCollection;
use Domain\Company\Http\Requests\CompanyInvitationIndexRequest;
use Domain\Company\Http\Requests\CompanyInvitationsStoreRequest;
use Domain\Company\UseCases\GetInvitationsForIndexUseCase;
use Domain\Company\UseCases\StoreInvitationUseCase;
use Illuminate\Http\JsonResponse;
use Support\Grid\Actions\SaveGridRequestQueryAction;
use Support\Grid\Enums\GridEnum;

use function Illuminate\Support\defer;

class CompanyInvitationController extends ApiController
{
    public function index(CompanyInvitationIndexRequest $request): JsonResponse
    {
        $user = $request->user();

        $gridQuery = $request->getGridQuery();

        $invitations = GetInvitationsForIndexUseCase::make()->handle($user, $gridQuery);

        defer(fn () => SaveGridRequestQueryAction::make()->handle($user, GridEnum::COMPANY_INVITATION, $gridQuery));

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'invitations' => new TokenInvitationPaginatedCollection($invitations),
        ]);
    }

    public function store(CompanyInvitationsStoreRequest $request): JsonResponse
    {
        StoreInvitationUseCase::make()->handle($request->user(), $request->toData());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS);
    }
}
