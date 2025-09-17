<?php

declare(strict_types=1);

namespace Domain\Company\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Company\Http\Requests\CompanyShowRequest;
use Domain\Company\Http\Requests\CompanyUpdateRequest;
use Domain\Company\Http\Resources\CompanyResource;
use Domain\Company\Models\Company;
use Domain\Company\UseCases\CompanyUpdateUseCase;
use Illuminate\Http\JsonResponse;

final class CompanyController extends ApiController
{
    public function show(CompanyShowRequest $request, Company $company): JsonResponse
    {
        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'company' => new CompanyResource($company),
        ]);
    }

    public function update(CompanyUpdateRequest $request, Company $company): JsonResponse
    {
        $company = CompanyUpdateUseCase::make()->handle($company, $request->getValues());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'company' => new CompanyResource($company),
        ]);
    }
}
