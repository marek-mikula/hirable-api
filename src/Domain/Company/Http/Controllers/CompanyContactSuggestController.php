<?php

declare(strict_types=1);

namespace Domain\Company\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Company\Http\Requests\CompanyContactSuggestCompaniesRequest;
use Domain\Company\Repositories\CompanyContactSuggestRepositoryInterface;
use Illuminate\Http\JsonResponse;

class CompanyContactSuggestController extends ApiController
{
    public function __construct(
        private readonly CompanyContactSuggestRepositoryInterface $companyContactSuggestRepository,
    ) {
    }

    public function companies(CompanyContactSuggestCompaniesRequest $request): JsonResponse
    {
        $user = $request->user();

        $company = $user->loadMissing('company')->company;

        $values = $this->companyContactSuggestRepository->suggestCompanies($company, $request->getQuery());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'values' => $values
        ]);
    }
}
