<?php

declare(strict_types=1);

namespace Support\Classifier\Actions;

use App\Actions\Action;
use Illuminate\Support\Collection;
use Support\Classifier\Data\ClassifierData;
use Support\Classifier\Enums\ClassifierTypeEnum;
use Support\Classifier\Services\ClassifierTranslateService;

/**
 * Takes raw value of classifiers, like 'full_time', and
 * transforms it to data object, which can be then parsed
 * in resource.
 */
class ToClassifierAction extends Action
{
    public function __construct(
        private readonly ClassifierTranslateService $translateService,
    ) {
    }

    /**
     * @return Collection<ClassifierData>|ClassifierData
     */
    public function handle(mixed $raw, ClassifierTypeEnum $type): Collection|ClassifierData
    {
        if (is_array($raw)) {
            $raw = collect($raw);
        }

        // if value is some kind of list, transform
        // the values recursively
        if ($raw instanceof Collection) {
            return $raw->map(fn (mixed $item) => $this->handle($item, $type));
        }

        if (is_string($raw)) {
            return ClassifierData::from(['value' => $raw, 'label' => $this->translateService->translateValue($type, $raw)]);
        }

        throw new \Exception(sprintf('Cannot transform raw classifier value of type %s.', gettype($raw)));
    }
}
