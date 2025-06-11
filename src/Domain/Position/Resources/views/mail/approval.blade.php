@php

    /**
    * @var \Domain\User\Models\User|\Domain\Company\Models\CompanyContact $notifiable
    * @var \Domain\User\Models\User $user
    * @var \Domain\Position\Models\Position $position
    * @var string $link
    */

    $type = \Domain\Notification\Enums\NotificationTypeEnum::POSITION_APPROVAL;

    $translation = $notifiable instanceof \Domain\Company\Models\CompanyContact ? 'external' : 'internal';

@endphp

<x-mail::message>
    {{ __('notifications.common.salutation') }},

    {!! __n($type, 'mail', sprintf('body.line1_%s', $translation), [
        'position' => $position->name,
        'user' => $user->full_name,
        'link' => $link,
        'application' => (string) config('app.name')
    ]) !!}

    {!! __n($type, 'mail', 'body.line2', ['date' => formatter()->formatDate($position->approve_until)]) !!}

    {{ __('notifications.common.regards') }},
    <br>
    {{ __('notifications.common.signature', ['application' => (string) config('app.name')]) }}
</x-mail::message>
