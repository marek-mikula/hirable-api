<?php

declare(strict_types=1);

namespace Domain\Position\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Position\Http\Request\PositionFileDestroyRequest;
use Domain\Position\Models\Position;
use Illuminate\Http\JsonResponse;
use Support\File\Models\File;
use Support\File\Repositories\FileRepositoryInterface;

class PositionFileController extends ApiController
{
    public function __construct(
        private readonly FileRepositoryInterface $fileRepository,
    ) {
    }

    public function destroy(PositionFileDestroyRequest $request, Position $position, File $file): JsonResponse
    {
        $this->fileRepository->delete($file);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS);
    }
}
