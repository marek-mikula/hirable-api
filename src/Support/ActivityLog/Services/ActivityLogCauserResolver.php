<?php

namespace Support\ActivityLog\Services;

use Illuminate\Database\Eloquent\Model;

class ActivityLogCauserResolver
{
    private \Closure $defaultCauser;

    private \Closure $causer;

    public function __construct()
    {
        $defaultCauser = static fn () => auth()->user();

        // setup default causer
        $this->setDefaultCauserResolver($defaultCauser);

        // setup initial causer
        $this->setCauserResolver($defaultCauser);
    }

    /**
     * Gets information about resolver causer
     *
     * @return array{0:class-string<Model>|null,1:int|null}
     */
    public function getCauserInfo(): array
    {
        $causer = $this->getCauser();

        return $causer ? [$causer::class, $causer->getKey()] : [null, null];
    }

    public function getCauser(): ?Model
    {
        /** @var Model|null $causer */
        $causer = app()->call($this->causer);

        return $causer;
    }

    /**
     * Sets causer explicitly to given model
     */
    public function setCauser(?Model $causer): void
    {
        $this->setCauserResolver(static fn () => $causer);
    }

    /**
     * Sets causer to default defined causer resolver
     */
    public function resetCauser(): void
    {
        $this->setCauserResolver($this->defaultCauser);
    }

    /**
     * Sets default causer resolver
     */
    public function setDefaultCauserResolver(\Closure $callback): void
    {
        $this->defaultCauser = $callback;
    }

    /**
     * Sets current causer resolver
     */
    public function setCauserResolver(?\Closure $callback): void
    {
        $this->causer = $callback;
    }

    /**
     * Calls given callback with specific causer
     */
    public function withCauser(\Closure $callback, ?Model $causer): mixed
    {
        $previousCauser = $this->causer;

        $this->setCauser($causer);

        $result = call_user_func($callback);

        $this->setCauserResolver($previousCauser);

        return $result;
    }
}
