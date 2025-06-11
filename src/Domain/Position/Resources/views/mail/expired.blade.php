@php

    /**
    * @var \Domain\User\Models\User|\Domain\Company\Models\CompanyContact $notifiable
    * @var \Domain\Position\Models\Position $position
    */

    $type = \Domain\Notification\Enums\NotificationTypeEnum::POSITION_APPROVAL_EXPIRED;

@endphp

<x-mail::message>
    {{ __('notifications.common.salutation') }},

    {!! __n($type, 'mail', 'body.line1', ['position' => $position->name]) !!}

    {{ __('notifications.common.regards') }},
    <br>
    {{ __('notifications.common.signature', ['application' => (string) config('app.name')]) }}
</x-mail::message>