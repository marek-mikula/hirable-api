<?php

declare(strict_types=1);

namespace Domain\Company\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Company\Http\Requests\CompanyUserIndexRequest;
use Domain\Company\Models\Company;
use Domain\Company\UseCases\CompanyUserIndexUseCase;
use Domain\User\Http\Resources\Collections\UserPaginatedResourceCollection;
use Illuminate\Http\JsonResponse;
use Support\Grid\Actions\SaveGridRequestQueryAction;
use Support\Grid\Enums\GridEnum;

use function Illuminate\Support\defer;

class CompanyUserController extends ApiController
{
    public function index(CompanyUserIndexRequest $request, Company $company): JsonResponse
    {
        $user = $request->user();

        $gridQuery = $request->getGridQuery();

        $users = CompanyUserIndexUseCase::make()->handle($company, $gridQuery);

        defer(fn () => SaveGridRequestQueryAction::make()->handle($user, GridEnum::COMPANY_USER, $gridQuery));

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'users' => new UserPaginatedResourceCollection($users),
        ]);
    }
}
