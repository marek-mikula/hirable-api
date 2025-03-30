<?php

namespace Support\ActivityLog\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Support\ActivityLog\Data\LogOptions;

class ActivityLogHandler
{
    public function __construct(private readonly ActivityLogManager $manager) {}

    public static function instance(): static
    {
        return app(static::class);
    }

    public function handleCreated(LogOptions $options, Model $model): void
    {
        if (! $this->manager->isEnabled()) {
            return;
        }

        $allowedAttributes = $options->getCreatedAttributes();

        $data = $model->toArray();

        foreach (array_keys($data) as $attribute) {
            if (! in_array($attribute, $allowedAttributes)) {
                Arr::forget($data, $attribute);
            }
        }

        $this->manager->log($model, action: 'created')
            ->withData($data)
            ->withDefaultCauser()
            ->add();
    }

    public function handleUpdated(LogOptions $options, Model $model): void
    {
        if (! $this->manager->isEnabled()) {
            return;
        }

        $allowedAttributes = $options->getUpdatedAttributes();

        $data = [];

        foreach ($model->getChanges() as $attribute => $value) {
            if (! in_array($attribute, $allowedAttributes)) {
                continue;
            }

            $data[$attribute] = [
                'old' => $model->getOriginal($attribute),
                'new' => $model->{$attribute},
            ];
        }

        if (! $options->shouldLogEmptyUpdates() && empty($data)) {
            return;
        }

        $this->manager->log($model, action: 'updated')
            ->withData($data)
            ->withDefaultCauser()
            ->add();
    }

    public function handleDeleted(LogOptions $options, Model $model, bool $force = false): void
    {
        if (! $this->manager->isEnabled()) {
            return;
        }

        $usesSoftDeletes = usesSoftDeletes($model::class);

        // when force deleting soft-delete models,
        // Laravel also triggers `deleted` event
        // => do not log this event, log only
        // force deleted event
        if ($usesSoftDeletes && ! $force && $model->isForceDeleting()) {
            return;
        }

        $data = [];

        // when deleting model with soft deletes, add
        // info if the deletion was soft or forced
        if ($usesSoftDeletes) {
            $data['soft'] = $force ? 0 : 1;
        }

        $this->manager->log($model, action: 'deleted')
            ->withData($data)
            ->withDefaultCauser()
            ->add();
    }

    public function handleRestored(LogOptions $options, Model $model): void
    {
        if (! $this->manager->isEnabled()) {
            return;
        }

        $this->manager->log($model, action: 'restored')
            ->withData([])
            ->withDefaultCauser()
            ->add();
    }
}
