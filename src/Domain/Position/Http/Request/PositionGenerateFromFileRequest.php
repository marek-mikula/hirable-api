<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use App\Rules\Rule;
use Domain\Position\Models\Position;
use Domain\Position\Policies\PositionPolicy;
use Illuminate\Http\UploadedFile;
use Support\File\Enums\FileTypeEnum;
use Support\File\Services\FileConfigService;

class PositionGenerateFromFileRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see PositionPolicy::store() */
        return $this->user()->can('store', Position::class);
    }

    public function rules(FileConfigService $fileConfigService): array
    {
        return [
            'file' => [
                'required',
                Rule::file()
                    ->max($fileConfigService->getFileMaxSize(FileTypeEnum::POSITION_GENERATE_FROM_FILE))
                    ->extensions($fileConfigService->getFileExtensions(FileTypeEnum::POSITION_GENERATE_FROM_FILE))
            ]
        ];
    }

    public function getUploadedFile(): UploadedFile
    {
        return $this->file('file');
    }
}
