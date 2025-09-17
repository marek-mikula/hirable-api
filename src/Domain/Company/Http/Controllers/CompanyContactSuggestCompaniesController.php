<?php

declare(strict_types=1);

namespace Domain\Company\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Company\Http\Requests\CompanyContactSuggestCompaniesRequest;
use Domain\Company\Models\Company;
use Domain\Company\Repositories\CompanyContactSuggestRepositoryInterface;
use Illuminate\Http\JsonResponse;

final class CompanyContactSuggestCompaniesController extends ApiController
{
    public function __construct(
        private readonly CompanyContactSuggestRepositoryInterface $companyContactSuggestRepository,
    ) {
    }

    public function __invoke(CompanyContactSuggestCompaniesRequest $request, Company $company): JsonResponse
    {
        $values = $this->companyContactSuggestRepository->suggestCompanies($company, $request->getQuery());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'values' => $values
        ]);
    }
}
