<?php

namespace Support\Grid\Data\Definition;

use Spatie\LaravelData\Data;

class GridColumnDefinition extends Data
{
    /**
     * @param  string  $key  unique string key to define the column name
     * @param  string  $label  the translated label for column
     * @param  bool  $enabled  if the column is enabled
     * @param  int|null  $width  width in pixels
     * @param  int|null  $minWidth  min. width in pixels
     * @param  int|null  $maxWidth  max. width in pixels
     * @param  bool  $allowToggle  if user can hide/show to the column
     * @param  bool  $allowSort  if user can sort by the column
     */
    public function __construct(
        public string $key,
        public string $label,
        public bool $enabled = true,
        public ?int $width = null,
        public ?int $minWidth = null,
        public ?int $maxWidth = null,
        public bool $allowToggle = true,
        public bool $allowSort = true,
    ) {
    }
}
