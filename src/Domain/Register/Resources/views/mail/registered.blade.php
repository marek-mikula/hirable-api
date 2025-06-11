@php

/**
* @var \Domain\User\Models\User $notifiable
*/

$type = \Domain\Notification\Enums\NotificationTypeEnum::REGISTER_REGISTERED;

@endphp

<x-mail::message>
{{ __('notifications.common.salutation') }},

{{ __n($type, 'mail', 'body.line1', ['application' => (string) config('app.name')]) }}

{{ __('notifications.common.regards') }},
<br>
{{ __('notifications.common.signature', ['application' => (string) config('app.name')]) }}
</x-mail::message>
