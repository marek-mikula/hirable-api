<?php

declare(strict_types=1);

namespace Domain\Company\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Company\Http\Requests\CompanyUpdateRequest;
use Domain\Company\Http\Resources\CompanyResource;
use Domain\Company\UseCases\UpdateCompanyUseCase;
use Illuminate\Http\JsonResponse;

class CompanyController extends ApiController
{
    public function update(CompanyUpdateRequest $request): JsonResponse
    {
        $company = UpdateCompanyUseCase::make()->handle($request->user(), $request->getValues());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'company' => new CompanyResource($company),
        ]);
    }
}
