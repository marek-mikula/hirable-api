<?php

namespace Domain\Register\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use App\Http\Resources\AuthUserResource;
use Domain\Auth\Services\AuthService;
use Domain\Register\Http\Requests\RegisterRegisterRequest;
use Domain\Register\Http\Requests\RegisterRequestRequest;
use Domain\Register\UseCases\RegisterInvitationUseCase;
use Domain\Register\UseCases\RegisterUseCase;
use Domain\Register\UseCases\RequestRegistrationUseCase;
use Illuminate\Http\JsonResponse;
use Support\Token\Enums\TokenTypeEnum;

class RegisterController extends ApiController
{
    public function request(RegisterRequestRequest $request): JsonResponse
    {
        RequestRegistrationUseCase::make()->handle(email: $request->getEmail());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS);
    }

    public function register(RegisterRegisterRequest $request): JsonResponse
    {
        $token = $request->getToken();

        if ($token->type === TokenTypeEnum::INVITATION) {
            $user = RegisterInvitationUseCase::make()->handle($token, $request->toData());
        } else {
            $user = RegisterUseCase::make()->handle($token, $request->toData());
        }

        /** @var AuthService $authService */
        $authService = app(AuthService::class);

        $authService->loginWithModel($user); // immediately login user

        // load needed relationships
        $user->loadMissing('company');

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'user' => new AuthUserResource($user),
        ]);
    }
}
