<?php

declare(strict_types=1);

namespace Domain\AI\Services;

use App\Services\Service;
use Domain\AI\Context\Enums\FieldTypeEnum;
use Domain\AI\Context\ValueSerializers\ValueSerializer;
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

    public function getModelContextConfig(Model $model): array
    {
        $config = (array) config(sprintf('ai.context.models.%s', $model::class));

        throw_if(empty($config), new \Exception(sprintf('Undefined context definition for class %s.', $model::class)));

        return $config;
    }

    /**
     * @return class-string<ValueSerializer>
     */
    public function getModelContextSerializer(FieldTypeEnum $type): string
    {
        return (string) config(sprintf('ai.context.serializers.%s', $type->value));
    }
}
