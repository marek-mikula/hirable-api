<?php

declare(strict_types=1);

namespace App\Exceptions;

final class RepositoryException extends \Exception
{
    final public function __construct(string $model, string $action)
    {
        parent::__construct(sprintf('Model %s could not have been %s!', $model, $action));
    }

    public static function saved(string $model): static
    {
        return new static(model: $model, action: 'saved');
    }

    public static function stored(string $model): static
    {
        return new static(model: $model, action: 'stored');
    }

    public static function updated(string $model): static
    {
        return new static(model: $model, action: 'updated');
    }

    public static function deleted(string $model): static
    {
        return new static(model: $model, action: 'deleted');
    }

    public static function forceDeleted(string $model): static
    {
        return new static(model: $model, action: 'force deleted');
    }
}
