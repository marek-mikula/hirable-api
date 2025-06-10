<?php

declare(strict_types=1);

namespace Domain\Company\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Company\Http\Requests\CompanyContactIndexRequest;
use Domain\Company\Http\Requests\CompanyContactStoreRequest;
use Domain\Company\Http\Resources\Collection\CompanyContactPaginatedCollection;
use Domain\Company\Http\Resources\CompanyContactResource;
use Domain\Company\Models\Company;
use Domain\Company\UseCases\CompanyContactIndexUseCase;
use Domain\Company\UseCases\CompanyContactStoreUseCase;
use Illuminate\Http\JsonResponse;
use Support\Grid\Actions\SaveGridRequestQueryAction;
use Support\Grid\Enums\GridEnum;

use function Illuminate\Support\defer;

class CompanyContactController extends ApiController
{
    public function index(CompanyContactIndexRequest $request, Company $company): JsonResponse
    {
        $user = $request->user();

        $gridQuery = $request->getGridQuery();

        $contacts = CompanyContactIndexUseCase::make()->handle($company, $gridQuery);

        defer(fn () => SaveGridRequestQueryAction::make()->handle($user, GridEnum::COMPANY_CONTACT, $gridQuery));

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'contacts' => new CompanyContactPaginatedCollection($contacts),
        ]);
    }

    public function store(CompanyContactStoreRequest $request, Company $company): JsonResponse
    {
        $contact = CompanyContactStoreUseCase::make()->handle($company, $request->toData());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'contact' => new CompanyContactResource($contact),
        ]);
    }
}
