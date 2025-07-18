@php

/**
* @var \Domain\Application\Models\Application $notifiable
*/

$type = \Domain\Notification\Enums\NotificationTypeEnum::APPLICATION_ACCEPTED;

@endphp

<x-mail::message>
{{ __('notifications.common.salutation') }},

{{ __n($type, 'mail', 'body.line1', ['position' => $notifiable->position->name]) }}

{{ __('notifications.common.regards') }},
<br>
{{ __('notifications.common.signature', ['application' => (string) config('app.name')]) }}
</x-mail::message>
