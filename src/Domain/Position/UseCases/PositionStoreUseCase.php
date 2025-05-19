<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Position\Http\Request\Data\PositionStoreData;
use Domain\Position\Models\Position;
use Domain\User\Models\User;

class PositionStoreUseCase extends UseCase
{
    public function handle(User $user, PositionStoreData $data): Position
    {
        dd($data->toArray());
    }
}
