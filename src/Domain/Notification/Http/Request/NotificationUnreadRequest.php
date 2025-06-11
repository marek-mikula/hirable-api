<?php

declare(strict_types=1);

namespace Domain\Notification\Http\Request;

use App\Http\Requests\AuthRequest;
use Domain\Notification\Models\Notification;

class NotificationUnreadRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see NotificationPolicy::list() */
        return $this->user()->can('list', Notification::class);
    }

    public function rules(): array
    {
        return [];
    }
}
