@php

/**
* @var \App\Models\User $notifiable
*/

$type = \App\Enums\NotificationTypeEnum::VERIFICATION_EMAIL_VERIFIED;

@endphp

<x-mail::message>
{{ __('notifications.common.salutation') }},

{{ __n($type, 'mail', 'body.line1') }}

{{ __('notifications.common.regards') }},
<br>
{{ __('notifications.common.signature', ['application' => (string) config('app.name')]) }}
</x-mail::message>
