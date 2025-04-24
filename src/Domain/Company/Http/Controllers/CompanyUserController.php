<?php

namespace Domain\Company\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use App\Http\Resources\Collections\UserPaginatedCollection;
use Domain\Company\Http\Requests\CompanyUserIndexRequest;
use Domain\Company\UseCases\GetCompanyUsersForIndexUseCase;
use Illuminate\Http\JsonResponse;
use Support\Grid\Actions\SaveGridRequestQueryAction;
use Support\Grid\Enums\GridEnum;

use function Illuminate\Support\defer;

class CompanyUserController extends ApiController
{
    public function index(CompanyUserIndexRequest $request): JsonResponse
    {
        $user = $request->user();

        $gridQuery = $request->getGridQuery();

        $users = GetCompanyUsersForIndexUseCase::make()->handle($user, $gridQuery);

        defer(fn () => SaveGridRequestQueryAction::make()->handle($user, GridEnum::COMPANY_USER, $gridQuery));

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'users' => new UserPaginatedCollection($users),
        ]);
    }
}
