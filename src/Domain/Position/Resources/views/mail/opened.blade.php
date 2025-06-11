@php

/**
* @var \Domain\User\Models\User $notifiable
* @var \Domain\Position\Models\Position $position
* @var string $link
*/

$type = \Domain\Notification\Enums\NotificationTypeEnum::POSITION_OPENED;

@endphp

<x-mail::message>
{{ __('notifications.common.salutation') }},

{!! __n($type, 'mail', 'body.line1', ['position' => $position->name, 'link' => $link]) !!}

{{ __('notifications.common.regards') }},
<br>
{{ __('notifications.common.signature', ['application' => (string) config('app.name')]) }}
</x-mail::message>