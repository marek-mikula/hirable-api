<?php

declare(strict_types=1);

namespace Domain\AI\Context\Transformers;

use Domain\Position\Enums\PositionFieldEnum;
use Domain\Position\Models\Position;
use Domain\Position\Services\PositionConfigService;
use Illuminate\Support\Arr;
use Support\Classifier\Actions\ToClassifierAction;
use Support\Classifier\Enums\ClassifierTypeEnum;

class PositionTransformer extends ModelTransformer
{
    public function __construct(
        private readonly PositionConfigService $positionConfigService,
    ) {
    }

    public function transformField(string $field, mixed $value): mixed
    {
        $field = PositionFieldEnum::from($field);

        $toClassifier = ToClassifierAction::make();

        return match ($field) {
            PositionFieldEnum::NAME,
            PositionFieldEnum::DEPARTMENT,
            PositionFieldEnum::DESCRIPTION,
            PositionFieldEnum::SALARY_VAR,
            PositionFieldEnum::EDUCATION_FIELD,
            PositionFieldEnum::HARD_SKILLS => (string) $value,

            PositionFieldEnum::FIELD => $toClassifier->handle($value, ClassifierTypeEnum::FIELD),
            PositionFieldEnum::SALARY_TYPE => $toClassifier->handle($value, ClassifierTypeEnum::SALARY_TYPE),
            PositionFieldEnum::SALARY_FREQUENCY => $toClassifier->handle($value, ClassifierTypeEnum::SALARY_FREQUENCY),
            PositionFieldEnum::SALARY_CURRENCY => $toClassifier->handle($value, ClassifierTypeEnum::CURRENCY),
            PositionFieldEnum::MIN_EDUCATION_LEVEL => $toClassifier->handle($value, ClassifierTypeEnum::EDUCATION_LEVEL),

            PositionFieldEnum::WORKLOADS => array_map(fn (string $item) => $toClassifier->handle($item, ClassifierTypeEnum::WORKLOAD), $value),
            PositionFieldEnum::EMPLOYMENT_RELATIONSHIPS => array_map(fn (string $item) => $toClassifier->handle($item, ClassifierTypeEnum::EMPLOYMENT_RELATIONSHIP), $value),
            PositionFieldEnum::EMPLOYMENT_FORMS => array_map(fn (string $item) => $toClassifier->handle($item, ClassifierTypeEnum::EMPLOYMENT_FORM), $value),
            PositionFieldEnum::BENEFITS => array_map(fn (string $item) => $toClassifier->handle($item, ClassifierTypeEnum::BENEFIT), $value),
            PositionFieldEnum::SENIORITY => array_map(fn (string $item) => $toClassifier->handle($item, ClassifierTypeEnum::SENIORITY), $value),

            PositionFieldEnum::EXPERIENCE,
            PositionFieldEnum::SALARY_FROM,
            PositionFieldEnum::SALARY_TO,
            PositionFieldEnum::JOB_SEATS_NUM,
            PositionFieldEnum::ORGANISATION_SKILLS,
            PositionFieldEnum::TEAM_SKILLS,
            PositionFieldEnum::TIME_MANAGEMENT,
            PositionFieldEnum::COMMUNICATION_SKILLS,
            PositionFieldEnum::LEADERSHIP => (int) $value,

            PositionFieldEnum::LANGUAGE_REQUIREMENTS => array_map(function (array $item) use ($toClassifier): array {
                $language = (string) Arr::get($item, 'language');
                $level = (string) Arr::get($item, 'level');

                return [
                    'language' => $toClassifier->handle($language, ClassifierTypeEnum::LANGUAGE),
                    'level' => $toClassifier->handle($level, ClassifierTypeEnum::LANGUAGE_LEVEL),
                ];
            }, $value),

            PositionFieldEnum::TAGS => collect($value)
                ->filter(fn (mixed $value): bool => !empty($value) && is_string($value))
                ->take($this->positionConfigService->getMaxTags())
                ->values(),
            default => throw new \Exception(sprintf('Transformation for field %s is not implemented for %s', $field->value, Position::class)),
        };
    }
}
