<?php

declare(strict_types=1);

namespace Domain\Application\Data;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

class ApplyData extends Data
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
}
