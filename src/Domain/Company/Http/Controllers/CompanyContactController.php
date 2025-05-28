<?php

declare(strict_types=1);

namespace Domain\Company\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Company\Http\Requests\CompanyContactIndexRequest;
use Domain\Company\Http\Requests\CompanyContactStoreRequest;
use Domain\Company\Http\Resources\Collection\CompanyContactPaginatedCollection;
use Domain\Company\Http\Resources\CompanyContactResource;
use Domain\Company\UseCases\GetCompanyContactsForIndexUseCase;
use Domain\Company\UseCases\StoreCompanyContactUseCase;
use Illuminate\Http\JsonResponse;
use Support\Grid\Actions\SaveGridRequestQueryAction;
use Support\Grid\Enums\GridEnum;

use function Illuminate\Support\defer;

class CompanyContactController extends ApiController
{
    public function index(CompanyContactIndexRequest $request): JsonResponse
    {
        $user = $request->user();

        $gridQuery = $request->getGridQuery();

        $contacts = GetCompanyContactsForIndexUseCase::make()->handle($user, $gridQuery);

        defer(fn () => SaveGridRequestQueryAction::make()->handle($user, GridEnum::COMPANY_CONTACT, $gridQuery));

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'contacts' => new CompanyContactPaginatedCollection($contacts),
        ]);
    }

    public function store(CompanyContactStoreRequest $request): JsonResponse
    {
        $contact = StoreCompanyContactUseCase::make()->handle($request->user(), $request->toData());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'contact' => new CompanyContactResource($contact),
        ]);
    }
}
