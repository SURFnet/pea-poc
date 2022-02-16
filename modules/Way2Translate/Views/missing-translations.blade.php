@extends($themePath . '.pages.error')

@section('title', trans('Way2Translate::page.missing-translations'))
@section('heading', trans('Way2Translate::page.missing-translations'))

@section('text')
    {{ trans('Way2Translate::translation.missing-translations') }}
@endsection
