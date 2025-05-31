@php

/**
* @var \Domain\User\Models\User $notifiable
* @var \Domain\User\Models\User $user
* @var \Domain\Position\Models\Position $position
* @var string $link
*/

$type = \Support\Notification\Enums\NotificationTypeEnum::POSITION_APPROVAL;

@endphp

<x-mail::message>
{{ __('notifications.common.salutation') }},

{!! __n($type, 'mail', 'body.line1', ['position' => $position->name, 'user' => $user->full_name, 'link' => $link]) !!}

{{ __('notifications.common.regards') }},
<br>
{{ __('notifications.common.signature', ['application' => (string) config('app.name')]) }}
</x-mail::message>
