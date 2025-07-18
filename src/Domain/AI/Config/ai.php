<?php

declare(strict_types=1);

use Domain\AI\Enums\AIServiceEnum;

return [

    'service' => env('AI_SERVICE', AIServiceEnum::OPENAI->value),

    'services' => [
        AIServiceEnum::OPENAI->value => '', // todo add class ref
    ],

];
