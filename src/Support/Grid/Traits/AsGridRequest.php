<?php

declare(strict_types=1);

namespace Support\Grid\Traits;

use App\Http\Requests\Request;
use Support\Grid\Actions\CollectGridRequestQueryAction;
use Support\Grid\Data\Query\GridRequestQuery;

/**
 * @mixin Request
 */
trait AsGridRequest
{
    public function getGridQuery(): GridRequestQuery
    {
        return CollectGridRequestQueryAction::make()->handle($this);
    }
}
