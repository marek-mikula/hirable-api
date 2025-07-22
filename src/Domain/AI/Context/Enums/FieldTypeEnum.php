<?php

declare(strict_types=1);

namespace Domain\AI\Context\Enums;

enum FieldTypeEnum: string
{
    case STRING = 'string';
    case INTEGER = 'integer';
    case CLASSIFIER = 'classifier';
    case CUSTOM = 'custom';
}
