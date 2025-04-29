<?php

declare(strict_types=1);

namespace Support\File\Data;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

class FileData extends Data
{
    public UploadedFile $file;

    public array $data = [];

    public static function make(UploadedFile $file, array $data = []): static
    {
        return static::from(['file' => $file, 'data' => $data]);
    }

    public function getName(): string
    {
        return $this->file->getClientOriginalName();
    }

    public function getMime(): string
    {
        return $this->file->getMimeType() ?: $this->file->getClientMimeType() ?: 'unknown';
    }

    public function getExtension(): string
    {
        return $this->file->getExtension() ?: $this->file->getClientOriginalExtension() ?: 'unknown';
    }

    public function getSize(): int
    {
        return (int) $this->file->getSize();
    }
}
