<?php

declare(strict_types=1);

namespace Services\OpenAI\Enums;

enum PromptEnum: string
{
    case EXTRACT_CV_DATA = 'extract_cv_data';
    case SCORE_APPLICATION = 'score_application';
}
