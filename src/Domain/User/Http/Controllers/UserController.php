<?php

declare(strict_types=1);

namespace Domain\User\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Auth\Http\Resources\AuthUserResource;
use Domain\User\Http\Requests\UserUpdateRequest;
use Domain\User\Models\User;
use Domain\User\UseCases\UserUpdateUseCase;
use Illuminate\Http\JsonResponse;

final class UserController extends ApiController
{
    public function update(UserUpdateRequest $request, User $user): JsonResponse
    {
        $user = UserUpdateUseCase::make()->handle($user, $request->getValues());

        // load needed relationships
        $user->loadMissing('company');

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'user' => new AuthUserResource($user),
        ]);
    }
}
