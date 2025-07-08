<?php

declare(strict_types=1);

namespace Domain\Application\Services;

use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;
use App\Services\Service;
use Domain\Application\TokenProcessing\Data\TokenData;
use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Models\Position;

class ApplicationValidatorService extends Service
{
    public function validate(TokenData $tokenData): void
    {
        $this->validatePosition($tokenData->position);
    }

    private function validatePosition(Position $position): void
    {
        throw_if($position->state !== PositionStateEnum::OPENED, new HttpException(responseCode: ResponseCodeEnum::APPLICATION_ENDED));
    }
}
