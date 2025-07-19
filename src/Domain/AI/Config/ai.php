<?php

declare(strict_types=1);

use Domain\AI\Enums\AIServiceEnum;
use Services\OpenAI\Services\OpenAIService;

return [

    'service' => env('AI_SERVICE', AIServiceEnum::OPENAI->value),

    'services' => [
        AIServiceEnum::OPENAI->value => OpenAIService::class,
    ],

];
