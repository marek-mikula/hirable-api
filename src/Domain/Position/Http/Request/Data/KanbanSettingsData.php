<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request\Data;

readonly class KanbanSettingsData
{
    /**
     * @param string[] $order
     */
    public function __construct(
        public array $order
    ) {
    }
}
