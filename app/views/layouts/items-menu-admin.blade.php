@if ( Auth::user()->hasRole('sysadmin') ) 		
	<li{{ (Request::is('admin/roles*') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/roles') }}}">
		<span class="fa fa-lock"></span> {{{ trans('titles.roles') }}}</a>
	</li>
@endif
<li {{ (Request::is('admin/users*') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/users') }}}">
	<span class="fa fa-users"></span>{{{ trans('titles.users') }}}</a>
</li>
