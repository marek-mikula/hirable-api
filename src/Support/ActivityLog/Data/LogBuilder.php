<?php

namespace Support\ActivityLog\Data;

use Illuminate\Database\Eloquent\Model;
use Support\ActivityLog\Services\ActivityLogCauserResolver;
use Support\ActivityLog\Services\ActivityLogManager;
use Support\ActivityLog\Services\ActivityLogSaver;

class LogBuilder
{
    private array $data = [];

    private ?Model $causer = null;

    private function __construct(
        private readonly Model $subject,
        private readonly string $action,
    ) {
    }

    public static function create(Model $subject, string $action): static
    {
        return new static($subject, $action);
    }

    public function withData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function withCauser(?Model $causer): static
    {
        $this->causer = $causer;

        return $this;
    }

    public function withDefaultCauser(): static
    {
        /** @var ActivityLogCauserResolver $resolver */
        $resolver = app(ActivityLogCauserResolver::class);

        return $this->withCauser($resolver->getCauser());
    }

    /**
     * Saves the log immediately to database
     */
    public function save(): Log
    {
        /** @var ActivityLogSaver $saver */
        $saver = app(ActivityLogSaver::class);

        $saver->save([$log = $this->createLog()], forceSync: true);

        return $log;
    }

    /**
     * Pushes log to the log stack
     */
    public function add(): Log
    {
        /** @var ActivityLogManager $manager */
        $manager = app(ActivityLogManager::class);

        $manager->addLog($log = $this->createLog());

        return $log;
    }

    /**
     * Creates the log object
     */
    public function get(): Log
    {
        return $this->createLog();
    }

    private function createLog(): Log
    {
        return Log::from([
            'causer' => $this->causer ? $this->causer::class : null,
            'causerId' => $this->causer?->getKey(),
            'subject' => $this->subject::class,
            'subjectId' => $this->subject->getKey(),
            'action' => $this->action,
            'data' => $this->data,
        ]);
    }
}
