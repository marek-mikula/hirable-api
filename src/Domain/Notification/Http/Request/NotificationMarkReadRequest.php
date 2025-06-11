<?php

declare(strict_types=1);

namespace Domain\Notification\Http\Request;

use App\Http\Requests\AuthRequest;
use Domain\Notification\Policies\NotificationPolicy;

class NotificationMarkReadRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see NotificationPolicy::markRead() */
        return $this->user()->can('markRead', $this->route('notification'));
    }

    public function rules(): array
    {
        return [];
    }
}
