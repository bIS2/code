<li class="{{ (!Request::is('sets*')) ?: 'active'}}" > 
	<a href="{{ route('sets.index', ['owner' => 1, 'aux'=>1]) }}" >
		<strong><span class="fa fa-file-text"></span> {{ trans('holdingssets.title')}}</strong>
	</a>
</li>
<li class="{{ (Request::is('groups*')) ? 'active' : '' }}">
	<a href="{{ route('groups.index') }}" ><strong><span class="fa fa-list"></span> {{ trans('holdingssets.groups')}}</strong> </a>
</li>
@if ((Session::get(Auth::user()->username.'_last_route') == '') && ($_COOKIE[Auth::user()->username.'_last_route'] != ''))
	<li class="btn btn-xs btn-warning">
		<a href="{{ $_COOKIE[Auth::user()->username.'_last_route'] }}" ><strong><span class="fa fa-repeat"></span> {{ trans('holdingssets.go_to_last_session')}}</strong> </a>
	</li>
	<?php Session::put(Auth::user()->username.'_last_route', $_COOKIE[Auth::user()->username.'_last_route']); ?>
@endif
