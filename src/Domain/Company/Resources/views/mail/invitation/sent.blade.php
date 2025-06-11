@php

/**
* @var \Support\Token\Models\Token $token
*/

$type = \Domain\Notification\Enums\NotificationTypeEnum::INVITATION_SENT;

@endphp

<x-mail::message>
{{ __('notifications.common.salutation') }},

{{ __n($type, 'mail', 'body.line1', ['application' => (string) config('app.name')]) }}

{{ __n($type, 'mail', 'body.line2', ['validity' => formatter()->formatDatetime($token->valid_until, withSeconds: true)]) }}

<x-mail::button :url="$token->link">
    {{ __n($type, 'mail', 'body.action') }}
</x-mail::button>

{{ __n($type, 'mail', 'body.line3') }}

{{ __('notifications.common.regards') }},
<br>
{{ __('notifications.common.signature', ['application' => (string) config('app.name')]) }}
</x-mail::message>
