<?php

declare(strict_types=1);

namespace Domain\Company\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use App\Http\Resources\Collections\PaginatedResourceCollection;
use Domain\Company\Http\Requests\CompanyContactDeleteRequest;
use Domain\Company\Http\Requests\CompanyContactIndexRequest;
use Domain\Company\Http\Requests\CompanyContactStoreRequest;
use Domain\Company\Http\Requests\CompanyContactUpdateRequest;
use Domain\Company\Http\Resources\CompanyContactResource;
use Domain\Company\Models\Company;
use Domain\Company\Models\CompanyContact;
use Domain\Company\UseCases\CompanyContactDeleteUseCase;
use Domain\Company\UseCases\CompanyContactIndexUseCase;
use Domain\Company\UseCases\CompanyContactStoreUseCase;
use Domain\Company\UseCases\CompanyContactUpdateUseCase;
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
            'contacts' => new PaginatedResourceCollection(CompanyContactResource::class, $contacts),
        ]);
    }

    public function store(CompanyContactStoreRequest $request, Company $company): JsonResponse
    {
        $contact = CompanyContactStoreUseCase::make()->handle($company, $request->toData());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'contact' => new CompanyContactResource($contact),
        ]);
    }

    public function update(CompanyContactUpdateRequest $request, Company $company, CompanyContact $contact): JsonResponse
    {
        $contact = CompanyContactUpdateUseCase::make()->handle($contact, $request->toData());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'contact' => new CompanyContactResource($contact),
        ]);
    }

    public function delete(CompanyContactDeleteRequest $request, Company $company, CompanyContact $contact): JsonResponse
    {
        CompanyContactDeleteUseCase::make()->handle($contact);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS);
    }
}
