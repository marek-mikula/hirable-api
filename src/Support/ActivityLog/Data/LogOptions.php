<?php

declare(strict_types=1);

namespace Support\ActivityLog\Data;

class LogOptions
{
    // if model updates where no changes
    // were made should be logged
    private bool $logEmptyUpdates = false;

    // changes on these attributes will
    // be logged when updating model
    private array $updatedAttributes = [];

    // these attributes will be logged when
    // creating model
    //
    // if null, $updatedAttributes are used
    private ?array $createdAttributes = null;

    // list of events to log automatically
    private array $events = [
        'created',
        'updated',
        'deleted',
        'restored',
    ];

    public static function defaults(): static
    {
        return new static();
    }

    public function logUpdatedAttributes(array $updatedAttributes): static
    {
        $this->updatedAttributes = $updatedAttributes;

        return $this;
    }

    public function logCreatedAttributes(?array $createAttributes): static
    {
        $this->createdAttributes = $createAttributes;

        return $this;
    }

    public function logEvents(array $events): static
    {
        $this->events = $events;

        return $this;
    }

    public function logEmptyUpdates(bool $state = true): static
    {
        $this->logEmptyUpdates = $state;

        return $this;
    }

    public function getUpdatedAttributes(): array
    {
        return $this->updatedAttributes;
    }

    public function getCreatedAttributes(): array
    {
        return $this->createdAttributes ?? $this->updatedAttributes;
    }

    public function shouldLogEmptyUpdates(): bool
    {
        return $this->logEmptyUpdates;
    }

    public function hasEvent(string $event): bool
    {
        return in_array($event, $this->events);
    }
}
