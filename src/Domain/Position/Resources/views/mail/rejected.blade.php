@php

/**
* @var \Domain\User\Models\User|\Domain\Company\Models\CompanyContact $notifiable
* @var \Domain\User\Models\User|\Domain\Company\Models\CompanyContact $rejectedBy
* @var \Domain\Position\Models\PositionApproval $approval
* @var \Domain\Position\Models\Position $position
*/

$type = \Support\Notification\Enums\NotificationTypeEnum::POSITION_APPROVAL_REJECTED;

$translation = $rejectedBy instanceof \Domain\Company\Models\CompanyContact ? 'external' : 'internal';

@endphp

<x-mail::message>
{{ __('notifications.common.salutation') }},

{!! __n($type, 'mail', sprintf('body.line1_%s', $translation), ['position' => $position->name, 'user' => $rejectedBy->full_name]) !!}

<x-mail::panel>
{{ (string) $approval->note }}
</x-mail::panel>

{{ __('notifications.common.regards') }},
<br>
{{ __('notifications.common.signature', ['application' => (string) config('app.name')]) }}
</x-mail::message>
