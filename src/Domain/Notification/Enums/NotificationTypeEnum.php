<?php

declare(strict_types=1);

namespace Domain\Notification\Enums;

enum NotificationTypeEnum: string
{
    case REGISTER_REQUEST = 'register:request';
    case REGISTER_REGISTERED = 'register:registered';

    case VERIFICATION_VERIFY_EMAIL = 'verification:verify_email';
    case VERIFICATION_EMAIL_VERIFIED = 'verification:email_verified';

    case PASSWORD_RESET_REQUEST = 'password:reset_request';
    case PASSWORD_CHANGED = 'password:changed';

    case INVITATION_SENT = 'invitation:sent';
    case INVITATION_ACCEPTED = 'invitation:accepted';

    case POSITION_OPENED = 'position:opened';
    case POSITION_APPROVAL = 'position:approval';
    case POSITION_APPROVAL_REJECTED = 'position:approval_rejected';
    case POSITION_APPROVAL_APPROVED = 'position:approval_approved';
    case POSITION_APPROVAL_EXPIRED = 'position:approval_expired';
    case POSITION_APPROVAL_CANCELED = 'position:approval_canceled';
    case POSITION_APPROVAL_REMINDER = 'position:approval_reminder';
    case POSITION_ASSIGNED_AS_RECRUITER = 'position:assigned_as_recruiter';
    case POSITION_ASSIGNED_AS_HM = 'position:assigned_as_hm';
    case POSITION_REMOVED_AS_RECRUITER = 'position:removed_as_recruiter';
    case POSITION_REMOVED_AS_HM = 'position:removed_as_hm';
    case POSITION_NEW_CANDIDATE = 'position:new_candidate';

    case POSITION_CANDIDATE_SHARED = 'position_candidate:shared';
    case POSITION_CANDIDATE_SHARE_STOPPED = 'position_candidate:share_stopped';

    case POSITION_CANDIDATE_EVALUATION_REQUESTED = 'position_candidate:evaluation_requested';

    case APPLICATION_ACCEPTED = 'application:accepted';
}
