@extends($themePath . '.pages.index')

@section('title', trans('Way2Translate::page.manage-translations'))
@section('heading', trans('Way2Translate::page.manage-translations'))

@section('translations-heading', trans('Way2Translate::translation.translations'))

@section('translations-table')
    @component($themeComponentPath . '.table.table')
        @component($themeComponentPath . '.table.thead')
            @component($themeComponentPath . '.table.thead')
                @component($themeComponentPath . '.table.th')
                    {{ trans('Way2Translate::translation.language') }}
                @endcomponent

                @component($themeComponentPath . '.table.th')
                    {{ trans('Way2Translate::translation.translated_percent') }}
                @endcomponent

                @component($themeComponentPath . '.table.th')
                    {{ trans('Way2Translate::translation.status') }}
                @endcomponent

                @component($themeComponentPath . '.table.th')
                @endcomponent
            @endcomponent
        @endcomponent

        @component($themeComponentPath . '.table.tbody')
            @foreach ($translatedLocales as $index => $translatedLocale)
                @component($themeComponentPath . '.table.row')
                    @component($themeComponentPath . '.table.td')
                        {{ $translatedLocale['name'] }}
                    @endcomponent

                    @component($themeComponentPath . '.table.td')
                        {{ $translatedLocale['translated_percent'] . '%' }}
                    @endcomponent

                    @component($themeComponentPath . '.table.td')
                        {{ trans('Way2Translate::translation.status-display.' . $translatedLocale['active']) }}
                    @endcomponent

                    @component($themeComponentPath . '.table.td')
                        @can('updateGroup', Modules\Way2Translate\Models\Translation::class)
                            @component($themeComponentPath . '.buttons.primary')
                                @slot('href', route('way2translate.group.index', $translatedLocale['code']))
                                @slot('small', true)

                                {{ trans('Way2Translate::action.edit') }}
                            @endcomponent
                        @endcan

                        @if ($translatedLocale['code'] != Config::get('way2translate.default-locale'))
                            @if ($translatedLocale['active'])
                                @can('deactivateLocale', Modules\Way2Translate\Models\Translation::class)
                                    @component($themeComponentPath . '.buttons.disable')
                                        @slot('href', route('way2translate.deactivate', $translatedLocale['code']))
                                        @slot('small', true)

                                        {{ trans('Way2Translate::action.deactivate') }}
                                    @endcomponent
                                @endcan
                            @else
                                @can('activateLocale', Modules\Way2Translate\Models\Translation::class)
                                    @component($themeComponentPath . '.buttons.enable')
                                        @slot('href', route('way2translate.activate', $translatedLocale['code']))
                                        @slot('small', true)

                                        {{ trans('Way2Translate::action.activate') }}
                                    @endcomponent
                                @endcan
                            @endif
                        @endif
                    @endcomponent
                @endcomponent
            @endforeach
        @endcomponent
    @endcomponent
@endsection

@can('create', Modules\Way2Translate\Models\Translation::class)
    @section('new-language-heading', trans('Way2Translate::translation.new-language'))

    @section('new-language-form')
        {!! Form::open([
            'id'     => 'add-language',
            'method' => 'post',
            'url'    => route('way2translate.add'),
            'class'  => config('way2translate.theme.classes.form')
        ]) !!}
            @component($themeComponentPath . '.form.group')
                {{ Form::select('locale_target', $nonTranslatedLocalesTarget, null, [
                    'class' => config('way2translate.theme.classes.select')
                ]) }}

                @if ($errors->has('locale_target'))
                    @component($themeComponentPath . '.form.error')
                        {{ $errors->first('locale_target') }}
                    @endcomponent
                @endif
            @endcomponent

            @component($themeComponentPath . '.form.group')
                @component($themeComponentPath . '.buttons.submit')
                    @slot('form', 'add-language')

                    {{ trans('Way2Translate::action.add-language') }}
                @endcomponent
            @endcomponent
        {{ Form::close() }}
    @endsection
@endcan
