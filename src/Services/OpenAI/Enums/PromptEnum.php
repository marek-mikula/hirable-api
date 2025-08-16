<?php

declare(strict_types=1);

namespace Services\OpenAI\Enums;

enum PromptEnum: string
{
    case EXTRACT_CV_DATA = 'extract_cv_data';
    case EVALUATE_CANDIDATE = 'evaluate_candidate';
    case GENERATE_POSITION_FROM_PROMPT = 'generate_position_from_prompt';
    case GENERATE_POSITION_FROM_FILE = 'generate_position_from_file';
}
