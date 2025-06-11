@php

    /**
    * @var \Domain\User\Models\User $notifiable
    * @var \Support\Token\Models\Token $token
    */

    $type = \Domain\Notification\Enums\NotificationTypeEnum::VERIFICATION_VERIFY_EMAIL;

@endphp

<x-mail::message>
    {{ __('notifications.common.salutation') }},

    {{ __n($type, 'mail', 'body.line1') }}

    <x-mail::button :url="$token->link">
        {{ __n($type, 'mail', 'body.action') }}
    </x-mail::button>

    {{ __n($type, 'mail', 'body.line2', ['validity' => formatter()->formatDatetime($token->valid_until, withSeconds: true)]) }}

    {{ __('notifications.common.regards') }},
    <br>
    {{ __('notifications.common.signature', ['application' => (string) config('app.name')]) }}
</x-mail::message>
