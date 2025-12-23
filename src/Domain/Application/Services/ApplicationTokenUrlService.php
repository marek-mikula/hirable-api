<?php

declare(strict_types=1);

namespace Domain\Application\Services;

use App\Services\Service;
use Domain\Application\TokenProcessing\TokenParserService;
use Domain\Candidate\Enums\SourceEnum;

class ApplicationTokenUrlService extends Service
{
    public function __construct(
        private readonly TokenParserService $tokenParserService,
    ) {
    }

    public function getApplyUrl(SourceEnum $source, string $token): string
    {
        return frontendLink('/apply?token={token}', ['token' => $this->tokenParserService->toUrlValue($source, $token)]);
    }
}
