<?php

declare(strict_types=1);

namespace Domain\AI\Enums;

enum AIProviderEnum: string
{
    case FAKE = 'fake';
    case OPENAI = 'openai';
}
