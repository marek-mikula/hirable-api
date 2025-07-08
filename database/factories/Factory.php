<?php

declare(strict_types=1);

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;
use Illuminate\Database\Eloquent\Model;

abstract class Factory extends BaseFactory
{
    protected bool $isMaking = false;

    protected bool $isCreating = false;

    public function ofCreatedAt(Carbon $timestamp): static
    {
        return $this->state(fn (array $attributes) => [
            'created_at' => $timestamp,
        ]);
    }

    public function ofUpdateAt(Carbon $timestamp): static
    {
        return $this->state(fn (array $attributes) => [
            'updated_at' => $timestamp,
        ]);
    }

    public function ofDeletedAt(?Carbon $timestamp): static
    {
        return $this->state(fn (array $attributes) => [
            'deleted_at' => $timestamp,
        ]);
    }

    public function create($attributes = [], ?Model $parent = null)
    {
        $this->isCreating = true;

        $result = $this->model::withoutEvents(function () use ($attributes, $parent) {
            return parent::create($attributes, $parent);
        });

        $this->isCreating = false;

        return $result;
    }

    public function make($attributes = [], ?Model $parent = null)
    {
        if ($this->isCreating) {
            return parent::make($attributes, $parent);
        }

        $this->isMaking = true;

        $result = parent::make($attributes, $parent);

        $this->isMaking = false;

        return $result;
    }
}
