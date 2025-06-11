<?php

declare(strict_types=1);

namespace Domain\Notification\Http\Request;

use App\Http\Requests\AuthRequest;
use Domain\Notification\Models\Notification;
use Domain\Notification\Policies\NotificationPolicy;

class NotificationMarkAllReadRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see NotificationPolicy::markAllRead() */
        return $this->user()->can('markAllRead', Notification::class);
    }

    public function rules(): array
    {
        return [];
    }
}
