<?php

declare(strict_types=1);

namespace Domain\AI\Serialization\Enums;

enum FieldTypeEnum: string
{
    case STRING = 'string';
    case INTEGER = 'integer';
    case CLASSIFIER = 'classifier';
    case CUSTOM = 'custom';
}
