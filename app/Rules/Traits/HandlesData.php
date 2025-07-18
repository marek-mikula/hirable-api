<?php

declare(strict_types=1);

namespace App\Rules\Traits;

trait HandlesData
{
    protected array $data = [];

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}
