<?php

declare(strict_types=1);

namespace Domain\Position\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Company\Models\CompanyContact;
use Domain\Position\Http\Request\PositionExternalApprovalDecideRequest;
use Domain\Position\Http\Request\PositionExternalApprovalShowRequest;
use Domain\Position\Http\Resources\PositionShowResource;
use Domain\Position\Models\PositionApproval;
use Domain\Position\Repositories\PositionApprovalRepositoryInterface;
use Domain\Position\UseCases\PositionApprovalDecideUseCase;
use Domain\User\Models\User;
use Illuminate\Http\JsonResponse;
use Support\File\Models\File;

class PositionExternalApprovalController extends ApiController
{
    public function __construct(
        private readonly PositionApprovalRepositoryInterface $positionApprovalRepository,
    ) {
    }

    public function show(PositionExternalApprovalShowRequest $request): JsonResponse
    {
        $approval = $this->positionApprovalRepository->findByToken($request->getToken(), with: [
            'position',
            'position.user',
        ]);

        abort_if(!$approval, code: 404);

        // set empty relations, so we don't leak data
        $approval->position->setRelation('files', modelCollection(File::class));
        $approval->position->setRelation('hiringManagers', modelCollection(User::class));
        $approval->position->setRelation('approvers', modelCollection(User::class));
        $approval->position->setRelation('externalApprovers', modelCollection(CompanyContact::class));
        $approval->position->setRelation('approvals', modelCollection(PositionApproval::class));

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'position' => new PositionShowResource($approval->position),
        ]);
    }

    public function decide(PositionExternalApprovalDecideRequest $request): JsonResponse
    {
        $approval = $this->positionApprovalRepository->findByToken($request->getToken(), with: [
            'token',
            'position',
            'modelHasPosition',
            'modelHasPosition.model',
        ]);

        abort_if(!$approval, code: 404);

        PositionApprovalDecideUseCase::make()->handle($approval->modelHasPosition->model, $approval->position, $approval, $request->toData());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS);
    }
}
