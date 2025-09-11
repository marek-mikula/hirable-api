@php

/**
* @var \Domain\Position\Models\PositionCandidateEvaluation $positionCandidateEvaluation
*/

$type = \Domain\Notification\Enums\NotificationTypeEnum::POSITION_CANDIDATE_EVALUATION_REMINDER;

$isExpired = $positionCandidateEvaluation->fill_until->isPast();

@endphp

<x-mail::message>
{{ __('notifications.common.salutation') }},

{!! __n($type, 'mail', 'body.line1', [
    'creator' => $positionCandidateEvaluation->creator->full_name,
    'candidate' => $positionCandidateEvaluation->positionCandidate->candidate->full_name,
    'position' => $positionCandidateEvaluation->positionCandidate->position->name
]) !!}

{!! __n($type, 'mail', $isExpired ? 'body.line2_expired' : 'body.line2', ['date' => formatter()->formatDate($positionCandidateEvaluation->fill_until)]) !!}

{{ __('notifications.common.regards') }},
<br>
{{ __('notifications.common.signature', ['application' => (string) config('app.name')]) }}
</x-mail::message>
