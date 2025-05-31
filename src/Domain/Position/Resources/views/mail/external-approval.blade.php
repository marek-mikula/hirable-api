@php

/**
* @var \Domain\User\Models\User $notifiable
*/

$type = \Support\Notification\Enums\NotificationTypeEnum::POSITION_EXTERNAL_APPROVAL;

@endphp

<x-mail::message>
{{ __('notifications.common.salutation') }},

Schval to more!

{{ __('notifications.common.regards') }},
<br>
{{ __('notifications.common.signature', ['application' => (string) config('app.name')]) }}
</x-mail::message>