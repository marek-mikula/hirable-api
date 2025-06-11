<?php

declare(strict_types=1);

namespace Support\Grid\Actions;

use App\Actions\Action;
use App\Http\Requests\Request;
use Support\Grid\Data\Query\GridRequestQuery;
use Support\Grid\Enums\OrderEnum;
use Support\Grid\Enums\PerPageEnum;

class CollectGridRequestQueryAction extends Action
{
    public function handle(Request $request): GridRequestQuery
    {
        $searchQuery = $request->string('searchQuery');
        $sort = $request->get('sort', []);
        $page = $request->integer('page', 1);
        $perPage = $request->integer('perPage', PerPageEnum::default()->value);

        return GridRequestQuery::from([
            'page' => max($page, 1),
            'perPage' => $this->validatePerPage($perPage),
            'searchQuery' => empty($searchQuery) ? null : (string) $searchQuery,
            'sort' => is_array($sort) ? array_filter(array_map([$this, 'mapSort'], $sort)) : [],
        ]);
    }

    private function validatePerPage(mixed $value): PerPageEnum
    {
        return PerPageEnum::tryFrom($value) ?? PerPageEnum::default();
    }

    private function mapSort(mixed $order): ?OrderEnum
    {
        return OrderEnum::tryFrom($order);
    }
}
