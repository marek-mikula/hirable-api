<?php

declare(strict_types=1);

namespace App\Http\Requests\Traits;

use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;
use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;

/**
 * @mixin Request
 */
trait ValidationFailsWithStatus
{
    protected ResponseCodeEnum $code = ResponseCodeEnum::CLIENT_ERROR;

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpException(responseCode: $this->code);
    }
}
