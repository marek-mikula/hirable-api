<?php

declare(strict_types=1);

namespace Domain\Search\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use App\Http\Resources\Collections\ResourceCollection;
use Domain\Position\Models\Position;
use Domain\Search\Http\Requests\SearchCompanyContactsRequest;
use Domain\Search\Http\Requests\SearchCompanyUsersRequest;
use Domain\Search\Http\Requests\SearchPositionUsersRequest;
use Domain\Search\Http\Resources\SearchResultResource;
use Domain\Search\UseCases\SearchCompanyContactsUseCase;
use Domain\Search\UseCases\SearchCompanyUsersUseCase;
use Domain\Search\UseCases\SearchPositionUsersUseCase;
use Illuminate\Http\JsonResponse;

class SearchController extends ApiController
{
    public function companyUsers(SearchCompanyUsersRequest $request): JsonResponse
    {
        $results = SearchCompanyUsersUseCase::make()->handle($request->user(), $request->toData(), $request->ignoreAuth(), $request->roles());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'results' => new ResourceCollection(SearchResultResource::class, $results),
        ]);
    }

    public function companyContacts(SearchCompanyContactsRequest $request): JsonResponse
    {
        $results = SearchCompanyContactsUseCase::make()->handle($request->user(), $request->toData());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'results' => new ResourceCollection(SearchResultResource::class, $results),
        ]);
    }

    public function positionUsers(SearchPositionUsersRequest $request, Position $position): JsonResponse
    {
        $results = SearchPositionUsersUseCase::make()->handle($request->user(), $position, $request->toData(), $request->ignoreAuth(), $request->roles());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'results' => new ResourceCollection(SearchResultResource::class, $results),
        ]);
    }
}
