<?php

declare(strict_types=1);

namespace Domain\Company\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Company\Http\Requests\CompanyInvitationIndexRequest;
use Domain\Company\Http\Requests\CompanyInvitationsStoreRequest;
use Domain\Company\Models\Company;
use Domain\Company\UseCases\CompanyInvitationIndexUseCase;
use Domain\Company\UseCases\CompanyInvitationStoreUseCase;
use Illuminate\Http\JsonResponse;
use Support\Grid\Actions\SaveGridRequestQueryAction;
use Support\Grid\Enums\GridEnum;
use Support\Token\Http\Resources\Collections\TokenInvitationPaginatedCollection;

use function Illuminate\Support\defer;

class CompanyInvitationController extends ApiController
{
    public function index(CompanyInvitationIndexRequest $request, Company $company): JsonResponse
    {
        $user = $request->user();

        $gridQuery = $request->getGridQuery();

        $invitations = CompanyInvitationIndexUseCase::make()->handle($company, $gridQuery);

        defer(fn () => SaveGridRequestQueryAction::make()->handle($user, GridEnum::COMPANY_INVITATION, $gridQuery));

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'invitations' => new TokenInvitationPaginatedCollection($invitations),
        ]);
    }

    public function store(CompanyInvitationsStoreRequest $request, Company $company): JsonResponse
    {
        CompanyInvitationStoreUseCase::make()->handle($request->user(), $company, $request->toData());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS);
    }
}
