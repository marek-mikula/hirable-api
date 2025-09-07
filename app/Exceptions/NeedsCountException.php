<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Http\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class NeedsCountException extends \Exception
{
    /**
     * @param  class-string<Resource>  $resource
     * @param  class-string<Model>  $model
     * @param  string|string[]  $counts
     */
    public function __construct(string $resource, string $model, string|array $counts)
    {
        $message = sprintf(
            'Model %s needs to have loaded counts (%s) in %s.',
            $model,
            collect(Arr::wrap($counts))->implode(', '),
            $resource
        );

        parent::__construct(message: $message);
    }
}
