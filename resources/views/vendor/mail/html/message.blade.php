<x-mail::layout>
{{-- Header --}}
<x-slot:header>
<x-mail::header :url="frontendLink('/')">
{{ config('app.name') }}
</x-mail::header>
</x-slot:header>

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
<x-slot:subcopy>
<x-mail::subcopy>
{{ $subcopy }}
</x-mail::subcopy>
</x-slot:subcopy>
@endisset

{{-- Footer --}}
<x-slot:footer>
<x-mail::footer>
© {{ now()->format('Y') }} {{ config('app.name') }}, <a href="mailto:{{ config('app.contact_email') }}">{{ config('app.contact_email') }}</a>. {{ __('notifications.common.rights') }}
</x-mail::footer>
</x-slot:footer>
</x-mail::layout>
