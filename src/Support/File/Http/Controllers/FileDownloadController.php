<?php

declare(strict_types=1);

namespace Support\File\Http\Controllers;

use App\Http\Controllers\ApiController;
use Support\File\Http\Requests\FileDownloadRequest;
use Support\File\Models\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileDownloadController extends ApiController
{
    public function __invoke(FileDownloadRequest $request, File $file): BinaryFileResponse
    {
        return response()->download($file->real_path, $file->name);
    }
}
