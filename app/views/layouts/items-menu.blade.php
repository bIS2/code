
<li data-toggle="tooltip" data-placement="bottom" data-original-title="{{ trans('titles.home')}}"> 
	<a href="/" ><span class="fa fa-home"></span> </a>
</li>

@if ((Auth::user()->hasRole('bibuser')) || (Auth::user()->hasRole('resuser'))) 
	<li class="{{ (!Request::is('sets*')) ?: 'active'}}" > 
		<a href="{{ route('sets.index', ['owner' => 1, 'aux'=>1]) }}" >
			<strong><span class="fa fa-file-text"></span> {{ trans('holdingssets.title')}}</strong>
		</a>
	</li>

	<li class="{{ (Request::is('groups*')) ? 'active' : '' }}">
		<a href="{{ route('groups.index') }}" ><strong><span class="fa fa-list"></span> {{ trans('holdingssets.groups')}}</strong> </a>
	</li>
@endif


@if ( Authority::can('work','Holding') or (( Auth::user()->hasRole('bibuser') || Auth::user()->hasRole('resuser')) && (count(Holdingsset::receiveds()->lists('id')) > 0 )) )
	<li class="{{ (!Request::is('holdings*')) ?: 'active'}}" > 
		<a href="{{ route('holdings.index') }}" >
			<strong><span class="fa fa-file-text"></span> {{ trans('holdings.title')}}</strong>
		</a>
	</li>
@endif


@if ( Authority::can('work','Holding') )
	<li class="{{ (Request::is('lists*')) ? 'active' : '' }}">
		<a href="{{ route('lists.index') }}" ><strong><span class="fa fa-list"></span> {{ trans('titles.lists')}}</strong> </a>
	</li>
@endif

@if (Auth::user()->hasRole('sysadmin') || Auth::user()->hasRole('superuser') )
	<li {{ (Request::is('admin/users*') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/users') }}}">
		<span class="fa fa-users"></span> {{{ trans('titles.users') }}}</a>
	</li>

	<li {{ (Request::is('admin/libraries*') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/libraries') }}}">
		<span class="fa fa-book"></span> {{{ trans('titles.libraries') }}}</a>
	</li>

	<li {{ (Request::is('admin/feedbacks*') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/feedbacks') }}}">
		<span class="fa fa-bug"></span> {{{ trans('titles.feedbacks') }}}</a>

	</li>
@endif
@if (Auth::user()->hasRole('superuser') )
	<li {{ (Request::is('admin/extract-data*') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/extract-data') }}}">
		<span class="fa fa-users"></span> {{{ trans('titles.extract_data') }}}</a>
	</li>

@endif
@if (((Session::get(Auth::user()->username.'_last_route') == '') && ($_COOKIE[Auth::user()->username.'_last_route'] != '')) && ((Auth::user()->hasRole('bibuser')) || (Auth::user()->hasRole('resuser'))))  
	<li class="btn btn-xs btn-warning">
		<a href="{{ $_COOKIE[Auth::user()->username.'_last_route'] }}" ><strong><i class="fa fa-repeat"></i></strong></a>
	</li>
	<?php Session::put(Auth::user()->username.'_last_route', $_COOKIE[Auth::user()->username.'_last_route']); ?>
@endif

<li {{ (Request::is('statistics*') ? ' class="active"' : '') }} data-toggle="tooltip" data-placement="bottom" data-original-title="{{ trans('titles.statistics')}}">
	<a href="/statistics" >
		<strong><i class="fa fa-bar-chart-o"></i></strong>
	</a>
</li>
<li data-toggle="tooltip" data-placement="bottom" data-original-title="{{ trans('titles.help')}}"> 
	<a href="/help" ><i class="fa fa-question-circle"></i> </a>
</li>
