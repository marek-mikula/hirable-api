<?php

declare(strict_types=1);

namespace Domain\Application\Http\Controllers;

use Domain\Application\Http\Requests\ApplicationApplyRequest;
use Illuminate\Http\JsonResponse;

class ApplicationApplyController
{
    public function __invoke(ApplicationApplyRequest $request): JsonResponse
    {
        dump($request->toData()->toArray());
    }
}
