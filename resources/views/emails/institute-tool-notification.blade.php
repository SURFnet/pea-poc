@component('mail::message')

{{ trans('mailable.greeting') }}

{{ trans('mailable.institute_tool_notification.intro', ['tool' => $tool->name]) }}

{{ $message }}

@component('mail::button', ['url' => $url])
    {{ trans('mailable.institute_tool_notification.view_tool_action') }}
@endcomponent

{{ trans('mailable.regards') }}

{{ $sender->name }}, {{ $sender->institute->full_name }}

@endcomponent
