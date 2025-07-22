<?php

declare(strict_types=1);

namespace Domain\AI\Context\ValueSerializers;

use App\Enums\LanguageEnum;
use Illuminate\Support\Arr;
use Support\Classifier\Enums\ClassifierTypeEnum;
use Support\Classifier\Services\ClassifierTranslateService;

class ClassifierSerializer implements ValueSerializer
{
    public function __construct(
        private readonly ClassifierTranslateService $classifierTranslateService,
    ) {
    }

    /**
     * @param ?string $value
     */
    public function serialize(mixed $value, array $config): ?string
    {
        $type = ClassifierTypeEnum::from((string) Arr::get($config, 'classifier'));

        return empty($value) ? null : $this->classifierTranslateService->translateValue($type, (string) $value, LanguageEnum::EN->value);
    }
}
