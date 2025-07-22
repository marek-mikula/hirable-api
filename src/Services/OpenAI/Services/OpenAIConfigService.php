<?php

declare(strict_types=1);

namespace Services\OpenAI\Services;

use Services\OpenAI\Enums\PromptEnum;

class OpenAIConfigService
{
    public function getModel(PromptEnum $prompt): string
    {
        $model = config(sprintf('openai.models.%s', $prompt->value));

        if (!empty($model)) {
            return (string) $model;
        }

        return (string) config('openai.model');
    }

    public function getPrompt(PromptEnum $prompt, array $variables = []): array
    {
        $result = (array) config(sprintf('openai.prompts.%s', $prompt->value));

        if (!empty($variables)) {
            $result['variables'] = $variables;
        }

        return $result;
    }
}
