<?php

declare(strict_types=1);

namespace Services\OpenAI\Services;

use Services\OpenAI\Enums\PromptEnum;

class OpenAIConfigService
{
    public function getModel(): string
    {
        return (string) config('openai.model');
    }

    public function getPrompt(PromptEnum $prompt): array
    {
        return (array) config(sprintf('openai.prompts.%s', $prompt->value));
    }
}
