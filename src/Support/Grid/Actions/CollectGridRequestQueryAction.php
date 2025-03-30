<?php

namespace Support\Grid\Actions;

use Illuminate\Http\Request;
use Lorisleiva\Actions\Action;
use Support\Grid\Data\Query\GridRequestQuery;
use Support\Grid\Enums\OrderEnum;
use Support\Grid\Enums\PerPageEnum;

class CollectGridRequestQueryAction extends Action
{
    public function handle(Request $request): GridRequestQuery
    {
        $searchQuery = $request->get('searchQuery');
        $page = (int) $request->get('page', 1);
        $sort = $request->get('sort', []);
        $perPage = $request->get('perPage', PerPageEnum::default()->value);

        return GridRequestQuery::from([
            'page' => $page < 0 ? 1 : $page,
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
