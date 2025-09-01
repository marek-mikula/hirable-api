<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class NeedsRelationshipException extends \Exception
{
    /**
     * @param  class-string<JsonResource>  $resource
     * @param  class-string<Model>  $model
     * @param  string|string[]  $relationships
     */
    public function __construct(string $resource, string $model, string|array $relationships)
    {
        $message = sprintf(
            'Model %s needs to have loaded relationships (%s) in %s.',
            $model,
            collect(Arr::wrap($relationships))->implode(', '),
            $resource
        );

        parent::__construct(message: $message);
    }
}
