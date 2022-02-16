@extends($themePath . '.pages.group')

@section('title', trans('Way2Translate::page.group-translations-detailed', [
    'locale'    => $locale['name'],
    'namespace' => $namespace,
    'group'     => $group
]))

@section('heading', trans('Way2Translate::page.group-translations-detailed', [
    'locale'    => $locale['name'],
    'namespace' => $namespace,
    'group'     => $group
]))

@section('groups')
    @foreach ($groupsByNamespace as $groupNamespace => $groups)
        @unless ($groupNamespace == '*')
            @component($themeComponentPath . '.list.group-heading', ['heading' => $groupNamespace])@endcomponent
        @endunless

        @component($themeComponentPath . '.list.group')
            @foreach($groups as $listGroup)
                @component($themeComponentPath . '.list.group-item')
                    @slot('href', route('way2translate.group.index', [$locale['code'], $listGroup->group, $listGroup->namespace]))
                    @slot('activeItem', $group)
                    @slot('item', $listGroup->group)
                @endcomponent
            @endforeach
        @endcomponent
    @endforeach
@endsection

@section('progress-bar-heading', trans('Way2Translate::translation.translated_percent'))

@section('progress-bar')
    @component($themeComponentPath . '.progress.bar')
        @slot('currentPercentage', $locale['translated_percent'])
    @endcomponent
@endsection

@section('translations-table')
    {!! Form::open([
        'id'     => 'group-translations',
        'method' => 'post',
        'url'    => route('way2translate.group.save', [$locale['code'], $group, $namespace]),
    ]) !!}
        @component($themeComponentPath . '.table.table')
            @component($themeComponentPath . '.table.thead')
                @component($themeComponentPath . '.table.th')
                    {{ trans('Way2Translate::translation.key') }}
                @endcomponent

                @component($themeComponentPath . '.table.th')
                    {{ trans('Way2Translate::translation.text') }}
                @endcomponent
            @endcomponent

            @component($themeComponentPath . '.table.tbody')
                @foreach ($translations as $translation)
                    @component($themeComponentPath . '.table.row')
                        @component($themeComponentPath . '.table.td')
                            {{ Form::label('value[' . $translation->id . ']', $translation->name, [
                                'class' => config('way2translate.theme.classes.static')
                            ]) }}
                        @endcomponent

                        @component($themeComponentPath . '.table.td')
                            {{ Form::textarea('value[' . $translation->id . ']', $translation->value, [
                                'class' => config('way2translate.theme.classes.textarea'),
                                'rows'  => 1,
                            ]) }}

                            @if ($errors->has('value[' . $translation->id . ']'))
                                @component($themeComponentPath . '.form.error')
                                    {{ $errors->first('value[' . $translation->id . ']') }}
                                @endcomponent
                            @endif
                        @endcomponent
                    @endcomponent
                @endforeach
            @endcomponent
        @endcomponent
    {{ Form::close() }}
@endsection

@if (config('way2translate.editable-languages'))
    @section('back-button')
        @component($themeComponentPath . '.buttons.link')
            @slot('href', route('way2translate.index'))

            {{ trans('Way2Translate::action.back') }}
        @endcomponent
    @endsection
@endif

@section('save-button')
    @can('updateGroup', Modules\Way2Translate\Models\Translation::class)
        @component($themeComponentPath . '.buttons.submit')
            @slot('form', 'group-translations')

            {{ trans('Way2Translate::action.store') }} "{{ $namespace }}::{{ $group }}" {{ trans('Way2Translate::translation.translations') }}
        @endcomponent
    @endcan
@endsection
