<li{{ (Request::is('admin/roles*') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/roles') }}}">
	<span class="glyphicon glyphicon-lock"></span> {{{ trans('titles.roles') }}}</a>
</li>
<li {{ (Request::is('admin/users*') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/users') }}}">
	<span class="glyphicon glyphicon-user"></span>{{{ trans('titles.users') }}}</a>
</li>
<li></li>
