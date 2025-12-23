<?php

declare(strict_types=1);

namespace Domain\Candidate\Enums;

enum SourceEnum: string
{
    case POSITION = 'position';

    public function getTokenPrefix(): string
    {
        return match ($this) {
            self::POSITION => 'pos',
        };
    }

    public static function fromTokenPrefix(string $prefix): ?SourceEnum
    {
        foreach (self::cases() as $enum) {
            if ($enum->getTokenPrefix() === $prefix) {
                return $enum;
            }
        }

        return null;
    }
}
