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

    case POSITION_OPENED = 'position:opened';
    case POSITION_APPROVAL = 'position:approval';
    case POSITION_APPROVAL_REJECTED = 'position:approval-rejected';
    case POSITION_APPROVAL_APPROVED = 'position:approval-approved';
    case POSITION_APPROVAL_EXPIRED = 'position:approval-expired';
    case POSITION_APPROVAL_CANCELED = 'position:approval-canceled';
    case POSITION_APPROVAL_REMINDER = 'position:approval-reminder';
}
