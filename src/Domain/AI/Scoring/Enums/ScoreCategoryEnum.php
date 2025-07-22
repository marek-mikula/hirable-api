<?php

declare(strict_types=1);

namespace Domain\AI\Scoring\Enums;

use Domain\Position\Models\Position;

enum ScoreCategoryEnum: string
{
    case HARD_SKILLS = 'hardSkills';
    case SOFT_SKILLS = 'softSkills';
    case LANGUAGE_SKILLS = 'languageSkills';
    case EDUCATION = 'education';
    case EXPERIENCE = 'experience';

    public function getWeight(Position $position): int
    {
        return match ($this) {
            self::HARD_SKILLS => $position->hard_skills_weight,
            self::SOFT_SKILLS => $position->soft_skills_weight,
            self::LANGUAGE_SKILLS => $position->language_skills_weight,
            self::EDUCATION => $position->education_weight,
            self::EXPERIENCE => $position->experience_weight,
        };
    }
}
