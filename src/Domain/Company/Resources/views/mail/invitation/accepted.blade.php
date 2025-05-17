@php

/**
* @var \Domain\User\Models\User $notifiable
* @var \Domain\User\Models\User $user
*/

$type = \Support\Notification\Enums\NotificationTypeEnum::INVITATION_ACCEPTED;

@endphp

<x-mail::message>
{{ __('notifications.common.salutation') }},

{{ __n($type, 'mail', 'body.line1', ['name' => $user->full_name]) }}

{{ __('notifications.common.regards') }},
<br>
{{ __('notifications.common.signature', ['application' => (string) config('app.name')]) }}
</x-mail::message>
