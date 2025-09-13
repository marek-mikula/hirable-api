<?php

declare(strict_types=1);

namespace AIProviders\OpenAI\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Support\File\Models\File;

class OpenAIFileManager
{
    public function attachFile(File $file): array
    {
        $content = Storage::disk($file->disk->value)->get($file->path);

        return [
            'type' => 'input_file',
            'filename' => $file->name,
            'file_data' => sprintf('%s,%s', 'data:application/pdf;base64', base64_encode((string) $content))
        ];
    }

    public function attachUploadedFile(UploadedFile $file): array
    {
        return [
            'type' => 'input_file',
            'filename' => $file->getClientOriginalName(),
            'file_data' => sprintf('%s,%s', 'data:application/pdf;base64', base64_encode($file->getContent()))
        ];
    }
}
