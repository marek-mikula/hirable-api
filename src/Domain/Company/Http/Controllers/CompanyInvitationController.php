<?php

declare(strict_types=1);

namespace Domain\Company\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Company\Http\Requests\CompanyInvitationIndexRequest;
use Domain\Company\Http\Requests\CompanyInvitationsStoreRequest;
use Domain\Company\UseCases\GetCompanyInvitationsForIndexUseCase;
use Domain\Company\UseCases\StoreCompanyInvitationUseCase;
use Illuminate\Http\JsonResponse;
use Support\Grid\Actions\SaveGridRequestQueryAction;
use Support\Grid\Enums\GridEnum;
use Support\Token\Http\Resources\Collections\TokenInvitationPaginatedCollection;

use function Illuminate\Support\defer;

class CompanyInvitationController extends ApiController
{
    public function index(CompanyInvitationIndexRequest $request): JsonResponse
    {
        $user = $request->user();

        $gridQuery = $request->getGridQuery();

        $invitations = GetCompanyInvitationsForIndexUseCase::make()->handle($user, $gridQuery);

        defer(fn () => SaveGridRequestQueryAction::make()->handle($user, GridEnum::COMPANY_INVITATION, $gridQuery));

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'invitations' => new TokenInvitationPaginatedCollection($invitations),
        ]);
    }

    public function store(CompanyInvitationsStoreRequest $request): JsonResponse
    {
        StoreCompanyInvitationUseCase::make()->handle($request->user(), $request->toData());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS);
    }
}
