<?php

declare(strict_types=1);

namespace Support\Classifier\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Support\Classifier\Enums\ClassifierTypeEnum;
use Support\Classifier\Http\Requests\ClassifierIndexRequest;
use Support\Classifier\Http\Requests\ClassifierListRequest;
use Support\Classifier\Http\Resources\Collections\ClassifierCollection;
use Support\Classifier\UseCases\ClassifierIndexUseCase;
use Support\Classifier\UseCases\ClassifierListUseCase;

class ClassifierController extends ApiController
{
    public function index(ClassifierIndexRequest $request): JsonResponse
    {
        $index = ClassifierIndexUseCase::make()->handle($request->getTypes());

        $index = array_map(fn (Collection $classifiers) => new ClassifierCollection($classifiers), $index);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'classifiers' => $index,
        ]);
    }

    public function list(ClassifierListRequest $request, ClassifierTypeEnum $type): JsonResponse
    {
        $list = ClassifierListUseCase::make()->handle($type);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'classifiers' => new ClassifierCollection($list),
        ]);
    }
}
