<?php

declare(strict_types=1);

namespace Domain\Company\UseCases;

use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;
use App\UseCases\UseCase;
use Domain\Company\Models\CompanyContact;
use Domain\Company\Repositories\CompanyContactRepositoryInterface;
use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Models\Position;
use Domain\Position\Repositories\ModelHasPositionRepositoryInterface;
use Domain\Position\Repositories\PositionApprovalRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CompanyContactDeleteUseCase extends UseCase
{
    public function __construct(
        private readonly PositionApprovalRepositoryInterface $positionApprovalRepository,
        private readonly ModelHasPositionRepositoryInterface $modelHasPositionRepository,
        private readonly CompanyContactRepositoryInterface $companyContactRepository,
    ) {
    }

    public function handle(CompanyContact $contact): void
    {
        $pendingApprovals = $this->positionApprovalRepository->getApprovalsByModelInstate($contact, PositionApprovalStateEnum::PENDING, with: [
            'position',
        ]);

        // check active approval processes
        if ($pendingApprovals->isNotEmpty()) {
            throw new HttpException(responseCode: ResponseCodeEnum::CONTACT_PENDING_APPROVALS, data: [
                'positions' => $pendingApprovals
                    ->pluck('position')
                    ->map(fn (Position $position) => [
                        'id' => $position->id,
                        'name' => $position->name,
                    ])
                    ->all(),
            ]);
        }

        DB::transaction(function () use ($contact): void {
            $this->modelHasPositionRepository->deleteByModel($contact);
            $this->companyContactRepository->delete($contact);
        }, attempts: 5);
    }
}
