@extends('layouts.pages')

@section('main')

<h1>Help</h1>

<h3>{{ trans('titles.updated') }}</h3> 
<p><?php echo shell_exec('git log -1 --abbrev-commit --format="%ci %s"');  ?></p>
	
<h3>{{ trans('general.handbook_title') }}</h3>
<p>{{ trans('general.to_find_handbook1') }}<a href="{{ trans('general.to_find_handbook_link') }}">
{{ trans('general.to_find_handbook_link_text') }}</a>{{ trans('general.to_find_handbook2') }}</p>

<h3>{{ trans('general.clear_cookies_title') }}</h3>
<p>{{ trans('general.to_clear_cookies') }} <a href="/clearcookies">{{ trans('general.click_here') }}</a></p>

<h3>{{ trans('general.error_title') }}</h3>
<p>{{ trans('general.error_handling') }}</p>

<h3>{{ trans('general.workflow_title') }}</h3>
<img src="{{ trans('general.workflow_image') }}" alt="{{ trans('general.workflow_image_alt') }}">

@stop