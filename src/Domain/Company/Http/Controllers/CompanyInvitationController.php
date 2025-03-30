<?php

namespace Domain\Company\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Company\Http\Requests\CompanyInvitationsStoreRequest;
use Domain\Company\UseCases\StoreInvitationUseCase;
use Illuminate\Http\JsonResponse;

class CompanyInvitationController extends ApiController
{
    public function store(CompanyInvitationsStoreRequest $request): JsonResponse
    {
        StoreInvitationUseCase::make()->handle($request->user(), $request->toData());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS);
    }
}
