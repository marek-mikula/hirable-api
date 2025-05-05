<?php

declare(strict_types=1);

namespace Support\Classifier\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Support\Classifier\Enums\ClassifierTypeEnum;
use Support\Classifier\Http\Requests\ClassifierListRequest;
use Support\Classifier\Http\Resources\Collections\ClassifierCollection;
use Support\Classifier\UseCases\GetClassifierListUseCase;

class ClassifierController extends ApiController
{
    public function getList(ClassifierListRequest $request, ClassifierTypeEnum $type): JsonResponse
    {
        $list = GetClassifierListUseCase::make()->handle($type);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'classifiers' => new ClassifierCollection($list),
        ]);
    }
}
