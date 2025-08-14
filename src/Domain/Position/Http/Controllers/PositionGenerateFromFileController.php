<?php

declare(strict_types=1);

namespace Domain\Position\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Company\Models\CompanyContact;
use Domain\Position\Http\Request\PositionGenerateFromFileRequest;
use Domain\Position\Http\Resources\PositionResource;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionApproval;
use Domain\User\Models\User;
use Illuminate\Http\JsonResponse;
use Support\File\Models\File;

class PositionGenerateFromFileController extends ApiController
{
    public function __invoke(PositionGenerateFromFileRequest $request): JsonResponse
    {
        /** @var Position $position */
        $position = [];

        $position->setRelation('user', $request->user());
        $position->setRelation('files', modelCollection(File::class));
        $position->setRelation('hiringManagers', modelCollection(User::class));
        $position->setRelation('approvers', modelCollection(User::class));
        $position->setRelation('externalApprovers', modelCollection(CompanyContact::class));
        $position->setRelation('approvals', modelCollection(PositionApproval::class));

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'approval' => new PositionResource($position),
        ]);
    }
}
