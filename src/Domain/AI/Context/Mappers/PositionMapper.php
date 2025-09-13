<?php

declare(strict_types=1);

namespace Domain\AI\Context\Mappers;

use App\Enums\LanguageEnum;
use Domain\Position\Enums\PositionFieldEnum;
use Domain\Position\Models\Position;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Support\Classifier\Enums\ClassifierTypeEnum;
use Support\Classifier\Services\ClassifierTranslateService;

class PositionMapper extends ModelMapper
{
    public function __construct(
        private readonly ClassifierTranslateService $classifierTranslateService,
    ) {
    }

    /**
     * @param Position $model
     */
    public function mapField(Model $model, string $field): mixed
    {
        $field = PositionFieldEnum::from($field);

        return match ($field) {
            PositionFieldEnum::NAME => $model->name,
            PositionFieldEnum::DEPARTMENT => $model->department,
            PositionFieldEnum::FIELD => $model->field ? $this->classifierTranslateService->translateValue(ClassifierTypeEnum::FIELD, $model->field, LanguageEnum::EN->value) : null,
            PositionFieldEnum::WORKLOADS => array_map(fn (string $value): string => $this->classifierTranslateService->translateValue(ClassifierTypeEnum::WORKLOAD, $value, LanguageEnum::EN->value), $model->workloads),
            PositionFieldEnum::EMPLOYMENT_RELATIONSHIPS => array_map(fn (string $value): string => $this->classifierTranslateService->translateValue(ClassifierTypeEnum::EMPLOYMENT_RELATIONSHIP, $value, LanguageEnum::EN->value), $model->employment_relationships),
            PositionFieldEnum::EMPLOYMENT_FORMS => array_map(fn (string $value): string => $this->classifierTranslateService->translateValue(ClassifierTypeEnum::EMPLOYMENT_FORM, $value, LanguageEnum::EN->value), $model->employment_forms),
            PositionFieldEnum::JOB_SEATS_NUM => (string) $model->job_seats_num,
            PositionFieldEnum::DESCRIPTION => (string) $model->description,
            PositionFieldEnum::SALARY_FROM => (string) $model->salary_from,
            PositionFieldEnum::SALARY_TO => !empty($model->salary_to) ? (string) $model->salary_to : null,
            PositionFieldEnum::SALARY_TYPE => $this->classifierTranslateService->translateValue(ClassifierTypeEnum::SALARY_TYPE, $model->salary_type, LanguageEnum::EN->value),
            PositionFieldEnum::SALARY_FREQUENCY => $this->classifierTranslateService->translateValue(ClassifierTypeEnum::SALARY_FREQUENCY, $model->salary_frequency, LanguageEnum::EN->value),
            PositionFieldEnum::SALARY_CURRENCY => $this->classifierTranslateService->translateValue(ClassifierTypeEnum::CURRENCY, $model->salary_currency, LanguageEnum::EN->value),
            PositionFieldEnum::SALARY_VAR => !empty($model->salary_var) ? (string) $model->salary_var : null,
            PositionFieldEnum::BENEFITS => array_map(fn (string $value): string => $this->classifierTranslateService->translateValue(ClassifierTypeEnum::BENEFIT, $value, LanguageEnum::EN->value), $model->benefits),
            PositionFieldEnum::MIN_EDUCATION_LEVEL => $model->min_education_level ? $this->classifierTranslateService->translateValue(ClassifierTypeEnum::EDUCATION_LEVEL, $model->min_education_level, LanguageEnum::EN->value) : null,
            PositionFieldEnum::EDUCATION_FIELD => !empty($model->education_field) ? (string) $model->education_field : null,
            PositionFieldEnum::SENIORITY => array_map(fn (string $value): string => $this->classifierTranslateService->translateValue(ClassifierTypeEnum::SENIORITY, $value, LanguageEnum::EN->value), $model->seniority),
            PositionFieldEnum::EXPERIENCE => !empty($model->experience) ? (string) $model->experience : null,
            PositionFieldEnum::HARD_SKILLS => !empty($model->hard_skills) ? (string) $model->hard_skills : null,
            PositionFieldEnum::ORGANISATION_SKILLS => (string) $model->organisation_skills,
            PositionFieldEnum::TEAM_SKILLS => (string) $model->team_skills,
            PositionFieldEnum::TIME_MANAGEMENT => (string) $model->time_management,
            PositionFieldEnum::COMMUNICATION_SKILLS => (string) $model->communication_skills,
            PositionFieldEnum::LEADERSHIP => (string) $model->leadership,
            PositionFieldEnum::LANGUAGE_REQUIREMENTS => array_map(fn (array $item): array => [
                'language' => $this->classifierTranslateService->translateValue(ClassifierTypeEnum::LANGUAGE, Arr::get($item, 'language'), LanguageEnum::EN->value),
                'level' => $this->classifierTranslateService->translateValue(ClassifierTypeEnum::LANGUAGE_LEVEL, Arr::get($item, 'level'), LanguageEnum::EN->value),
            ], $model->language_requirements),
            PositionFieldEnum::TAGS => $model->tags,
            default => throw new \Exception(sprintf('Mapping for field %s is not implemented for %s', $field->value, $model::class)),
        };
    }
}
