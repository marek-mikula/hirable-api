<?php

declare(strict_types=1);

namespace Support\Notification\Enums;

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

    case POSITION_APPROVAL = 'position:approval';
    case POSITION_APPROVAL_REJECTED = 'position:approval-rejected';
    case POSITION_APPROVAL_APPROVED = 'position:approval-approved';
    case POSITION_APPROVAL_CANCELED = 'position:approval-canceled';

    public function getCategory(): NotificationCategoryEnum
    {
        return match ($this) {
            self::POSITION_APPROVAL,
            self::INVITATION_SENT,
            self::REGISTER_REQUEST,
            self::REGISTER_REGISTERED,
            self::VERIFICATION_VERIFY_EMAIL,
            self::VERIFICATION_EMAIL_VERIFIED,
            self::PASSWORD_RESET_REQUEST,
            self::PASSWORD_CHANGED => NotificationCategoryEnum::CRUCIAL,

            self::INVITATION_ACCEPTED,
            self::POSITION_APPROVAL_REJECTED,
            self::POSITION_APPROVAL_CANCELED,
            self::POSITION_APPROVAL_APPROVED => NotificationCategoryEnum::APPLICATION,
        };
    }
}
