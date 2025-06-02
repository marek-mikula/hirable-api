@php

    /**
     * @var \Illuminate\Support\Collection<\Support\NotificationPreview\Data\NotificationDomain> $notifications
     * @var \Support\NotificationPreview\Data\NotificationData $notification
     */

@endphp
        <!doctype html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        {{ $notification->label }}
    </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#ebfeff',
                            100: '#cefaff',
                            200: '#a2f3ff',
                            300: '#63e8fd',
                            400: '#1cd2f4',
                            500: '#00b4d8',
                            600: '#0390b7',
                            700: '#0a7394',
                            800: '#125d78',
                            900: '#144d65',
                            950: '#063346',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="h-full">
<main class="max-w-screen-xl m-auto p-4">
    <div class="grid grid-cols-5 gap-5">
        <nav class="col-span-1 flex flex-1 flex-col" aria-label="Sidebar">
            <ul role="list" class="sticky" style="top: 10px;">
                @foreach($notifications as $domain)
                    <li class="{{ $loop->first ? 'px-3 py-1' : 'px-3 py-1 mt-3' }}">
                        <i class="bi bi-box"></i>
                        <span>
                            {{ $domain->getLabel() }}
                        </span>
                    </li>
                    @foreach($domain->notifications as $n)
                        <li class="{{ $n->is($notification) ? 'relative text-primary-600 px-3 py-1 text-sm leading-6' : 'relative text-gray-700 hover:text-primary-600 px-3 py-1 text-sm leading-6' }}">
                            <span>
                                @if($n->is($notification))
                                    <i class="bi bi-arrow-right"></i>
                                @endif
                                {{ $n->label }}
                            </span>
                            <a href="{{ route('notification_preview.show', ['type' => $n->getType()->value, 'key' => $n->key]) }}" class="absolute inset-0"></a>
                        </li>
                    @endforeach
                @endforeach
            </ul>
        </nav>
        <div class="col-span-4 space-y-5">
            <h1 class="text-2xl font-semibold leading-7 text-gray-900">
                {{ $notification->label }}
            </h1>
            <div>
                <p>{{ $notification->description }}</p>
            </div>
            <div>
                <h2 class="text-base font-semibold leading-7 text-gray-900">
                    Channels
                </h2>
                <div class="mt-1 text-sm leading-6 text-gray-700 space-x-2">
                    @foreach($notification->getChannels() as $channel)
                        <a href="#{{ $channel }}"
                           class="rounded bg-primary-50 px-2 py-1 text-sm text-primary-600 shadow-sm hover:bg-primary-100">
                            {{ str($channel)->title() }}
                        </a>
                    @endforeach
                </div>
            </div>
            <div>
                <h2 class="text-base font-semibold leading-7 text-gray-900">
                    Notification type
                </h2>
                <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                    {{ $notification->getType()->value }}
                </span>
            </div>
            <div>
                <h2 class="text-base font-semibold leading-7 text-gray-900">
                    Notification category
                </h2>
                @if($notification->getType()->getCategory() === \Support\Notification\Enums\NotificationCategoryEnum::CRUCIAL)
                    <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">
                        {{ $notification->getType()->getCategory()->name }}
                    </span>
                @elseif($notification->getType()->getCategory() === \Support\Notification\Enums\NotificationCategoryEnum::TECHNICAL)
                    <span class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">
                        {{ $notification->getType()->getCategory()->name }}
                    </span>
                @elseif($notification->getType()->getCategory() === \Support\Notification\Enums\NotificationCategoryEnum::MARKETING)
                    <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                        {{ $notification->getType()->getCategory()->name }}
                    </span>
                @else
                    <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                        {{ $notification->getType()->getCategory()->name }}
                    </span>
                @endif
            </div>
            <div>
                <h2 class="text-base font-semibold leading-7 text-gray-900">
                    Notification class
                </h2>
                <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                    {{ $notification->getNotificationClass() }}
                </span>
            </div>
            <div>
                <h2 class="text-base font-semibold leading-7 text-gray-900">
                    Notifiable class
                </h2>
                <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                    {{ $notification->getNotifiableClass() }}
                </span>
            </div>
            <div>
                <h2 class="text-base font-semibold leading-7 text-gray-900">
                    Dummy notifiable
                    @if($notification->isAnonymous())
                        <span class="inline-flex items-center rounded-md bg-yellow-100 px-1.5 py-0.5 text-xs font-medium text-yellow-800">
                            Anonymous
                        </span>
                    @endif
                </h2>
                <pre class="mt-1 text-sm leading-6 text-gray-700 bg-gray-50 rounded-lg p-2 border border-gray-200 overflow-x-auto">{{ $notification->getNotifiableAsJson() }}</pre>
            </div>
            @if($notification->hasChannel('database'))
                <div class="divide-y divide-gray-200 overflow-hidden rounded-lg bg-white border border-gray-200">
                    <div class="bg-gray-100 p-3">
                        <h2 class="text-base font-semibold leading-6 text-gray-900" id="database">
                            <i class="bi bi-database" title="Database notification"></i>
                            <span class="ml-1">Database</span>
                        </h2>
                    </div>
                    <div class="p-3 space-y-2">
                        <div>
                            <h2 class="text-base font-semibold leading-7 text-gray-900">
                                Type
                            </h2>
                            <code class="mt-1 text-sm leading-6 text-gray-700">
                                {{ $notification->getDatabaseType() }}
                            </code>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold leading-7 text-gray-900">
                                Data
                            </h2>
                            <pre class="mt-1 text-sm leading-6 text-gray-700 bg-gray-50 rounded-lg p-2 border border-gray-200 overflow-x-auto">{{ json_encode($notification->getDatabase(), flags: JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                </div>
            @endif
            @if($notification->hasChannel('mail'))
                <div class="divide-y divide-gray-200 overflow-hidden rounded-lg bg-white border border-gray-200">
                    <div class="bg-gray-100 p-3 flex justify-between">
                        <h2 class="text-base font-semibold leading-6 text-gray-900 grow" id="mail">
                            <i class="bi bi-envelope" title="Mail notification"></i>
                            <span class="ml-1">Mail</span>
                        </h2>
                        <a href="{{ route('notification_preview.mail', ['type' => $notification->getType()->value, 'key' => $notification->key]) }}"
                           class="rounded bg-white px-2 py-1 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            Open in window
                        </a>
                    </div>
                    <div class="p-3 space-y-2">
                        <div>
                            <h2 class="text-base font-semibold leading-7 text-gray-900">
                                Subject
                            </h2>
                            <code class="mt-1 text-sm leading-6 text-gray-700">
                                {{ $notification->getMail()->getSubject() }}
                            </code>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold leading-7 text-gray-900">
                                To
                            </h2>
                            <code class="mt-1 text-sm leading-6 text-gray-700">
                                {{ $notification->getMail()->getTo() }}
                            </code>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold leading-7 text-gray-900">
                                Cc
                                <small class="text-gray-400">(copy)</small>
                            </h2>
                            <code class="mt-1 text-sm leading-6 text-gray-700">
                                {{ $notification->getMail()->getCc() }}
                            </code>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold leading-7 text-gray-900">
                                Bcc
                                <small class="text-gray-400">(blind copy)</small>
                            </h2>
                            <code class="mt-1 text-sm leading-6 text-gray-700">
                                {{ $notification->getMail()->getBcc() }}
                            </code>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold leading-7 text-gray-900">
                                Reply to
                            </h2>
                            <code class="mt-1 text-sm leading-6 text-gray-700">
                                {{ $notification->getMail()->getReplyTo() }}
                            </code>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold leading-7 text-gray-900">
                                From
                            </h2>
                            <code class="mt-1 text-sm leading-6 text-gray-700">
                                {{ $notification->getMail()->getFrom() }}
                            </code>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold leading-7 text-gray-900">
                                Preview
                            </h2>
                            <div class="mt-1 rounded-lg overflow-hidden" style="height: 500px;">
                                <iframe class="w-full h-full" src="{{ route('notification_preview.mail', ['type' => $notification->getType()->value, 'html' => $notification->getMail()->base64Html()]) }}"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</main>
</body>
</html>
