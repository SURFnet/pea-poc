@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# {{ trans('notification.error_greeting') }}
@else
# {{ trans('notification.greeting') }}
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset ($actionText)
<?php
    switch ($level) {
        case 'success':
        case 'error':
            $color = $level;
            break;
        default:
            $color = 'primary';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
{{ trans('notification.regards') }}<br>{{ config('app.name') }}
@endif

{{-- Subcopy --}}
@isset ($actionText)
@slot('subcopy')
{{ trans('notification.trouble_viewing', ['actionText' => $actionText]) }} [{{ $actionUrl }}]({{ $actionUrl }})
@endslot
@endisset
@endcomponent
