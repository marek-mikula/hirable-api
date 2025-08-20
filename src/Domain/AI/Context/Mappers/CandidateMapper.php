<?php

declare(strict_types=1);

namespace Domain\AI\Context\Mappers;

use Domain\Candidate\Enums\CandidateFieldEnum;
use Domain\Candidate\Models\Candidate;
use Illuminate\Database\Eloquent\Model;

class CandidateMapper extends ModelMapper
{
    /**
     * @param Candidate $model
     */
    public function mapField(Model $model, string $field): mixed
    {
        $field = CandidateFieldEnum::from($field);

        return match ($field) {
            CandidateFieldEnum::FIRSTNAME => $model->firstname,
            CandidateFieldEnum::LASTNAME => $model->lastname,
            CandidateFieldEnum::GENDER => $model->gender ? __(sprintf('common.gender.%s', $model->gender->value)) : null,
            CandidateFieldEnum::LANGUAGE => __(sprintf('common.language.%s', $model->language->value)),
            CandidateFieldEnum::EMAIL => $model->email,
            CandidateFieldEnum::PHONE_PREFIX => $model->phone_prefix,
            CandidateFieldEnum::PHONE_NUMBER => $model->phone_number,
            CandidateFieldEnum::LINKEDIN => $model->linkedin,
            CandidateFieldEnum::INSTAGRAM => $model->instagram,
            CandidateFieldEnum::GITHUB => $model->github,
            CandidateFieldEnum::PORTFOLIO => $model->portfolio,
            CandidateFieldEnum::BIRTH_DATE => $model->birth_date?->format('Y-m-d'),
            CandidateFieldEnum::EXPERIENCE => $model->experience,
            CandidateFieldEnum::TAGS => $model->tags,
            default => throw new \Exception(sprintf('Mapping for field %s is not implemented for %s', $field->value, $model::class)),
        };
    }
}
