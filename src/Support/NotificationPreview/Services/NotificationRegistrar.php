<?php

declare(strict_types=1);

namespace Support\NotificationPreview\Services;

use Domain\Application\Models\Application;
use Domain\Application\Notifications\ApplicationAcceptedNotification;
use Domain\Application\Notifications\ApplicationNewCandidateNotification;
use Domain\Candidate\Models\Candidate;
use Domain\Company\Models\CompanyContact;
use Domain\Company\Notifications\InvitationAcceptedNotification;
use Domain\Company\Notifications\InvitationSentNotification;
use Domain\Password\Notifications\PasswordChangedNotification;
use Domain\Password\Notifications\PasswordResetRequestNotification;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionApproval;
use Domain\Position\Notifications\PositionApprovalCanceledNotification;
use Domain\Position\Notifications\PositionApprovalExpiredNotification;
use Domain\Position\Notifications\PositionApprovalNotification;
use Domain\Position\Notifications\PositionApprovalApprovedNotification;
use Domain\Position\Notifications\PositionApprovalRejectedNotification;
use Domain\Position\Notifications\PositionApprovalReminderNotification;
use Domain\Position\Notifications\PositionAssignedAsHmNotification;
use Domain\Position\Notifications\PositionAssignedAsRecruiterNotification;
use Domain\Position\Notifications\PositionOpenedNotification;
use Domain\Position\Notifications\PositionRemovedAsHmNotification;
use Domain\Position\Notifications\PositionRemovedAsRecruiterNotification;
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
                        notifiable: fn () => (new AnonymousNotifiable())->route('mail', 'example@example.com')
                    ),
                    NotificationData::create(
                        label: 'Registered',
                        description: 'Notification notifies user that he successfully finished his registration.',
                        notification: fn (User $notifiable) => new RegisterRegisteredNotification(),
                        notifiable: fn () => User::factory()->make(),
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
                        notifiable: fn () => User::factory()->make(),
                    ),
                    NotificationData::create(
                        label: 'Email verified',
                        description: 'Notification informs the user that his email has been verified.',
                        notification: fn (User $notifiable) => new EmailVerifiedNotification(),
                        notifiable: fn () => User::factory()->make(),
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

                            return new PasswordResetRequestNotification(token: $token);
                        },
                        notifiable: fn () => User::factory()->make(),
                    ),
                    NotificationData::create(
                        label: 'Reset',
                        description: 'Notification notifies user that his password was successfully reset.',
                        notification: fn (User $notifiable) => new PasswordChangedNotification(),
                        notifiable: fn () => User::factory()->make(),
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
                        notifiable: fn () => (new AnonymousNotifiable())->route('mail', 'example@example.com'),
                    ),
                    NotificationData::create(
                        label: 'Invitation accepted',
                        description: 'Notifies the user, who sent an invitation, that the invitation has been accepted.',
                        notification: function (User $notifiable) {
                            $user = User::factory()->make();

                            return new InvitationAcceptedNotification(user: $user);
                        },
                        notifiable: fn () => User::factory()->make(),
                    ),
                ]
            ),
            NotificationDomain::create(
                key: 'position',
                notifications: [
                    NotificationData::create(
                        label: 'Opened',
                        description: 'Notification informs hiring managers and recruiters on position that the position has been opened for hiring.',
                        notification: function (User $notifiable) {
                            $position = Position::factory()->make();
                            return new PositionOpenedNotification(position: $position);
                        },
                        notifiable: fn () => User::factory()->make(),
                    ),
                    NotificationData::create(
                        label: 'Assigned as recruiter',
                        description: 'Notification informs user that he has been assigned to position as recruiter.',
                        notification: function (User $notifiable) {
                            $position = Position::factory()->make();
                            return new PositionAssignedAsRecruiterNotification(position: $position);
                        },
                        notifiable: fn () => User::factory()->make(),
                    ),
                    NotificationData::create(
                        label: 'Removed as recruiter',
                        description: 'Notification informs user that he has been removed from position as recruiter.',
                        notification: function (User $notifiable) {
                            $position = Position::factory()->make();
                            return new PositionRemovedAsRecruiterNotification(position: $position);
                        },
                        notifiable: fn () => User::factory()->make(),
                    ),
                    NotificationData::create(
                        label: 'Assigned as hiring manager',
                        description: 'Notification informs user that he has been assigned to position as hiring manager.',
                        notification: function (User $notifiable) {
                            $position = Position::factory()->make();
                            return new PositionAssignedAsHmNotification(position: $position);
                        },
                        notifiable: fn () => User::factory()->make(),
                    ),
                    NotificationData::create(
                        label: 'Removed as hiring manager',
                        description: 'Notification informs user that he has been removed from position as hiring manager.',
                        notification: function (User $notifiable) {
                            $position = Position::factory()->make();
                            return new PositionRemovedAsHmNotification(position: $position);
                        },
                        notifiable: fn () => User::factory()->make(),
                    ),
                ],
            ),
            NotificationDomain::create(
                key: 'position-approval',
                notifications: [
                    NotificationData::create(
                        label: 'To approve (internal user)',
                        description: 'Notification sends user position to approve.',
                        notification: function (User $notifiable) {
                            $position = Position::factory()->ofApproveUntil(now()->subDays(3))->ofApproveMessage(fake()->text(500))->make();
                            $user = User::factory()->make();

                            return new PositionApprovalNotification(user: $user, position: $position);
                        },
                        notifiable: fn () => User::factory()->make(),
                        key: 'internal'
                    ),
                    NotificationData::create(
                        label: 'To approve (external approver)',
                        description: 'Notification sends external user position to approve.',
                        notification: function (CompanyContact $notifiable) {
                            $position = Position::factory()->ofApproveUntil(now()->subDays(3))->ofApproveMessage(fake()->text(500))->make();
                            $user = User::factory()->make();
                            $token = Token::factory()->ofType(TokenTypeEnum::EXTERNAL_APPROVAL)->make();

                            return new PositionApprovalNotification(user: $user, position: $position, token: $token);
                        },
                        notifiable: fn () => CompanyContact::factory()->make(),
                        key: 'external',
                    ),
                    NotificationData::create(
                        label: 'Rejected (internal user)',
                        description: 'Notification sends info to internal user or external approver that position has been rejected by internal user.',
                        notification: function (User $notifiable) {
                            $rejectedBy = User::factory()->make();
                            $position = Position::factory()->make();
                            $approval = PositionApproval::factory()->rejected(fake()->text(maxNbChars: 500))->make();

                            return new PositionApprovalRejectedNotification(
                                rejectedBy: $rejectedBy,
                                approval: $approval,
                                position: $position
                            );
                        },
                        notifiable: fn () => User::factory()->make(),
                        key: 'internal'
                    ),
                    NotificationData::create(
                        label: 'Rejected (external approver)',
                        description: 'Notification sends info to internal user or external approver that position has been rejected by external approver.',
                        notification: function (User $notifiable) {
                            $rejectedBy = CompanyContact::factory()->make();
                            $position = Position::factory()->make();
                            $approval = PositionApproval::factory()->rejected(fake()->text(maxNbChars: 500))->make();

                            return new PositionApprovalRejectedNotification(
                                rejectedBy: $rejectedBy,
                                approval: $approval,
                                position: $position
                            );
                        },
                        notifiable: fn () => User::factory()->make(),
                        key: 'external'
                    ),
                    NotificationData::create(
                        label: 'Approved',
                        description: 'Notification informs the owner of the position that it was successfully approved.',
                        notification: function (User $notifiable) {
                            $position = Position::factory()->make();
                            return new PositionApprovalApprovedNotification(position: $position);
                        },
                        notifiable: fn () => User::factory()->make(),
                    ),
                    NotificationData::create(
                        label: 'Canceled (internal user)',
                        description: 'Notification informs internal user that the approval process of specific position has been canceled by the owner.',
                        notification: function (User $notifiable) {
                            $position = Position::factory()->make();
                            $canceledBy = User::factory()->make();
                            return new PositionApprovalCanceledNotification(position: $position, canceledBy: $canceledBy);
                        },
                        notifiable: fn () => User::factory()->make(),
                        key: 'internal',
                    ),
                    NotificationData::create(
                        label: 'Canceled (external approver)',
                        description: 'Notification informs external approver that the approval process of specific position has been canceled by the owner.',
                        notification: function (CompanyContact $notifiable) {
                            $position = Position::factory()->make();
                            $canceledBy = User::factory()->make();
                            return new PositionApprovalCanceledNotification(position: $position, canceledBy: $canceledBy);
                        },
                        notifiable: fn () => CompanyContact::factory()->make(),
                        key: 'external',
                    ),
                    NotificationData::create(
                        label: 'Expired (internal user)',
                        description: 'Notification informs internal user that the approval process of specific position has expired.',
                        notification: function (User $notifiable) {
                            $position = Position::factory()->make();
                            return new PositionApprovalExpiredNotification(position: $position);
                        },
                        notifiable: fn () => User::factory()->make(),
                        key: 'internal'
                    ),
                    NotificationData::create(
                        label: 'Expired (external approver)',
                        description: 'Notification informs external approver that the approval process of specific position has expired.',
                        notification: function (CompanyContact $notifiable) {
                            $position = Position::factory()->make();
                            return new PositionApprovalExpiredNotification(position: $position);
                        },
                        notifiable: fn () => CompanyContact::factory()->make(),
                        key: 'external'
                    ),
                    NotificationData::create(
                        label: 'Reminder (internal user)',
                        description: 'Notification informs internal user about the forgotten approval process.',
                        notification: function (User $notifiable) {
                            $position = Position::factory()->ofApproveUntil(now())->make();
                            return new PositionApprovalReminderNotification(position: $position, token: null);
                        },
                        notifiable: fn () => User::factory()->make(),
                        key: 'internal'
                    ),
                    NotificationData::create(
                        label: 'Reminder (external approver)',
                        description: 'Notification informs external approver about the forgotten approval process.',
                        notification: function (CompanyContact $notifiable) {
                            $position = Position::factory()->ofApproveUntil(now())->make();
                            $token = Token::factory()->ofType(TokenTypeEnum::EXTERNAL_APPROVAL)->make();

                            return new PositionApprovalReminderNotification(position: $position, token: $token);
                        },
                        notifiable: fn () => CompanyContact::factory()->make(),
                        key: 'external'
                    ),
                ]
            ),
            NotificationDomain::create(
                key: 'application',
                notifications: [
                    NotificationData::create(
                        label: 'Accepted',
                        description: 'Notification informs candidate that his application has been accepted.',
                        notification: function (Application $notifiable) {
                            return new ApplicationAcceptedNotification();
                        },
                        notifiable: function () {
                            $notifiable = Application::factory()->make();
                            $notifiable->setRelation('position', Position::factory()->make());

                            return $notifiable;
                        },
                    ),
                    NotificationData::create(
                        label: 'New candidate',
                        description: 'Notification informs users that new candidate has applied for a position.',
                        notification: function (User $notifiable) {
                            $position = Position::factory()->make();
                            $candidate = Candidate::factory()->make();
                            return new ApplicationNewCandidateNotification(position: $position, candidate: $candidate);
                        },
                        notifiable: function () {
                            return User::factory()->make();
                        },
                    ),
                ]
            ),
        ]));
    }
}
