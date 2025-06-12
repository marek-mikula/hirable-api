<?php

declare(strict_types=1);

namespace Support\File\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Support\File\Http\Requests\FileDeleteRequest;
use Support\File\Http\Requests\FileShowRequest;
use Support\File\Models\File;
use Support\File\Repositories\FileRepositoryInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileController extends ApiController
{
    public function __construct(
        private readonly FileRepositoryInterface $fileRepository,
    ) {
    }

    public function show(FileShowRequest $request, File $file): BinaryFileResponse
    {
        return response()->file($file->real_path);
    }

    public function delete(FileDeleteRequest $request, File $file): JsonResponse
    {
        $this->fileRepository->delete($file);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS);
    }
}
