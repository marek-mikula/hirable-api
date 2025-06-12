@php

/**
* @var \Domain\User\Models\User $notifiable
*/

$type = \Domain\Notification\Enums\NotificationTypeEnum::PASSWORD_CHANGED;

@endphp

<x-mail::message>
{{ __('notifications.common.salutation') }},

{{ __n($type, 'mail', 'body.line1') }}

{{ __n($type, 'mail', 'body.line2') }}

{{ __n($type, 'mail', 'body.line3') }}

{{ __('notifications.common.regards') }},
<br>
{{ __('notifications.common.signature', ['application' => (string) config('app.name')]) }}
</x-mail::message>
