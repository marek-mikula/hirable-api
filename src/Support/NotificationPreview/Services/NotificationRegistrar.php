<?php

declare(strict_types=1);

namespace Support\NotificationPreview\Services;

use Domain\Company\Notifications\InvitationAcceptedNotification;
use Domain\Company\Notifications\InvitationSentNotification;
use Domain\Password\Notifications\ChangedNotification;
use Domain\Password\Notifications\ResetRequestNotification;
use Domain\Register\Notifications\RegisterRegisteredNotification;
use Domain\Register\Notifications\RegisterRequestNotification;
use Domain\User\Models\User;
use Domain\Verification\Notifications\EmailVerifiedNotification;
use Domain\Verification\Notifications\VerifyEmailNotification;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Collection;
use Support\NotificationPreview\Data\NotificationData;
use Support\NotificationPreview\Data\NotificationDomain;
use Support\Token\Enums\TokenTypeEnum;
use Support\Token\Models\Token;

class NotificationRegistrar
{
    /**
     * @return Collection<NotificationDomain>
     */
    public function getNotifications(): Collection
    {
        return once(static fn (): Collection => collect([
            NotificationDomain::create(
                key: 'register',
                notifications: [
                    NotificationData::create(
                        label: 'Request',
                        description: 'Notification sends newly registered user a link to finish his registration.',
                        notification: function (AnonymousNotifiable $notifiable) {
                            $token = Token::factory()->ofType(TokenTypeEnum::REGISTRATION)->ofData([
                                'email' => (string) $notifiable->routeNotificationFor('mail'),
                            ])->make();

                            return new RegisterRequestNotification(token: $token);
                        },
                        notifiable: fn() => (new AnonymousNotifiable())->route('mail', 'example@example.com')
                    ),
                    NotificationData::create(
                        label: 'Registered',
                        description: 'Notification notifies user that he successfully finished his registration.',
                        notification: fn(User $notifiable) => new RegisterRegisteredNotification(),
                        notifiable: fn() => User::factory()->make(),
                    ),
                ],
            ),
            NotificationDomain::create(
                key: 'verification',
                notifications: [
                    NotificationData::create(
                        label: 'Verify email address',
                        description: 'Notification prompts the user to finish the email address verification process.',
                        notification: function (User $notifiable) {
                            $token = Token::factory()->ofType(TokenTypeEnum::EMAIL_VERIFICATION)->make();

                            return new VerifyEmailNotification(token: $token);
                        },
                        notifiable: fn() => User::factory()->make(),
                    ),
                    NotificationData::create(
                        label: 'Email verified',
                        description: 'Notification informs the user that his email has been verified.',
                        notification: fn(User $notifiable) => new EmailVerifiedNotification(),
                        notifiable: fn() => User::factory()->make(),
                    ),
                ],
            ),
            NotificationDomain::create(
                key: 'password',
                notifications: [
                    NotificationData::create(
                        label: 'Reset Request',
                        description: 'Notification sends user link to reset his password.',
                        notification: function (User $notifiable) {
                            $token = Token::factory()->ofType(TokenTypeEnum::RESET_PASSWORD)->make();

                            return new ResetRequestNotification(token: $token);
                        },
                        notifiable: fn() => User::factory()->make(),
                    ),
                    NotificationData::create(
                        label: 'Reset',
                        description: 'Notification notifies user that his password was successfully reset.',
                        notification: fn(User $notifiable) => new ChangedNotification(),
                        notifiable: fn() => User::factory()->make(),
                    ),
                ]
            ),
            NotificationDomain::create(
                key: 'invitation',
                notifications: [
                    NotificationData::create(
                        label: 'Invitation sent',
                        description: 'Notification sends user link to register with specified company.',
                        notification: function (AnonymousNotifiable $notifiable) {
                            $token = Token::factory()
                                ->ofType(TokenTypeEnum::INVITATION)
                                ->make();

                            return new InvitationSentNotification(token: $token);
                        },
                        notifiable: fn() => (new AnonymousNotifiable())->route('mail', 'example@example.com'),
                    ),
                    NotificationData::create(
                        label: 'Invitation accepted',
                        description: 'Notifies the user, who sent an invitation, that the invitation has been accepted.',
                        notification: function (User $notifiable) {
                            $user = User::factory()->make();

                            return new InvitationAcceptedNotification(user: $user);
                        },
                        notifiable: fn() => User::factory()->make(),
                    ),
                ]
            ),
        ]));
    }
}
