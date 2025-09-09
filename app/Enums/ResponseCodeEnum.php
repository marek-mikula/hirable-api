<?php

declare(strict_types=1);

namespace App\Enums;

enum ResponseCodeEnum
{
    // 2XX
    case SUCCESS;
    case CREATED;

    // 4XX
    case CLIENT_ERROR;
    case TOKEN_MISMATCH;
    case EMAIL_VERIFICATION_NEEDED;
    case CONTACT_PENDING_APPROVALS;
    case UNAUTHORIZED;
    case TOKEN_MISSING;
    case TOKEN_CORRUPTED;
    case TOKEN_INVALID;
    case GUEST_ONLY;
    case APPLICATION_ENDED;
    case APPLICATION_DUPLICATE;
    case STEP_EXISTS;
    case NOT_SUFFICIENT_STEP;
    case ACTION_EXISTS;
    case UNAUTHENTICATED;
    case NOT_FOUND;
    case METHOD_NOT_ALLOWED;
    case INVALID_CONTENT;
    case INVALID_CREDENTIALS;
    case INVITATION_EXISTS;
    case INVITATION_USER_EXISTS;
    case TOO_MANY_ATTEMPTS;
    case REGISTRATION_ALREADY_REQUESTED;
    case RESET_ALREADY_REQUESTED;

    // 5XX
    case SERVER_ERROR;

    public function getStatusCode(): int
    {
        return match ($this) {
            // 200
            self::SUCCESS => 200,

            // 201
            self::CREATED => 201,

            // 400
            self::TOKEN_MISMATCH,
            self::EMAIL_VERIFICATION_NEEDED,
            self::CONTACT_PENDING_APPROVALS,
            self::APPLICATION_DUPLICATE,
            self::STEP_EXISTS,
            self::NOT_SUFFICIENT_STEP,
            self::ACTION_EXISTS,
            self::CLIENT_ERROR => 400,

            // 401
            self::UNAUTHENTICATED => 401,

            // 403
            self::GUEST_ONLY,
            self::TOKEN_MISSING,
            self::TOKEN_CORRUPTED,
            self::TOKEN_INVALID,
            self::APPLICATION_ENDED,
            self::UNAUTHORIZED => 403,

            // 404
            self::NOT_FOUND => 404,

            // 405
            self::METHOD_NOT_ALLOWED => 405,

            // 422
            self::INVITATION_EXISTS,
            self::INVITATION_USER_EXISTS,
            self::INVALID_CREDENTIALS,
            self::INVALID_CONTENT => 422,

            // 429
            self::REGISTRATION_ALREADY_REQUESTED,
            self::RESET_ALREADY_REQUESTED,
            self::TOO_MANY_ATTEMPTS => 429,

            // 500
            self::SERVER_ERROR => 500,
        };
    }
}
