<?php

declare(strict_types=1);

namespace Domain\AI\Context\Transformers;

use App\Enums\LanguageEnum;
use Domain\Candidate\Enums\CandidateFieldEnum;
use Domain\Candidate\Enums\GenderEnum;
use Domain\Candidate\Services\CandidateConfigService;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CandidateTransformer extends ModelTransformer
{
    public function __construct(
        private readonly CandidateConfigService $candidateConfigService,
    ) {
    }

    public function transformField(string $field, mixed $value): mixed
    {
        $field = CandidateFieldEnum::from($field);

        return match ($field) {
            CandidateFieldEnum::EMAIL => $this->transformEmail($value),
            CandidateFieldEnum::FIRSTNAME,
            CandidateFieldEnum::LASTNAME,
            CandidateFieldEnum::PHONE_PREFIX,
            CandidateFieldEnum::PHONE_NUMBER => (string) $value,
            CandidateFieldEnum::LINKEDIN,
            CandidateFieldEnum::INSTAGRAM,
            CandidateFieldEnum::GITHUB,
            CandidateFieldEnum::PORTFOLIO => $this->transformUrl($value),
            CandidateFieldEnum::BIRTH_DATE => $this->toCarbon((string) $value, 'Y-m-d'),
            CandidateFieldEnum::EXPERIENCE => array_map(fn (array $item): array => [
                'position' => Arr::get($item, 'position'),
                'employer' => Arr::get($item, 'employer'),
                'from' => empty($from = Arr::get($item, 'from')) ? null : $this->toCarbonFormat($from, 'Y-m-d'),
                'to' => empty($to = Arr::get($item, 'to')) ? null : $this->toCarbonFormat($to, 'Y-m-d'),
                'description' => Arr::get($item, 'description'),
            ], $value),
            CandidateFieldEnum::TAGS => collect($value)
                ->filter(fn (mixed $value): bool => !empty($value) && is_string($value))
                ->take($this->candidateConfigService->getMaxTags())
                ->values()
                ->toArray(),
            CandidateFieldEnum::GENDER => GenderEnum::tryFrom((string) $value),
            CandidateFieldEnum::LANGUAGE => LanguageEnum::tryFrom((string) $value),
        };
    }

    private function transformEmail(mixed $value): ?string
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return (string) $value;
        }

        return null;
    }

    private function transformUrl(mixed $value): ?string
    {
        return Str::isUrl($value) ? (string) $value : null;
    }
}
