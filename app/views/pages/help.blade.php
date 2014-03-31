@extends('layouts.pages')

@section('main')

<h1>Help</h1>

<h3>{{ trans('general.handbook_title') }}</h3>
<p>{{ trans('general.to_find_handbook1') }}<a href="{{ trans('general.to_find_handbook_link') }}">
{{ trans('general.to_find_handbook_link') }}</a>{{ trans('general.to_find_handbook2') }}</p>

<h3>{{ trans('general.clear_cookies_title') }}</h3>
<p>{{ trans('general.to_clear_cookies') }} <a href="/clearcookies">{{ trans('general.click_here') }}</a></p>
@stop