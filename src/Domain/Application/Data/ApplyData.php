<?php

declare(strict_types=1);

namespace Domain\Application\Data;

use Illuminate\Http\UploadedFile;
use Support\File\Data\FileData;

readonly class ApplyData
{
    /**
     * @param UploadedFile[] $otherFiles
     */
    public function __construct(
        public string $firstname,
        public string $lastname,
        public string $email,
        public string $phonePrefix,
        public string $phoneNumber,
        public ?string $linkedin,
        public UploadedFile $cv,
        public array $otherFiles,
    ) {
    }

    public function hasOtherFiles(): bool
    {
        return !empty($this->otherFiles);
    }

    public function getCvAsFileData(): FileData
    {
        return FileData::make($this->cv);
    }

    public function getOtherFilesAsFileData(): array
    {
        return array_map(fn (UploadedFile $file) => FileData::make($file), $this->otherFiles);
    }
}
