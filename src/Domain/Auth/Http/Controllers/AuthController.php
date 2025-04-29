<?php

declare(strict_types=1);

namespace Domain\Auth\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use App\Http\Resources\AuthUserResource;
use Domain\Auth\Http\Requests\AuthLoginRequest;
use Domain\Auth\Http\Requests\AuthLogoutRequest;
use Domain\Auth\Http\Requests\AuthMeRequest;
use Domain\Auth\Http\Requests\AuthUpdateRequest;
use Domain\Auth\Services\AuthService;
use Domain\Auth\UseCases\UpdateUserUseCase;
use Illuminate\Http\JsonResponse;

class AuthController extends ApiController
{
    public function login(AuthLoginRequest $request): JsonResponse
    {
        /** @var AuthService $service */
        $service = app(AuthService::class);

        $user = $service->login($request->toData());

        // load needed relationships
        $user->loadMissing('company');

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'user' => new AuthUserResource($user),
        ]);
    }

    public function logout(AuthLogoutRequest $request): JsonResponse
    {
        /** @var AuthService $service */
        $service = app(AuthService::class);

        $service->logout($request);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS);
    }

    public function me(AuthMeRequest $request): JsonResponse
    {
        $user = $request->user();

        // load needed relationships
        $user->loadMissing('company');

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'user' => new AuthUserResource($user),
        ]);
    }

    public function update(AuthUpdateRequest $request): JsonResponse
    {
        $user = UpdateUserUseCase::make()->handle($request->user(), $request->getValues());

        // load needed relationships
        $user->loadMissing('company');

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'user' => new AuthUserResource($user),
        ]);
    }
}
