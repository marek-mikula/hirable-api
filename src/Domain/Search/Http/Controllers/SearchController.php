<?php

declare(strict_types=1);

namespace Domain\Search\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Search\Http\Requests\SearchCompanyContactsRequest;
use Domain\Search\Http\Requests\SearchAdvertisementPositionsRequest;
use Domain\Search\Http\Requests\SearchCompanyUsersRequest;
use Domain\Search\Http\Resources\Collection\SearchResultCollection;
use Domain\Search\UseCases\SearchCompanyContactsUseCase;
use Domain\Search\UseCases\SearchAdvertisementPositionsUseCase;
use Domain\Search\UseCases\SearchCompanyUsersUseCase;
use Illuminate\Http\JsonResponse;

class SearchController extends ApiController
{
    public function companyUsers(SearchCompanyUsersRequest $request): JsonResponse
    {
        $results = SearchCompanyUsersUseCase::make()->handle($request->user(), $request->toData(), $request->ignoreAuth());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'results' => new SearchResultCollection($results),
        ]);
    }

    public function companyContacts(SearchCompanyContactsRequest $request): JsonResponse
    {
        $results = SearchCompanyContactsUseCase::make()->handle($request->user(), $request->toData());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'results' => new SearchResultCollection($results),
        ]);
    }

    public function advertisementPositions(SearchAdvertisementPositionsRequest $request): JsonResponse
    {
        $results = SearchAdvertisementPositionsUseCase::make()->handle($request->user(), $request->toData());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'results' => new SearchResultCollection($results),
        ]);
    }
}
