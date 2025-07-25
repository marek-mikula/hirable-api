<?php

declare(strict_types=1);

namespace Domain\Position\Http\Controllers;

use App\Http\Controllers\ApiController;
use Domain\Position\Http\Request\PositionKanbanRequest;
use Domain\Position\Models\Position;
use Illuminate\Http\JsonResponse;

class PositionKanbanController extends ApiController
{
    public function __invoke(PositionKanbanRequest $request, Position $position): JsonResponse
    {
        return $this->jsonResponse();
    }
}
