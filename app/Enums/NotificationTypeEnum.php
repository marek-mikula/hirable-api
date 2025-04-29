<?php

declare(strict_types=1);

namespace App\Enums;

enum NotificationTypeEnum: string
{
    case REGISTER_REQUEST = 'register:request';
    case REGISTER_REGISTERED = 'register:registered';

    case VERIFICATION_VERIFY_EMAIL = 'verification:verify-email';
    case VERIFICATION_EMAIL_VERIFIED = 'verification:email-verified';

    case PASSWORD_RESET_REQUEST = 'password:reset-request';
    case PASSWORD_CHANGED = 'password:changed';

    case INVITATION_SENT = 'invitation:sent';
    case INVITATION_ACCEPTED = 'invitation:accepted';

    public function getCategory(): NotificationCategoryEnum
    {
        return match ($this) {
            self::INVITATION_SENT,
            self::REGISTER_REQUEST,
            self::REGISTER_REGISTERED,
            self::VERIFICATION_VERIFY_EMAIL,
            self::VERIFICATION_EMAIL_VERIFIED,
            self::PASSWORD_RESET_REQUEST,
            self::PASSWORD_CHANGED => NotificationCategoryEnum::CRUCIAL,

            self::INVITATION_ACCEPTED => NotificationCategoryEnum::APPLICATION,
        };
    }
}
