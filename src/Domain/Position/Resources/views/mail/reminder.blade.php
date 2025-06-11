@php

    /**
    * @var \Domain\User\Models\User|\Domain\Company\Models\CompanyContact $notifiable
    * @var \Domain\Position\Models\Position $position
    * @var string $link
    */

    $type = \Domain\Notification\Enums\NotificationTypeEnum::POSITION_APPROVAL_REMINDER;

@endphp

<x-mail::message>
    {{ __('notifications.common.salutation') }},

    {!! __n($type, 'mail', 'body.line1', ['position' => $position->name, 'link' => $link]) !!}

    {!! __n($type, 'mail', 'body.line2', ['date' => formatter()->formatDate($position->approve_until)]) !!}

    {{ __('notifications.common.regards') }},
    <br>
    {{ __('notifications.common.signature', ['application' => (string) config('app.name')]) }}
</x-mail::message>