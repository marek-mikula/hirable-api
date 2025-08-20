<?php

declare(strict_types=1);

namespace Domain\AI\Context\Transformers;

use App\Enums\LanguageEnum;
use Domain\Candidate\Enums\CandidateFieldEnum;
use Domain\Candidate\Enums\GenderEnum;
use Domain\Candidate\Models\Candidate;
use Domain\Candidate\Services\CandidateConfigService;
use Illuminate\Support\Arr;

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
            CandidateFieldEnum::FIRSTNAME,
            CandidateFieldEnum::LASTNAME,
            CandidateFieldEnum::EMAIL,
            CandidateFieldEnum::PHONE_PREFIX,
            CandidateFieldEnum::PHONE_NUMBER,
            CandidateFieldEnum::LINKEDIN,
            CandidateFieldEnum::INSTAGRAM,
            CandidateFieldEnum::GITHUB,
            CandidateFieldEnum::PORTFOLIO => (string) $value,

            CandidateFieldEnum::BIRTH_DATE => $this->toCarbon((string) $value, 'Y-m-d'),

            CandidateFieldEnum::EXPERIENCE => array_map(function (array $item) {
                return [
                    'position' => Arr::get($item, 'position'),
                    'employer' => Arr::get($item, 'employer'),
                    'from' => empty($from = Arr::get($item, 'from')) ? null : $this->toCarbonFormat($from, 'Y-m-d'),
                    'to' => empty($to = Arr::get($item, 'to')) ? null : $this->toCarbonFormat($to, 'Y-m-d'),
                    'description' => Arr::get($item, 'description'),
                ];
            }, $value),

            CandidateFieldEnum::TAGS => collect($value)
                ->filter(fn (mixed $value) => !empty($value) && is_string($value))
                ->take($this->candidateConfigService->getMaxTags())
                ->values(),

            CandidateFieldEnum::GENDER => GenderEnum::tryFrom((string) $value),
            CandidateFieldEnum::LANGUAGE => LanguageEnum::tryFrom((string) $value),

            default => throw new \Exception(sprintf('Transformation for field %s is not implemented for %s', $field->value, Candidate::class)),
        };
    }
}
