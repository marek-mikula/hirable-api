<?php

declare(strict_types=1);

namespace Domain\Company\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Company\Http\Requests\CompanyShowRequest;
use Domain\Company\Http\Requests\CompanyUpdateRequest;
use Domain\Company\Http\Resources\CompanyResource;
use Domain\Company\UseCases\UpdateCompanyUseCase;
use Illuminate\Http\JsonResponse;

class CompanyController extends ApiController
{
    public function show(CompanyShowRequest $request): JsonResponse
    {
        $company = $request->user()->loadMissing('company')->company;

        $company->loadMissing('benefits');

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'company' => new CompanyResource($company),
        ]);
    }

    public function update(CompanyUpdateRequest $request): JsonResponse
    {
        $company = UpdateCompanyUseCase::make()->handle($request->user(), $request->getValues());

        $company->loadMissing('benefits');

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'company' => new CompanyResource($company),
        ]);
    }
}
