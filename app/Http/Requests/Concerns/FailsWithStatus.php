<?php

declare(strict_types=1);

namespace App\Http\Requests\Concerns;

use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;
use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;

/**
 * @mixin Request
 */
trait FailsWithStatus
{
    protected ResponseCodeEnum $code = ResponseCodeEnum::CLIENT_ERROR;

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpException(responseCode: $this->code);
    }
}
