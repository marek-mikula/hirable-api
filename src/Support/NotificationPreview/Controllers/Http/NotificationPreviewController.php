<?php

namespace Support\NotificationPreview\Controllers\Http;

use App\Enums\NotificationTypeEnum;
use App\Http\Controllers\WebController;
use App\Mail\Mailable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Support\NotificationPreview\Data\NotificationData;
use Support\NotificationPreview\Data\NotificationDomain;
use Support\NotificationPreview\Services\NotificationRegistrar;

class NotificationPreviewController extends WebController
{
    public function __construct(
        private readonly NotificationRegistrar $notificationRegistrar,
    ) {}

    public function index(): RedirectResponse
    {
        /** @var NotificationDomain|null $domain */
        $domain = $this->notificationRegistrar->getNotifications()->first();

        /** @var NotificationData|null $notification */
        $notification = $domain?->notifications?->first();

        abort_if(! $notification, code: 404, message: 'There are no notifications for preview!');

        // redirect to detail of the first notification
        return redirect()->route('notification_preview.show', ['type' => $notification->getType()->value]);
    }

    public function show(NotificationTypeEnum $type): View
    {
        $notifications = $this->notificationRegistrar->getNotifications();

        $notification = $this->findNotification($notifications, $type);

        abort_if(! $notification, code: 404, message: 'Notification not found!');

        return view('notifications-preview::preview', [
            'notifications' => $notifications,
            'notification' => $notification,
        ]);
    }

    public function mail(NotificationTypeEnum $type): Mailable|string
    {
        $html = request()->query('html');

        // decode passed base64 html code for preview
        // to include generated data from outside the
        // iframe when previewing the mail
        if (! empty($html)) {
            return base64_decode($html);
        }

        $notification = $this->findNotification($this->notificationRegistrar->getNotifications(), $type);

        abort_if(! $notification, code: 404, message: 'Notification not found!');

        return $notification->getMail()->mailable;
    }

    private function findNotification(Collection $notifications, NotificationTypeEnum $type): ?NotificationData
    {
        /** @var NotificationData|null $notification */
        $notification = $notifications
            ->map(function (NotificationDomain $domain): Collection {
                return $domain->notifications;
            })
            ->flatten()
            ->first(function (NotificationData $notification) use ($type): bool {
                return $notification->getType() === $type;
            });

        return $notification;
    }
}
