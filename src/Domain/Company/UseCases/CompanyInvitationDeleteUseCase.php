<?php

declare(strict_types=1);

namespace Domain\Company\UseCases;

use App\UseCases\UseCase;
use Illuminate\Support\Facades\DB;
use Support\Token\Models\Token;
use Support\Token\Repositories\TokenRepositoryInterface;

class CompanyInvitationDeleteUseCase extends UseCase
{
    public function __construct(
        private readonly TokenRepositoryInterface $tokenRepository,
    ) {
    }

    public function handle(Token $token): void
    {
        DB::transaction(function () use ($token): void {
            $this->tokenRepository->delete($token);
        }, attempts: 5);
    }
}
