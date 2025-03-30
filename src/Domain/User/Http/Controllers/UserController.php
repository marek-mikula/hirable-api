<?php

namespace Domain\User\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use App\Http\Resources\Collections\UserPaginatedCollection;
use Domain\User\Http\Request\UserIndexRequest;
use Domain\User\UseCases\GetUsersForIndexUseCase;
use Illuminate\Http\JsonResponse;
use Support\Grid\Actions\SaveGridRequestQueryAction;
use Support\Grid\Enums\GridEnum;

use function Illuminate\Support\defer;

class UserController extends ApiController
{
    public function index(UserIndexRequest $request): JsonResponse
    {
        $user = $request->user();

        $gridQuery = $request->getGridQuery();

        $users = GetUsersForIndexUseCase::make()->handle($request->user(), $gridQuery);

        defer(fn () => SaveGridRequestQueryAction::make()->handle($user, GridEnum::USER, $gridQuery));

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'users' => new UserPaginatedCollection($users),
        ]);
    }
}
