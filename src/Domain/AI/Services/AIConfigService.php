<?php

declare(strict_types=1);

namespace Domain\AI\Services;

use App\Services\Service;
use Domain\AI\Context\Mappers\ModelMapper;
use Domain\AI\Contracts\AIServiceInterface;
use Domain\AI\Scoring\Enums\ScoreCategoryEnum;
use Illuminate\Database\Eloquent\Model;

class AIConfigService extends Service
{
    /**
     * @return array<string,class-string<AIServiceInterface>>
     */
    public function getServices(): array
    {
        return (array) config('ai.services');
    }

    /**
     * @return class-string<AIServiceInterface>
     */
    public function getServiceClass(): string
    {
        $service = (string) config('ai.service');

        $services = $this->getServices();

        throw_if(!array_key_exists($service, $services), new \Exception(sprintf('Undefined AI service %s given.', $service)));

        return $services[$service];
    }

    /**
     * @return string[]
     */
    public function getScoreFiles(): array
    {
        return (array) config('ai.score.files');
    }

    public function getScoreBaseWeight(): int
    {
        return (int) config('ai.score.base_weight');
    }

    public function getScoreCategoryDescription(ScoreCategoryEnum $category): string
    {
        $description = config(sprintf('ai.score.category_descriptions.%s', $category->value));

        throw_if(empty($description), new \Exception(sprintf('Undefined category description for category %s.', $category->value)));

        return (string) $description;
    }

    /**
     * @return class-string<ModelMapper>
     */
    public function getModelMapper(Model|string $model): string
    {
        $class = is_string($model) ? $model : $model::class;

        $mapper = config(sprintf('ai.context.mappers.%s', $class));

        throw_if(empty($mapper), new \Exception(sprintf('Undefined context mapper for class %s.', $class)));

        return (string) $mapper;
    }

    public function getModelContextConfig(Model|string $model): array
    {
        $class = is_string($model) ? $model : $model::class;

        $config = config(sprintf('ai.context.models.%s', $class));

        throw_if(empty($config), new \Exception(sprintf('Undefined context for class %s.', $class)));

        return (array) $config;
    }
}
