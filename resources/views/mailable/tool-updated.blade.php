@component('mail::message')

{{ trans('mailable.greeting') }}

{{ trans('mailable.tool_updated.there_has_been_a_change') }}

@component('mail::button', ['url' => route('other.tool.show', $tool)])
    {{ trans('mailable.tool_updated.view_tool_action') }}
@endcomponent

{{ trans('mailable.regards') }}

@endcomponent
