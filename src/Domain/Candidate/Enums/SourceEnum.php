<?php

declare(strict_types=1);

namespace Domain\Candidate\Enums;

enum SourceEnum: string
{
    case POSITION = 'position';
    case INTERN = 'intern';
    case REFERRAL = 'referral';

    public function getTokenPrefix(): string
    {
        return match ($this) {
            self::POSITION => 'pos',
            self::INTERN => 'int',
            self::REFERRAL => 'ref',
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
