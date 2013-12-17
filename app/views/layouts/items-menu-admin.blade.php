@if ( Auth::user()->hasRole('sysadmin') ) 		
	<li{{ (Request::is('admin/roles*') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/roles') }}}">
		<span class="fa fa-lock"></span> {{{ trans('titles.roles') }}}</a>
	</li>
@endif

<li {{ (Request::is('admin/users*') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/users') }}}">
	<span class="fa fa-users"></span> {{{ trans('titles.users') }}}</a>
</li>

@if ( Authority::can('manage', 'Feedback') )
	<li {{ (Request::is('admin/feedbacks*') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/feedbacks') }}}">
		<span class="fa fa-bug"></span> {{{ trans('titles.feedbacks') }}}</a>
	</li>
@endif
