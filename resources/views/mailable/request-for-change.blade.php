@component('mail::message')

<p style="text-align: center;"><small>---- Scroll down for English ----</small></p>

De volgende email is verstuurd naar PEA SURF:

Beste,

Er is een aanvraag voor wijziging ingestuurd door: {{  $user->name }} van instelling {{ $user->institute->full_name_nl }}.

Het betreft een wijziging voor: <a href="{{ $urlToTool }}" target="_blank">{{ $tool->name }}</a>.

De volgende tekst is aangeleverd:

{{ $requestForChange }}

<hr />

The following e-mail has been sent to PEA Surf:

Dear,

There is a new request for change by: {{  $user->name }} from institute {{ $user->institute->full_name_en }}.

It concerns a change for: <a href="{{ $urlToTool }}" target="_blank">{{ $tool->name }}</a>.

The following text has been supplied:

{{ $requestForChange }}
@endcomponent
