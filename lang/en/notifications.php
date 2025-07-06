<?php

declare(strict_types=1);

use Domain\Notification\Enums\NotificationTypeEnum;

return [

    'common' => [
        'salutation' => 'Greetings',
        'regards' => 'Best regards',
        'signature' => ':application Team',
        'link' => 'If the button link does not work, use this link instead: *:link*.',
        'rights' => 'All rights reserved.',
    ],

    NotificationTypeEnum::REGISTER_REQUEST->value => [
        'mail' => [
            'subject' => 'Finish your registration to start using :application 🚀',
            'body' => [
                'line1' => 'We\'re thrilled that you\'ve decided to join us! To finish your registration, use the button below!',
                'line2' => 'The link is valid until **:validity**.',
                'action' => 'Finish registration',
            ],
        ],
    ],

    NotificationTypeEnum::REGISTER_REGISTERED->value => [
        'mail' => [
            'subject' => 'Woohoo! 🎉 Welcome to :application!',
            'body' => [
                'line1' => 'Thank you for joining :application! We\'re thrilled to have you on board and we look forward to making your experience truly awesome.',
            ],
        ],
    ],

    NotificationTypeEnum::VERIFICATION_VERIFY_EMAIL->value => [
        'mail' => [
            'subject' => '📧 Verify your email address',
            'body' => [
                'line1' => 'We need to verify your email address. Use the button below to finish the verification process.',
                'line2' => 'The link is valid until **:validity**.',
                'action' => 'Verify email address',
            ],
        ],
    ],

    NotificationTypeEnum::VERIFICATION_EMAIL_VERIFIED->value => [
        'mail' => [
            'subject' => '🎉 Your email address is verified',
            'body' => [
                'line1' => 'Your email address has been successfully verified! You\'re all set to dive into our platform and explore everything it has to offer.',
            ],
        ],
    ],

    NotificationTypeEnum::PASSWORD_CHANGED->value => [
        'mail' => [
            'subject' => 'Password changed',
            'body' => [
                'line1' => 'We are writing to inform you that your password has been successfully changed.',
                'line2' => 'If you changed your password recently, then you can safely ignore this email.',
                'line3' => 'However, if you did not make this change, or you believe that someone else may have accessed your account, please contact us immediately.',
            ],
        ],
    ],

    NotificationTypeEnum::PASSWORD_RESET_REQUEST->value => [
        'mail' => [
            'subject' => 'Password reset request',
            'body' => [
                'line1' => 'We\'ve received a request to reset the password associated with your account. Use the button below to navigate to the link to reset your password.',
                'line2' => 'The link is valid until **:validity**.',
                'line3' => 'If you did not request a password reset, please ignore this email.',
                'action' => 'Reset password',
            ],
        ],
    ],

    NotificationTypeEnum::INVITATION_SENT->value => [
        'mail' => [
            'subject' => 'You\'ve been invited to join :application!',
            'body' => [
                'line1' => 'You\'ve been invited to join :application. Use the button below to navigate to the link to finish your registration.',
                'line2' => 'The link is valid until **:validity**.',
                'line3' => 'If you don\'t know what this is, you can safely ignore this email.',
                'action' => 'Finish registration',
            ],
        ],
    ],

    NotificationTypeEnum::INVITATION_ACCEPTED->value => [
        'mail' => [
            'subject' => '✅ Invitation accepted',
            'body' => [
                'line1' => 'User :name has accepted your invitation.',
            ],
        ],
    ],

    NotificationTypeEnum::POSITION_APPROVAL->value => [
        'mail' => [
            'subject' => '👍 Position to approve - :position',
            'body' => [
                'line1_internal' => 'There is a new position **:position** in application from user :user that needs your approval. You can check the position <a href=":link">here</a>',
                'line1_external' => 'User :user has assigned you to position **:position** as an approver in :application app. You can check the position <a href=":link">here</a>',
                'line2' => 'Decide on approval by: **:date**.',
            ],
        ],
    ],

    NotificationTypeEnum::POSITION_APPROVAL_REJECTED->value => [
        'mail' => [
            'subject' => '🔴 Position rejected - :position',
            'body' => [
                'line1_internal' => 'Position **:position** was rejected by user :user.',
                'line1_external' => 'Position **:position** was rejected by external approver :user.',
            ],
        ],
    ],

    NotificationTypeEnum::POSITION_APPROVAL_APPROVED->value => [
        'mail' => [
            'subject' => '🟢 Position approved - :position',
            'body' => [
                'line1' => 'Your position **:position** was successfully approved by all approvers.',
            ],
        ],
    ],

    NotificationTypeEnum::POSITION_APPROVAL_CANCELED->value => [
        'mail' => [
            'subject' => '⚪ Approval Canceled - :position',
            'body' => [
                'line1' => 'The approval process for position **:position** has been canceled by user :user.',
            ],
        ],
    ],

    NotificationTypeEnum::POSITION_APPROVAL_EXPIRED->value => [
        'mail' => [
            'subject' => '⏱️ Approval expired - :position',
            'body' => [
                'line1' => 'The approval process for position **:position** has expired.',
            ],
        ],
    ],

    NotificationTypeEnum::POSITION_APPROVAL_REMINDER->value => [
        'mail' => [
            'subject' => '👍 Position pending approval - :position',
            'body' => [
                'line1' => 'Position **:position** is still awaiting your approval. You can view the position <a href=":link">here</a>',
                'line2' => 'Decide on approval by: **:date**.',
            ],
        ],
    ],

    NotificationTypeEnum::POSITION_OPENED->value => [
        'mail' => [
            'subject' => '✅ Position open for hiring - :position',
            'body' => [
                'line1' => 'The position, where you are assigned as hiring manager or recruiter, **:position** has been opened for hiring. You can view the position <a href=":link">here</a>',
            ],
        ],
    ],

];
