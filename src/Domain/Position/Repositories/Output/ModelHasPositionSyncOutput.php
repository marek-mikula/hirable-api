<?php

declare(strict_types=1);

namespace Domain\Position\Repositories\Output;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

readonly class ModelHasPositionSyncOutput
{
    /**
     * @param Collection<Model> $stored
     * @param Collection<Model> $deleted
     */
    public function __construct(
        public Collection $stored,
        public Collection $deleted,
    ) {
    }
}
