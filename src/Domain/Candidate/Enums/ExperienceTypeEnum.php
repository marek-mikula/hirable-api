<?php

declare(strict_types=1);

namespace Domain\Candidate\Enums;

enum ExperienceTypeEnum: string
{
    case FULL_TIME = 'full-time';
    case PART_TIME = 'part-time';
    case INTERNSHIP = 'internship';
}
